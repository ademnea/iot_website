#!/bin/sh
set -e
cd /var/www/html

# storage/ and the upload folders are volumes; recreate the layout and let Apache write to them.
mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions \
         storage/framework/views storage/logs bootstrap/cache public/images public/documents
for dir in images documents; do
    [ -f "public/$dir/.htaccess" ] || cp /usr/local/share/upload-guard.htaccess "public/$dir/.htaccess"
done
chown -R www-data:www-data storage bootstrap/cache public/images public/documents

if [ "$1" = "apache2-foreground" ]; then
    # Only the web server needs the key; one-off commands (e.g. key:generate) run without it.
    if [ -z "$APP_KEY" ]; then
        echo "APP_KEY is not set. Run: docker compose run --rm --no-deps app php artisan key:generate --show" >&2
        echo "and put the value in .env as APP_KEY=..." >&2
        exit 1
    fi

    echo "Waiting for the database at $DB_HOST:$DB_PORT..."
    tries=0
    until php -r 'new PDO("mysql:host=".getenv("DB_HOST").";port=".getenv("DB_PORT"), getenv("DB_USERNAME"), getenv("DB_PASSWORD"));' 2>/dev/null; do
        tries=$((tries + 1))
        [ "$tries" -ge 60 ] && { echo "Database not reachable after 120s." >&2; exit 1; }
        sleep 2
    done

    if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
        php artisan migrate --force
    fi
    # Older releases stored admin passwords as plain text; this hashes any that are left. Safe to repeat.
    php artisan admin:hash-passwords

    php artisan config:cache
    php artisan view:cache
fi

exec "$@"
