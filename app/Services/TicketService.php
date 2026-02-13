<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TellerCategory;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function generateTicket(int $categoryId): Ticket
    {
        return DB::transaction(function () use ($categoryId) {
            $today = Ticket::today();
            // Only select needed fields
            $category = TellerCategory::select('id', 'name', 'prefix')->findOrFail($categoryId);

            // Get the last ticket for today across ALL categories (not just this category)
            // Only select code field to minimize data transfer
            $lastTicket = Ticket::select('id', 'code')
                ->forToday()
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();

            // Get the next number (stored as number only, not with prefix)
            if ($lastTicket) {
                // Get the raw code from attributes (bypasses accessor)
                $rawCode = $lastTicket->getAttributes()['code'] ?? $lastTicket->getOriginal('code');
                
                // If code contains prefix (old format), extract number
                // Otherwise, code is already just the number
                if (preg_match('/\d+$/', $rawCode, $matches)) {
                    $lastNumber = intval($matches[0]);
                } else {
                    $lastNumber = intval($rawCode);
                }
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            // Store only the number (e.g., "001" not "P001")
            $ticketNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $ticket = Ticket::create([
                'code' => $ticketNumber, // Store only number
                'type' => $categoryId,
                'ticket_date' => $today,
                'status' => 'waiting',
            ]);

            return $ticket;
        });
    }
}

