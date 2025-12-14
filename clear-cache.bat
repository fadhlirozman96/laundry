@echo off
echo Clearing Laravel Cache...
echo.

cd C:\laragon\www\laundry

echo [1/5] Clearing route cache...
php artisan route:clear

echo [2/5] Clearing config cache...
php artisan config:clear

echo [3/5] Clearing application cache...
php artisan cache:clear

echo [4/5] Clearing view cache...
php artisan view:clear

echo [5/5] Clearing compiled classes...
php artisan clear-compiled

echo.
echo ================================
echo All caches cleared successfully!
echo ================================
echo.
echo Now refresh your browser (Ctrl + F5)
echo.
pause

