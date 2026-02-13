@echo off
setlocal
REM ============================================================================
REM Deployment Script for Queueing Cashier System
REM ============================================================================
REM This script automates the deployment process:
REM 1. Pull latest changes from git
REM 2. Install PHP dependencies
REM 3. Copy .env.public to .env
REM 4. Generate application key
REM 5. Run database migrations
REM 6. Create storage link
REM 7. Install Node dependencies
REM 8. Build frontend assets
REM ============================================================================

REM Prevent script from closing on errors
setlocal enabledelayedexpansion
set "EXIT_CODE=0"

REM Initialize variables for error tracking
set "KEY_GEN_RESULT=0"
set "MIGRATE_RESULT=0"
set "COMPOSER_RESULT=0"

echo.
echo ============================================================================
echo   Queueing Cashier - Deployment Script
echo ============================================================================
echo.

REM Error handling is already set above

REM Change to script directory to ensure we're in the right place
cd /d "%~dp0"

REM Check if we're in the right directory
if not exist "artisan" (
    echo ERROR: artisan file not found. Please run this script from the Laravel root directory.
    echo Current directory: %CD%
    pause
    exit /b 1
)

REM Step 1: Git Pull
echo [1/8] Pulling latest changes from git master...
git pull origin master
if errorlevel 1 (
    echo WARNING: Git pull failed. Continuing anyway...
) else (
    echo ✓ Git pull completed successfully.
)
echo.

REM Step 2: Composer Install
echo [2/8] Installing PHP dependencies with Composer...
call composer install --no-interaction --prefer-dist --optimize-autoloader
set COMPOSER_RESULT=!errorlevel!
if !COMPOSER_RESULT! NEQ 0 (
    echo WARNING: Composer install returned error code !COMPOSER_RESULT!
    echo This may be normal if dependencies are already installed.
    echo Continuing with deployment...
) else (
    echo ✓ Composer install completed successfully.
)
echo.

REM Step 3: Copy .env.public to .env
echo [3/8] Copying .env.public to .env...
if exist ".env.public" (
    copy /Y .env.public .env >nul
    echo ✓ .env file created from .env.public
) else (
    echo WARNING: .env.public file not found. Skipping...
)
echo.

REM Step 4: Generate Application Key
echo [4/8] Checking application key...
set "SKIP_KEY_GEN=0"
if exist .env (
    findstr /C:"APP_KEY=base64:" .env >nul 2>&1
    if not errorlevel 1 (
        set "SKIP_KEY_GEN=1"
    )
)
if "!SKIP_KEY_GEN!"=="1" (
    echo ✓ Application key already exists. Skipping generation.
) else (
    echo Attempting to generate application key...
    call php artisan key:generate --force >nul 2>&1
    if errorlevel 1 (
        echo WARNING: Key generation failed, but continuing...
    ) else (
        echo ✓ Application key generated successfully.
    )
)
echo.

REM Step 5: Run Migrations
echo [5/8] Running database migrations...
php artisan migrate --force
if errorlevel 1 (
    echo ERROR: Database migration failed!
    echo Please check your database connection in .env file.
    set "EXIT_CODE=1"
    goto :end_script
)
echo ✓ Database migrations completed successfully.
echo.

REM Step 6: Create Storage Link
echo [6/8] Creating storage symbolic link...
php artisan storage:link 2>&1
if errorlevel 1 (
    echo WARNING: Storage link creation failed. Link may already exist.
) else (
    echo ✓ Storage link created successfully.
)
echo.

REM Step 7: NPM Install
echo [7/8] Installing Node.js dependencies...
call npm install
if errorlevel 1 (
    echo ERROR: npm install failed!
    set "EXIT_CODE=1"
    goto :end_script
)
echo ✓ npm install completed successfully.
echo.

REM Step 8: Build Frontend Assets
echo [8/8] Building frontend assets...
call npm run build
if errorlevel 1 (
    echo ERROR: npm run build failed!
    set "EXIT_CODE=1"
    goto :end_script
)
echo ✓ Frontend assets built successfully.
echo.

REM Clear caches
echo [Bonus] Clearing application caches...
php artisan config:clear >nul 2>&1
php artisan cache:clear >nul 2>&1
php artisan view:clear >nul 2>&1
echo ✓ Caches cleared.
echo.

:end_script
echo.
if %EXIT_CODE% EQU 0 (
    echo ============================================================================
    echo   Deployment completed successfully!
    echo ============================================================================
) else (
    echo ============================================================================
    echo   Deployment completed with errors!
    echo ============================================================================
)
echo.
echo Next steps:
echo   1. Update .env file with your actual server IP address
echo   2. Start Laravel Reverb: php artisan reverb:start
echo   3. Start Laravel server: php artisan serve --host=0.0.0.0 --port=8000
echo.
echo Press any key to exit...
pause >nul
endlocal
if %EXIT_CODE% EQU 0 (exit /b 0) else (exit /b %EXIT_CODE%)
