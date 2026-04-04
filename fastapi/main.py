import os
import tempfile
from typing import List, Optional

import usb.backend.libusb1
from escpos.printer import Usb
from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from PIL import Image, ImageDraw, ImageFont
from pydantic import BaseModel, Field

try:
    # Bundles a libusb backend on Windows for PyUSB.
    import libusb_package  # type: ignore
except Exception:
    libusb_package = None


# Set your USB IDs in hex, for example:
#   setx POS58_VID 0x0FE6
#   setx POS58_PID 0x811E
VID = int(os.getenv("POS58_VID", "0x0FE6"), 16)
PID = int(os.getenv("POS58_PID", "0x811E"), 16)


class PrintItem(BaseModel):
    name: str = Field(..., min_length=1)
    amount: Optional[float] = None


class PrintTicketRequest(BaseModel):
    store_name: str = "NORSU-GUIHULNGAN Queue System"
    ticket_code: str = Field(..., min_length=1)
    category: str = "Queue"
    date: Optional[str] = None
    time: Optional[str] = None
    items: List[PrintItem] = []
    total: Optional[float] = None
    footer: str = "THANK YOU!"


app = FastAPI(title="Kiosk Print API", version="1.0.0")

kiosk_origin = os.getenv("KIOSK_ORIGIN", "*")
app.add_middleware(
    CORSMiddleware,
    allow_origins=[kiosk_origin] if kiosk_origin != "*" else ["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)


def get_printer() -> Usb:
    try:
        backend = None
        if libusb_package is not None:
            backend = usb.backend.libusb1.get_backend(
                find_library=lambda name: libusb_package.find_library(name)
            )
        return Usb(VID, PID, backend=backend)
    except Exception as exc:
        raise RuntimeError(
            f"Unable to open printer VID={VID:#06x} PID={PID:#06x}: {exc}. "
            "Tip: install a WinUSB/libusb driver with Zadig for this USB device."
        ) from exc


def print_large_ticket_code(printer: Usb, ticket_code: str) -> None:
    """Print ticket code as smooth text image for firmware that ignores ESC/POS sizing."""
    font_size = int(os.getenv("TICKET_CODE_FONT_SIZE", "96"))
    font_candidates = [
        os.getenv("TICKET_CODE_FONT_PATH"),
        r"C:\Windows\Fonts\arialbd.ttf",
        r"C:\Windows\Fonts\segoeuib.ttf",
        r"C:\Windows\Fonts\calibrib.ttf",
    ]

    font = None
    for font_path in font_candidates:
        if not font_path:
            continue
        if os.path.exists(font_path):
            try:
                font = ImageFont.truetype(font_path, font_size)
                break
            except Exception:
                continue

    if font is None:
        font = ImageFont.load_default()

    measure = Image.new("L", (1, 1), 255)
    measure_draw = ImageDraw.Draw(measure)
    left, top, right, bottom = measure_draw.textbbox((0, 0), ticket_code, font=font)
    text_w = max(1, right - left)
    text_h = max(1, bottom - top)

    # Extra bottom padding avoids clipping on some POS58 models.
    pad_x = 20
    pad_top = 12
    pad_bottom = 8

    canvas_w = text_w + (pad_x * 2)
    canvas_h = text_h + pad_top + pad_bottom

    # Some ESC/POS raster paths behave better with 24-dot aligned heights.
    band_height = 24
    remainder = canvas_h % band_height
    if remainder:
        canvas_h += (band_height - remainder)

    final_img = Image.new("L", (canvas_w, canvas_h), 255)
    final_draw = ImageDraw.Draw(final_img)

    # Respect bbox origin to keep all glyph parts (curves/descenders) visible.
    draw_x = pad_x - left
    draw_y = pad_top - top
    final_draw.text((draw_x, draw_y), ticket_code, font=font, fill=0)

    # Threshold to get cleaner thermal output without heavy dithering noise.
    final_img = final_img.point(lambda p: 0 if p < 170 else 255, mode="1")

    temp_path = None
    try:
        with tempfile.NamedTemporaryFile(suffix=".png", delete=False) as tmp:
            temp_path = tmp.name
        final_img.save(temp_path)
        printer.image(temp_path)
    finally:
        if temp_path and os.path.exists(temp_path):
            os.remove(temp_path)


def print_ticket(payload: PrintTicketRequest) -> None:
    printer = get_printer()

    try:
        date_text = payload.date or "N/A"
        time_text = payload.time or "N/A"

        printer.set(align="left", bold=False)
        printer.text("================================\n")

        printer.set(align="center", bold=True)
        printer.text(f"{payload.store_name}\n")

        printer.set(align="left", bold=False)
        printer.text("================================\n\n")

        printer.set(align="center", bold=True)
        printer.text("TICKET NUMBER\n")
        try:
            print_large_ticket_code(printer, payload.ticket_code)
        except Exception:
            # Fallback for devices that still reject image printing.
            printer.set(align="center", bold=True, width=4, height=4)
            printer.text(f"{payload.ticket_code}")

        printer.set(align="left", bold=False, width=1, height=1)

        printer.text("--------------------------------\n")
        printer.text(f"Category : {payload.category}\n")
        printer.text(f"Date     : {date_text}\n")
        printer.text(f"Time     : {time_text}\n")
        printer.text("--------------------------------\n")

        printer.set(align="center", bold=True)
        printer.text(f"{payload.footer}\n")

        printer.set(align="left", bold=False)
        printer.text("================================")

        # Some POS58 units don't have an auto-cutter; ignore cut errors.
        try:
            printer.cut()
        except Exception:
            pass
    except Exception as exc:
        raise RuntimeError(
            f"Print failed for VID={VID:#06x} PID={PID:#06x}: {exc}. "
            "On Windows, bind this USB device to WinUSB (libusb) using Zadig, then retry."
        ) from exc
    finally:
        # Always release the USB handle so next requests can access the device.
        try:
            printer.close()
        except Exception:
            pass


@app.get("/health")
def health() -> dict:
    return {"status": "ok", "vid": f"{VID:#06x}", "pid": f"{PID:#06x}"}


@app.get("/printer-status")
def printer_status() -> dict:
    printer = None
    try:
        printer = get_printer()
        return {
            "status": "ok",
            "connected": True,
            "vid": f"{VID:#06x}",
            "pid": f"{PID:#06x}",
            "note": "USB printer opened successfully.",
        }
    except Exception as exc:
        raise HTTPException(
            status_code=503,
            detail={
                "status": "error",
                "connected": False,
                "vid": f"{VID:#06x}",
                "pid": f"{PID:#06x}",
                "message": str(exc),
            },
        ) from exc
    finally:
        if printer is not None:
            try:
                printer.close()
            except Exception:
                pass


@app.post("/print-ticket")
def api_print_ticket(payload: PrintTicketRequest) -> dict:
    try:
        print_ticket(payload)
        return {"ok": True, "ticket_code": payload.ticket_code}
    except Exception as exc:
        raise HTTPException(status_code=500, detail=str(exc)) from exc


if __name__ == "__main__":
    # Keep compatibility with direct python execution.
    import uvicorn

    host = os.getenv("FASTAPI_HOST", "127.0.0.1")
    port = int(os.getenv("FASTAPI_PORT", "8000"))
    uvicorn.run("main:app", host=host, port=port, reload=False)
