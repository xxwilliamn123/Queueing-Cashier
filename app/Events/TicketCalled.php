<?php

namespace App\Events;

use App\Models\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketCalled implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Ticket $ticket;

    /**
     * Create a new event instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('tickets'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ticket.called';
    }

    public function broadcastWith(): array
    {
        // Ensure category and teller relationships are loaded
        $this->ticket->load(['category', 'teller']);
        
        return [
            'ticket' => [
                'id' => $this->ticket->id,
                'code' => $this->ticket->full_code,
                'type' => $this->ticket->type,
                'status' => $this->ticket->status,
                'category' => [
                    'id' => $this->ticket->category->id ?? null,
                    'name' => $this->ticket->category->name ?? null,
                ],
                'teller' => [
                    'id' => $this->ticket->teller->id ?? null,
                    'name' => $this->ticket->teller->name ?? null,
                    'counter_name' => $this->ticket->teller->counter_name ?? null,
                ],
            ],
        ];
    }
}
