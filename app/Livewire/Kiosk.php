<?php

namespace App\Livewire;

use App\Events\TicketCreated;
use App\Models\TellerCategory;
use App\Services\TicketService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class Kiosk extends Component
{
    public $categories;
    public $selectedCategory = null; // Will be set by JavaScript before generateTicket
    public $showTicketModal = false;
    public $generatedTicket = null;
    public $isKeyVerified = false;
    public $secretKey = '';

    public function mount()
    {
        // Check if key is already verified in session
        $this->isKeyVerified = session()->get('kiosk_key_verified', false);
        
        if ($this->isKeyVerified) {
            // Cache categories to avoid repeated queries
            $this->loadCategories();
        }
    }

    protected function loadCategories()
    {
        // Use cache to avoid repeated database queries
        $this->categories = cache()->remember('kiosk_categories', 3600, function () {
            return TellerCategory::select('id', 'name', 'prefix')->orderBy('name')->get();
        });
    }

    public function verifyKey()
    {
        $this->validate([
            'secretKey' => 'required|string',
        ]);

        $envKey = env('KIOSK_SECRET_KEY');
        
        // If KIOSK_SECRET_KEY is not set, use APP_KEY as fallback
        if (empty($envKey)) {
            $envKey = config('app.key');
        }
        
        if (hash_equals($envKey, $this->secretKey)) {
            $this->isKeyVerified = true;
            session()->put('kiosk_key_verified', true);
            $this->loadCategories();
            $this->secretKey = '';
            $this->dispatch('toastr', ['type' => 'success', 'message' => 'Access granted!']);
            $this->dispatch('hide-printing-overlay');
        } else {
            $this->dispatch('toastr', ['type' => 'error', 'message' => 'Invalid secret key. Access denied.']);
            $this->secretKey = '';
        }
    }

    public function generateTicket($categoryId = null)
    {
        // Use parameter if provided, otherwise use property (set by JavaScript)
        $categoryId = $categoryId ?? $this->selectedCategory;
        
        if (!$categoryId) {
            $this->dispatch('toastr', ['type' => 'error', 'message' => 'Please select a ticket type']);
            return;
        }

        $ticketService = new TicketService();
        $ticket = $ticketService->generateTicket($categoryId);

        // Eager load category to avoid N+1 queries
        $ticket->load('category:id,name,prefix');

        // Broadcast event (non-blocking)
        event(new TicketCreated($ticket));

        // Store ticket info and show modal
        $this->generatedTicket = $ticket;
        $this->showTicketModal = true;
        $this->selectedCategory = null;
        
        // Clear category cache after ticket creation
        cache()->forget('kiosk_categories');
    }

    public function closeTicketModal()
    {
        $this->showTicketModal = false;
        $this->generatedTicket = null;
    }

    public function render()
    {
        return view('livewire.kiosk');
    }
}
