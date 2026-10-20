#!/bin/sh
# Runs once, when the database volume is first created: a separate database for the test suite.
mariadb -uroot -p"$MARIADB_ROOT_PASSWORD" <<SQL
CREATE DATABASE IF NOT EXISTS iot_website_test;
GRANT ALL PRIVILEGES ON iot_website_test.* TO '$MARIADB_USER'@'%';
FLUSH PRIVILEGES;
SQL
