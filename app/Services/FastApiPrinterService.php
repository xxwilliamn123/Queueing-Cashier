<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FastApiPrinterService
{
    public function printTicketById(int $ticketId): bool
    {
        $ticket = Ticket::with('category:id,name,prefix')->find($ticketId);

        if (!$ticket) {
            Log::warning('FastAPI print skipped: ticket not found', ['ticket_id' => $ticketId]);
            return false;
        }

        return $this->printTicket($ticket);
    }

    public function printTicket(Ticket $ticket): bool
    {
        if (!config('services.fastapi_print.enabled', true)) {
            return false;
        }

        $baseUrl = rtrim((string) config('services.fastapi_print.base_url', 'http://127.0.0.1:8000'), '/');
        $timeout = (int) config('services.fastapi_print.timeout', 5);

        $ticket->loadMissing('category:id,name,prefix');

        $payload = [
            'store_name' => (string) config('app.name', 'Queue System'),
            'ticket_code' => (string) $ticket->code,
            'category' => (string) optional($ticket->category)->name ?: 'Queue',
            'date' => optional($ticket->ticket_date)->format('F d, Y'),
            'time' => optional($ticket->created_at)->format('h:i A'),
            'footer' => 'THANK YOU!',
        ];

        try {
            $response = Http::connectTimeout(2)
                ->timeout($timeout)
                ->retry(2, 150)
                ->post($baseUrl . '/print-ticket', $payload);

            if ($response->successful()) {
                return true;
            }

            Log::warning('FastAPI print request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'ticket_id' => $ticket->id,
                'ticket_code' => $ticket->code,
            ]);
        } catch (\Throwable $e) {
            Log::warning('FastAPI print request exception', [
                'message' => $e->getMessage(),
                'ticket_id' => $ticket->id,
                'ticket_code' => $ticket->code,
            ]);
        }

        return false;
    }
}
