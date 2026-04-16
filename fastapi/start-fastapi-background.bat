@echo off
REM Auto-start FastAPI in the background.
REM pythonw.exe exits quickly with uvicorn (no stdio streams), so we use python.exe
REM in a detached cmd and redirect output to fastapi-background.log.

setlocal
cd /d "%~dp0"
set "LOG=%~dp0fastapi-background.log"

>>"%LOG%" echo [%date% %time%] start-fastapi-background.bat started

if not exist ".venv\Scripts\python.exe" (
    >>"%LOG%" echo [%date% %time%] Creating venv...
    py -3.11 -m venv .venv 1>>"%LOG%" 2>>&1
    if errorlevel 1 (
        >>"%LOG%" echo [%date% %time%] ERROR: py -3.11 -m venv failed. Install Python 3.11 and ensure py launcher works.
        exit /b 1
    )
    call .venv\Scripts\activate.bat
    python -m pip install -q --upgrade pip 1>>"%LOG%" 2>>&1
    pip install -q -r requirements.txt 1>>"%LOG%" 2>>&1
    if not exist .env copy .env.example .env >nul
)

if not exist ".venv\Scripts\python.exe" (
    >>"%LOG%" echo [%date% %time%] ERROR: .venv\Scripts\python.exe missing. Run start-fastapi.bat once from this folder.
    exit /b 1
)

REM Detached process (survives after this .bat exits). Window title is required by START.
start "" cmd /c ""%~dp0.venv\Scripts\python.exe" "%~dp0run_uvicorn.py" 1>>"%LOG%" 2>>&1"
>>"%LOG%" echo [%date% %time%] start issued for python run_uvicorn.py (detached)

endlocal
exit /b 0
