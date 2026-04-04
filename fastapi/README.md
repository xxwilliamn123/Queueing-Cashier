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
- start FastAPI at `http://127.0.0.1:8000`

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

`GET http://127.0.0.1:8000/health`

Print test payload:

`POST http://127.0.0.1:8000/print-ticket`

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
