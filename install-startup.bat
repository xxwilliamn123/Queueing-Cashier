@echo off
REM ============================================================================
REM Install Startup Files Script
REM ============================================================================
REM This script copies start-reverb-background.vbs and XAMPP Control Panel
REM shortcut to Windows Startup folder
REM ============================================================================

echo.
echo ============================================================================
echo   Installing Files to Windows Startup Folder
echo ============================================================================
echo.

REM Set paths
set "PROJECT_DIR=%~dp0"
set "STARTUP_FOLDER=%APPDATA%\Microsoft\Windows\Start Menu\Programs\Startup"
set "XAMPP_PATH=C:\xampp"
set "XAMPP_CONTROL=%XAMPP_PATH%\xampp-control.exe"

REM Check if startup folder exists
if not exist "%STARTUP_FOLDER%" (
    echo ERROR: Startup folder not found at:
    echo %STARTUP_FOLDER%
    pause
    exit /b 1
)

echo Startup folder: %STARTUP_FOLDER%
echo.

REM Step 1: Copy VBScript file
echo [1/2] Copying start-reverb-background.vbs...
if exist "%PROJECT_DIR%start-reverb-background.vbs" (
    copy /Y "%PROJECT_DIR%start-reverb-background.vbs" "%STARTUP_FOLDER%\" >nul
    if errorlevel 1 (
        echo ERROR: Failed to copy VBScript file.
        pause
        exit /b 1
    )
    echo ✓ VBScript copied successfully.
) else (
    echo ERROR: start-reverb-background.vbs not found in project directory.
    echo Project directory: %PROJECT_DIR%
    pause
    exit /b 1
)
echo.

REM Step 2: Create XAMPP Control Panel shortcut
echo [2/2] Creating XAMPP Control Panel shortcut...
if exist "%XAMPP_CONTROL%" (
    REM Create shortcut using PowerShell
    powershell -Command "$WshShell = New-Object -ComObject WScript.Shell; $Shortcut = $WshShell.CreateShortcut('%STARTUP_FOLDER%\XAMPP Control Panel.lnk'); $Shortcut.TargetPath = '%XAMPP_CONTROL%'; $Shortcut.WorkingDirectory = '%XAMPP_PATH%'; $Shortcut.Description = 'XAMPP Control Panel'; $Shortcut.Save()" >nul 2>&1
    
    if errorlevel 1 (
        echo WARNING: Failed to create shortcut using PowerShell.
        echo Attempting alternative method...
        
        REM Alternative: Copy existing shortcut if it exists
        if exist "%XAMPP_PATH%\xampp-control.exe.lnk" (
            copy /Y "%XAMPP_PATH%\xampp-control.exe.lnk" "%STARTUP_FOLDER%\XAMPP Control Panel.lnk" >nul
            echo ✓ XAMPP shortcut copied successfully.
        ) else (
            echo ERROR: Could not create XAMPP shortcut.
            echo Please create it manually:
            echo 1. Right-click xampp-control.exe
            echo 2. Create shortcut
            echo 3. Copy shortcut to: %STARTUP_FOLDER%
        )
    ) else (
        echo ✓ XAMPP Control Panel shortcut created successfully.
    )
) else (
    echo WARNING: XAMPP Control Panel not found at: %XAMPP_CONTROL%
    echo Please update XAMPP_PATH in this script if XAMPP is installed elsewhere.
    echo Skipping XAMPP shortcut creation...
)
echo.

echo ============================================================================
echo   Installation Complete!
echo ============================================================================
echo.
echo Files installed to startup folder:
echo   - start-reverb-background.vbs
echo   - XAMPP Control Panel.lnk
echo.
echo These files will run automatically when Windows starts.
echo.
echo To verify:
echo   1. Press Win + R
echo   2. Type: shell:startup
echo   3. Check if files are present
echo.
pause
