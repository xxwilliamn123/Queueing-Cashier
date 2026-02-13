<?php

namespace App\Livewire\Teller;

use App\Events\TicketCalled;
use App\Events\TicketCompleted;
use App\Events\TicketRecalled;
use App\Models\Serving;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]
class MyQueue extends Component
{
    public $waitingTickets = [];
    public $currentTicket = null;
    public $upNextTickets = [];

    public function mount()
    {
        $this->loadTickets();
    }

    public function loadTickets()
    {
        $user = Auth::user();
        
        if (!$user->category_id) {
            return;
        }

        // Optimize: Single query instead of 3 separate queries
        $tickets = Ticket::forCategory($user->category_id)
            ->forToday()
            ->with('category:id,name,prefix')
            ->orderBy('id', 'asc')
            ->get();
        
        // Filter from collection (much faster than separate queries)
        $this->waitingTickets = $tickets->where('status', 'waiting')->values();
        
        // Get current serving ticket - ensure it's fresh from database
        $this->currentTicket = Ticket::forCategory($user->category_id)
            ->forToday()
            ->serving()
            ->where('teller_id', $user->id)
            ->with('category:id,name,prefix')
            ->first();
        
        $this->upNextTickets = $tickets->where('status', 'waiting')->take(5)->values();
    }

    public function callNext()
    {
        $user = Auth::user();
        
        if (!$user->category_id) {
            $this->dispatch('toastr', ['type' => 'error', 'message' => 'You are not assigned to a category']);
            return;
        }

        // Check if there's already a ticket being served by this teller
        $existingServing = Ticket::forCategory($user->category_id)
            ->forToday()
            ->serving()
            ->where('teller_id', $user->id)
            ->first();
        
        if ($existingServing) {
            $this->dispatch('toastr', ['type' => 'error', 'message' => 'Please complete or skip the current ticket first']);
            return;
        }

        $ticket = Ticket::forCategory($user->category_id)
            ->forToday()
            ->waiting()
            ->with('category:id,name,prefix')
            ->orderBy('id', 'asc')
            ->first();

        if (!$ticket) {
            $this->dispatch('toastr', ['type' => 'error', 'message' => 'No waiting tickets']);
            return;
        }

        $ticket->update([
            'status' => 'serving',
            'teller_id' => $user->id,
        ]);

        Serving::create([
            'ticket_id' => $ticket->id,
            'teller_id' => $user->id,
            'started_at' => now(),
        ]);

        // Reload ticket with relationships before broadcasting
        $ticket = $ticket->fresh(['category', 'teller']);
        
        // Update currentTicket FIRST (before broadcasting) so the card exists when WebSocket event fires
        $this->currentTicket = $ticket;
        
        // Broadcast event (this will trigger WebSocket update)
        event(new TicketCalled($ticket));
        
        // Reload tickets to update waiting list and upNext
        $this->loadTickets();
        
        $this->dispatch('toastr', ['type' => 'success', 'message' => 'Ticket ' . $ticket->code . ' called']);
    }

    public function markDone()
    {
        if (!$this->currentTicket) {
            return;
        }

        $ticketId = $this->currentTicket->id;
        $this->currentTicket->update(['status' => 'done']);
        
        $serving = Serving::where('ticket_id', $ticketId)
            ->whereNull('ended_at')
            ->first();
        
        if ($serving) {
            $serving->update(['ended_at' => now()]);
        }

        // Reload ticket with relationships before broadcasting
        $ticket = Ticket::with(['category', 'teller'])->find($ticketId);
        event(new TicketCompleted($ticket));
        
        // Clear current ticket immediately
        $this->currentTicket = null;
        
        // Reload tickets to update waiting list
        $this->loadTickets();
        
        $this->dispatch('toastr', ['type' => 'success', 'message' => 'Ticket marked as done']);
    }

    public function skip()
    {
        if (!$this->currentTicket) {
            return;
        }

        $ticketId = $this->currentTicket->id;
        $this->currentTicket->update(['status' => 'skipped']);
        
        // Reload ticket with relationships before broadcasting
        $ticket = Ticket::with(['category', 'teller'])->find($ticketId);
        event(new TicketCompleted($ticket));
        
        // Clear current ticket immediately
        $this->currentTicket = null;
        
        // Reload tickets to update waiting list
        $this->loadTickets();
        
        $this->dispatch('toastr', ['type' => 'success', 'message' => 'Ticket skipped']);
    }

    public function recall()
    {
        // Reload current ticket from database to ensure we have the latest
        $user = Auth::user();
        
        if (!$user->category_id) {
            $this->dispatch('toastr', ['type' => 'error', 'message' => 'You are not assigned to a category']);
            return;
        }

        // Get the current serving ticket from database (not from property which might be stale)
        $ticket = Ticket::forCategory($user->category_id)
            ->forToday()
            ->serving()
            ->where('teller_id', $user->id)
            ->with(['category', 'teller'])
            ->first();

        if (!$ticket) {
            $this->dispatch('toastr', ['type' => 'error', 'message' => 'No ticket currently being served']);
            // Update currentTicket property
            $this->currentTicket = null;
            $this->loadTickets();
            return;
        }

        // Update currentTicket property with fresh data
        $this->currentTicket = $ticket;
        
        // Reload tickets to ensure everything is in sync
        $this->loadTickets();
        
        // Broadcast recall event
        event(new TicketRecalled($ticket));
        
        $this->dispatch('toastr', ['type' => 'success', 'message' => 'Ticket ' . $ticket->code . ' recalled']);
    }

    #[On('refresh-queue')]
    public function handleRefreshQueue()
    {
        $this->loadTickets();
    }

    public function render()
    {
        return view('livewire.teller.my-queue');
    }
}
