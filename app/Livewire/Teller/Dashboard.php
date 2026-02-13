<?php

namespace App\Livewire\Teller;

use App\Models\Ticket;
use App\Models\Serving;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $stats = [];
    public $todayTickets = [];
    public $recentTickets = [];
    public $currentTicket = null;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $user = Auth::user();
        
        if (!$user->category_id) {
            return;
        }

        // Optimize: Use single query with grouping for stats
        $statusCounts = Ticket::forCategory($user->category_id)
            ->forToday()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        $myStatusCounts = Ticket::forCategory($user->category_id)
            ->forToday()
            ->where('teller_id', $user->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $this->stats = [
            'total_today' => array_sum($statusCounts),
            'waiting' => $statusCounts['waiting'] ?? 0,
            'serving' => $myStatusCounts['serving'] ?? 0,
            'done' => $myStatusCounts['done'] ?? 0,
        ];

        // Get current serving ticket
        $this->currentTicket = Ticket::forCategory($user->category_id)
            ->forToday()
            ->serving()
            ->where('teller_id', $user->id)
            ->with('category:id,name,prefix')
            ->first();

        // Get recent tickets served by this teller
        $this->recentTickets = Ticket::forCategory($user->category_id)
            ->forToday()
            ->where('teller_id', $user->id)
            ->whereIn('status', ['done', 'skipped'])
            ->with('category:id,name,prefix')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.teller.dashboard');
    }
}
