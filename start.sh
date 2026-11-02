#!/bin/sh
set -e

mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite
chown -R www-data:www-data /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache

sed -i "s/Listen 80/Listen ${PORT:-80}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT:-80}>/" /etc/apache2/sites-available/000-default.conf

php artisan package:discover --ansi || true
php artisan config:cache
php artisan migrate --force
php artisan storage:link || true

exec apache2-foreground