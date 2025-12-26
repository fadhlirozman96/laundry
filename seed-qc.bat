@echo off
echo Running Product QC Mode Seeder...
echo.

REM Find PHP executable
set PHP_PATH=C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe

echo Using PHP: %PHP_PATH%
echo.

REM Run seeder
%PHP_PATH% artisan db:seed --class=ProductQcModeSeeder

echo.
echo Seeding completed!
pause


