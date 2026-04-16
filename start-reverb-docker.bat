@echo off
setlocal
cd /d "%~dp0"

echo Building Reverb image...
docker build -f Dockerfile.reverb -t queueing-reverb .
if errorlevel 1 (
  echo Build failed.
  exit /b 1
)

echo Recreating Reverb container...
docker rm -f queueing-reverb >nul 2>&1
docker run -d --name queueing-reverb --restart unless-stopped -p 8080:8080 --env-file .env -v "%cd%:/var/www/html" queueing-reverb
if errorlevel 1 (
  echo Container start failed.
  exit /b 1
)

echo Reverb container is running.
docker ps --filter "name=queueing-reverb"
