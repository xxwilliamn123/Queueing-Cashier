<?php

namespace App\Livewire\Admin;

use App\Models\Ticket;
use App\Models\TellerCategory;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Reports extends Component
{
    public $startDate;
    public $endDate;
    public $stats = [];
    public $categoryStats = [];
    public $tellerStats = [];

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->loadReports();
    }

    public function updatedStartDate()
    {
        $this->loadReports();
    }

    public function updatedEndDate()
    {
        $this->loadReports();
    }

    public function loadReports()
    {
        $query = Ticket::whereBetween('ticket_date', [$this->startDate, $this->endDate]);

        // Overall Statistics
        $this->stats = [
            'total' => $query->count(),
            'waiting' => (clone $query)->where('status', 'waiting')->count(),
            'serving' => (clone $query)->where('status', 'serving')->count(),
            'done' => (clone $query)->where('status', 'done')->count(),
            'skipped' => (clone $query)->where('status', 'skipped')->count(),
        ];

        // Category Statistics
        $this->categoryStats = TellerCategory::withCount([
            'tickets' => function($q) {
                $q->whereBetween('ticket_date', [$this->startDate, $this->endDate]);
            },
            'tickets as done_count' => function($q) {
                $q->whereBetween('ticket_date', [$this->startDate, $this->endDate])
                  ->where('status', 'done');
            }
        ])->get();

        // Teller Statistics - Use whereHas to filter tellers with tickets, then get counts
        $this->tellerStats = User::where('role', 'teller')
            ->whereHas('tickets', function($q) {
                $q->whereBetween('ticket_date', [$this->startDate, $this->endDate]);
            })
            ->withCount([
                'tickets' => function($q) {
                    $q->whereBetween('ticket_date', [$this->startDate, $this->endDate]);
                },
                'tickets as done_count' => function($q) {
                    $q->whereBetween('ticket_date', [$this->startDate, $this->endDate])
                      ->where('status', 'done');
                }
            ])
            ->orderBy('tickets_count', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.reports');
    }
}
