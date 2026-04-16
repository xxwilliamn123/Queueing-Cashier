"""Start Uvicorn using FASTAPI_HOST / FASTAPI_PORT from fastapi/.env."""
from pathlib import Path

from dotenv import load_dotenv

load_dotenv(Path(__file__).resolve().parent / ".env", override=False)

import os

import uvicorn

host = os.getenv("FASTAPI_HOST", "127.0.0.1")
port = int(os.getenv("FASTAPI_PORT", "8000"))

uvicorn.run("main:app", host=host, port=port, reload=False)
