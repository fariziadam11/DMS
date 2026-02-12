#!/bin/bash

# Debug Script untuk Error 500 di Production
# Usage: bash debug_production.sh

echo "=== Debugging Production Error 500 ==="
echo ""

echo "1. Checking Laravel Logs..."
echo "---"
tail -50 storage/logs/laravel.log
echo ""

echo "2. Checking PHP-FPM Logs..."
echo "---"
sudo tail -50 /var/log/php8.1-fpm.log
echo ""

echo "3. Checking Nginx Error Logs..."
echo "---"
sudo tail -50 /var/log/nginx/error.log
echo ""

echo "4. Checking File Permissions..."
echo "---"
ls -la storage/
ls -la bootstrap/cache/
echo ""

echo "5. Checking PHP Extensions..."
echo "---"
php -m | grep -E "(ftp|mysql|mbstring|xml)"
echo ""

echo "6. Testing Database Connection..."
echo "---"
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database OK';"
echo ""

echo "7. Checking Config Cache..."
echo "---"
ls -la bootstrap/cache/config.php
echo ""

echo "=== Debug Complete ==="
echo ""
echo "Common fixes:"
echo "1. Clear cache: php artisan config:clear && php artisan cache:clear"
echo "2. Fix permissions: sudo chown -R www-data:www-data storage bootstrap/cache"
echo "3. Check .env: Make sure all required variables are set"
echo "4. Check composer: composer install --no-dev"
