<?php

namespace App\Livewire\Admin;

use App\Models\Ticket;
use App\Models\TellerCategory;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $stats = [];
    public $todayTickets = [];
    public $categories;
    public $tellers;

    public function mount()
    {
        $this->loadStats();
        // Cache categories and tellers for 5 minutes
        $this->categories = cache()->remember('admin_categories', 300, function() {
            return TellerCategory::select('id', 'name', 'prefix')->get();
        });
        $this->tellers = cache()->remember('admin_tellers', 300, function() {
            return User::where('role', 'teller')->select('id', 'name', 'email', 'category_id', 'counter_name')->get();
        });
    }

    public function loadStats()
    {
        $today = Ticket::today();
        
        // Optimize: Use single query with grouping instead of multiple count queries
        $statusCounts = Ticket::forToday()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        $this->stats = [
            'total_today' => array_sum($statusCounts),
            'waiting' => $statusCounts['waiting'] ?? 0,
            'serving' => $statusCounts['serving'] ?? 0,
            'done' => $statusCounts['done'] ?? 0,
            'skipped' => $statusCounts['skipped'] ?? 0,
            'total_categories' => TellerCategory::count(),
            'total_tellers' => User::where('role', 'teller')->count(),
            'active_tellers' => User::where('role', 'teller')
                ->whereHas('tickets', function($query) use ($today) {
                    $query->forToday()->serving();
                })
                ->count(),
        ];

        $this->todayTickets = Ticket::forToday()
            ->with(['category:id,name,prefix', 'teller:id,name'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
