@echo off
REM Laravel Reverb Auto-Start Script (Background)
REM This script builds frontend assets and starts Laravel Reverb in a separate window

REM Change to project directory
cd /d "C:\xampp\htdocs\Queueing Cashier"

REM Check if artisan exists
if not exist "artisan" (
    echo ERROR: artisan file not found.
    echo Please update the path in this script to match your project location.
    echo Current path: C:\xampp\htdocs\Queueing Cashier
    pause
    exit /b 1
)

echo.
echo ============================================================================
echo   Building Frontend Assets
echo ============================================================================
echo Project path: C:\xampp\htdocs\Queueing Cashier
echo.

REM Build frontend assets
echo Running npm run build...
call npm run build
if errorlevel 1 (
    echo WARNING: npm run build returned an error, but continuing...
) else (
    echo ✓ Frontend assets built successfully.
)
echo.

REM Start Reverb in new window
echo Starting Laravel Reverb...
start "Laravel Reverb" cmd /k "cd /d C:\xampp\htdocs\Queueing Cashier && php artisan reverb:start"

echo ✓ Laravel Reverb started in a new window.
echo.
echo This window will close automatically...
timeout /t 2 /nobreak >nul
exit