@echo off
setlocal
cd /d %~dp0

if not exist .venv (
  echo Creating virtual environment in .venv ...
  py -3.11 -m venv .venv
)

call .venv\Scripts\activate.bat
python -m pip install --upgrade pip
pip install -r requirements.txt

if not exist .env (
  copy .env.example .env >nul
)

python -m uvicorn main:app --host 127.0.0.1 --port 8000
