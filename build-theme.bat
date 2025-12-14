@echo off
echo ====================================
echo Building Rapy Laundry Blue Theme
echo ====================================
echo.

cd C:\laragon\www\laundry

echo Compiling SCSS to CSS with new blue colors...
echo.

call npm run build

echo.
echo ====================================
echo Build Complete!
echo ====================================
echo.
echo Your theme has been updated to:
echo - Primary Color: #0067e2 (Rapy Blue)
echo - Secondary Color: #50b0ff (Light Blue)
echo.
echo Refresh your browser (Ctrl + F5) to see the changes!
echo.
pause


