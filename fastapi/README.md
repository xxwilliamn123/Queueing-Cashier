# FastAPI USB Printer Service

This folder is an isolated Python/FastAPI service for USB POS printing.

## 1) Create and use isolated environment

From this folder, run:

```bat
start-fastapi.bat
```

That script will:
- create `.venv` (Python 3.11 via `py -3.11`)
- install requirements
- copy `.env.example` to `.env` if needed
- start FastAPI using `FASTAPI_HOST` and `FASTAPI_PORT` from `fastapi/.env` (defaults: `127.0.0.1`, `8000`)

### Auto-start on Windows (without Docker)

Docker is fine for APIs that do not need USB. **USB POS printing on Windows must run on the host** (same machine as Zadig / WinUSB). To still auto-start after login:

1. Run `start-fastapi.bat` once so `.venv` and dependencies exist.
2. Optional: edit `FASTAPI_HOST` / `FASTAPI_PORT` in `fastapi/.env` (default `127.0.0.1:8000`). If something else already uses port `8000` (for example `php artisan serve`), pick another port there.
3. Put a shortcut to `fastapi/start-fastapi-background.vbs` in your Startup folder (`Win+R` → `shell:startup`), **or** use Task Scheduler to run that `.vbs` at logon.

The `.vbs` file uses **its own folder** (no hardcoded path). Put the shortcut next to the real project, or copy the whole `fastapi` folder consistently.

**If nothing seems to happen at logon:** open `fastapi/fastapi-background.log`. It records each launch and errors (for example missing `.venv` or `py -3.11` failing).

**If double-clicking `start-fastapi-background.bat` closes instantly:** that is normal after the fix; the window exits on purpose while **`pythonw.exe`** keeps running. Check Task Manager for `pythonw.exe` or open `http://127.0.0.1:8000/health` (or your `FASTAPI_PORT`).

**Startup shortcut:** target the **`.vbs`** file inside `fastapi` (not a copy with broken paths). One shortcut is enough; two copies at logon can make the second instance fail on “port already in use”.

## 2) Configure USB IDs

Edit `.env` values if needed:
- `POS58_VID`
- `POS58_PID`

Example:

```env
POS58_VID=0x0FE6
POS58_PID=0x811E
```

## 3) Test API

Health check:

`GET http://127.0.0.1:8000/health` (or your server IP and the port set in `FASTAPI_PORT`)

Print test payload:

`POST http://127.0.0.1:8000/print-ticket` (same host/port as above)

Body:

```json
{
  "store_name": "NORSU-GUIHULNGAN Queue System",
  "ticket_code": "A-001",
  "category": "Registrar",
  "date": "April 04, 2026",
  "time": "09:30 AM",
  "footer": "THANK YOU!"
}
```

## Windows USB Driver Note

For many POS58 USB printers on Windows, bind the printer device to WinUSB/libusb using Zadig.
