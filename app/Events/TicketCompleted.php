<?php

namespace App\Events;

use App\Models\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketCompleted implements ShouldBroadcastNow
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
        return 'ticket.completed';
    }

    public function broadcastWith(): array
    {
        $this->ticket->load(['category', 'teller']);

        return [
            'ticket' => [
                'id' => $this->ticket->id,
                'code' => $this->ticket->full_code,
                'status' => $this->ticket->status,
                'teller_id' => $this->ticket->teller_id,
                'teller' => $this->ticket->teller ? [
                    'id' => $this->ticket->teller->id,
                    'name' => $this->ticket->teller->name,
                    'counter_name' => $this->ticket->teller->counter_name,
                ] : null,
            ],
        ];
    }
}
