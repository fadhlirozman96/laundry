@echo off
echo Running Laravel Migrations...
echo.

cd /d "%~dp0"

REM Find PHP executable in Laragon
set PHP_PATH=
for /d %%i in (C:\laragon\bin\php\php-*) do set PHP_PATH=%%i\php.exe

if not exist "%PHP_PATH%" (
    echo ERROR: PHP not found in Laragon!
    echo Please make sure Laragon is installed correctly.
    pause
    exit /b 1
)

echo Using PHP: %PHP_PATH%
echo.

"%PHP_PATH%" artisan migrate --force

echo.
echo Migration completed!
echo.
pause


