@echo off
echo ================================
echo Setting User as Superadmin
echo ================================
echo.
set /p email="Enter user email: "
C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe artisan tinker --execute="$user = \App\Models\User::where('email', '%email%')->first(); if($user) { $user->update(['role' => 'superadmin']); echo 'SUCCESS: ' . $user->name . ' is now a superadmin!'; } else { echo 'ERROR: User not found!'; }"
echo.
pause

