#!/bin/sh
set -e

echo "DEBUG DB_HOST=${DB_HOST:-NOT SET} DB_DATABASE=${DB_DATABASE:-NOT SET} DB_PORT=${DB_PORT:-NOT SET}"

sed -i "s/Listen 80/Listen ${PORT:-80}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT:-80}>/" /etc/apache2/sites-available/000-default.conf

php artisan package:discover --ansi || true
php artisan config:cache
php artisan migrate --force
php artisan storage:link || true

exec apache2-foreground