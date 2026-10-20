# syntax=docker/dockerfile:1

# PHP dependencies (no dev packages in the production image).
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --optimize --no-dev --no-scripts

# PHP dependencies including dev packages, for the test image. Cached until composer.lock changes.
FROM composer:2 AS vendor-dev
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --prefer-dist --no-interaction --ignore-platform-reqs

# Front-end assets built with Vite. Tailwind scans the Blade views in vendor/ too.
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
COPY --from=vendor /app/vendor ./vendor
RUN npm run build

# Runtime: Apache + mod_php, so the .htaccess rules in public/ keep working.
FROM php:8.4-apache AS app
# Fail the build if any command in a pipe fails, not just the last one.
SHELL ["/bin/bash", "-o", "pipefail", "-c"]
# Debian security updates, then the PHP extensions. Afterwards the compilers and -dev packages
# are removed again and only the shared libraries the extensions link against are kept
# (the same pattern the official PHP images use), so the image ships no build toolchain.
RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y; \
    savedAptMark="$(apt-mark showmanual)"; \
    apt-get install -y --no-install-recommends libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libicu-dev; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" pdo_mysql zip gd intl opcache; \
    apt-mark auto '.*' > /dev/null; \
    apt-mark manual $savedAptMark > /dev/null; \
    apt-mark auto $PHPIZE_DEPS libc6-dev > /dev/null; \
    find /usr/local -type f -name '*.so' -exec ldd '{}' ';' \
        | awk '/=>/ { so = $(NF-1); if (index(so, "/usr/local/") == 1) { next }; gsub("^/(usr/)?", "", so); printf "*%s\n", so }' \
        | sort -u | xargs -r dpkg-query --search | cut -d: -f1 | sort -u \
        | grep -vE -- '-dev$' | xargs -r apt-mark manual; \
    apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false; \
    rm -rf /var/lib/apt/lists/*; \
    for m in pdo_mysql zip gd intl 'Zend OPcache'; do php -m | grep -qx "$m"; done; \
    command -v curl; \
    a2enmod rewrite headers

COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-iot.ini
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/apache/security.conf /etc/apache2/conf-available/zz-iot-security.conf
RUN a2enconf zz-iot-security

WORKDIR /var/www/html
COPY --chown=www-data:www-data . .
COPY --from=vendor --chown=www-data:www-data /app/vendor ./vendor
COPY --from=assets --chown=www-data:www-data /app/public/build ./public/build
COPY docker/entrypoint.sh /usr/local/bin/iot-entrypoint
RUN chmod +x /usr/local/bin/iot-entrypoint \
 && cp public/images/.htaccess /usr/local/share/upload-guard.htaccess

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=0
EXPOSE 80
# Healthy only when Apache, PHP and the database all answer (the login page reads site content).
HEALTHCHECK --interval=30s --timeout=5s --start-period=60s --retries=3 \
    CMD ["curl", "-fsS", "-o", "/dev/null", "http://127.0.0.1/login"]
ENTRYPOINT ["iot-entrypoint"]
CMD ["apache2-foreground"]

# Test image: the app plus dev dependencies, runs the test suite.
FROM app AS test
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY --from=vendor-dev --chown=www-data:www-data /app/vendor ./vendor
# Placeholder .env (example values only) so the test runner has a file to read; real settings
# come from the environment. The production image has no .env file.
RUN cp .env.example .env \
 && composer dump-autoload --no-interaction \
 && php artisan package:discover --ansi
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=1
HEALTHCHECK NONE
CMD ["php", "artisan", "test"]
