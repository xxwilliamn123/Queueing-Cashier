<?php

namespace App\Livewire\Admin;

use App\Models\Ticket;
use App\Models\TellerCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Tickets extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $categoryFilter = '';
    public $dateFilter = '';

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        // Don't set default date filter - show all tickets by default
        $this->dateFilter = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Ticket::with(['category', 'teller'])
            ->orderBy('created_at', 'desc');

        // Date filter
        if ($this->dateFilter) {
            $query->whereDate('ticket_date', $this->dateFilter);
        }

        // Status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Category filter - Note: tickets table uses 'type' column, not 'category_id'
        if ($this->categoryFilter && $this->categoryFilter !== '') {
            $query->where('type', (int)$this->categoryFilter);
        }

        // Search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('code', 'like', '%' . $this->search . '%')
                  ->orWhereHas('category', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('teller', function($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $tickets = $query->paginate(20);
        $categories = TellerCategory::all();

        return view('livewire.admin.tickets', [
            'tickets' => $tickets,
            'categories' => $categories,
        ]);
    }
}
