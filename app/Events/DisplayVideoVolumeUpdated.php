<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DisplayVideoVolumeUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $volumePercent
    ) {}

    /**
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
        return 'display.video.volume';
    }

    /**
     * @return array{volumePercent: int}
     */
    public function broadcastWith(): array
    {
        return [
            'volumePercent' => $this->volumePercent,
        ];
    }
}
