<div>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Teller</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('teller.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">My Queue</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <!-- Current Ticket Section -->
    @if($currentTicket)
    <div class="card rounded-4 border-success border-3 shadow-lg mb-4" wire:key="current-ticket-{{ $currentTicket->id }}">
        <div class="card-header bg-success text-white py-3">
            <h4 class="mb-0 d-flex align-items-center gap-2">
                <i class="material-icons-outlined">volume_up</i>
                <span>NOW SERVING</span>
            </h4>
        </div>
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <i class="material-icons-outlined text-success" style="font-size: 80px;">receipt_long</i>
            </div>
            <div class="ticket-display-large mb-3" wire:ignore>
                <span id="currentTicketCode" class="ticket-code-large">{{ $currentTicket->code }}</span>
            </div>
            <div class="ticket-info mb-4">
                <span class="badge bg-primary fs-6 px-3 py-2">{{ $currentTicket->category->name ?? 'N/A' }}</span>
                <p class="text-secondary mt-2 mb-0">Started at: {{ $currentTicket->updated_at->format('h:i A') }}</p>
            </div>
            <div class="d-flex flex-column flex-md-row gap-2 justify-content-center">
                <button type="button" 
                        class="btn btn-info btn-lg px-3 px-md-5 raised d-flex align-items-center justify-content-center gap-2 w-100 w-md-auto" 
                        wire:click="recall"
                        wire:loading.attr="disabled">
                    <i class="material-icons-outlined" wire:loading.remove wire:target="recall">volume_up</i>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading wire:target="recall"></span>
                    <span>Recall</span>
                </button>
                <button type="button" 
                        class="btn btn-success btn-lg px-3 px-md-5 raised d-flex align-items-center justify-content-center gap-2 w-100 w-md-auto" 
                        wire:click="markDone"
                        wire:loading.attr="disabled">
                    <i class="material-icons-outlined" wire:loading.remove wire:target="markDone">check_circle</i>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading wire:target="markDone"></span>
                    <span>Mark as Done</span>
                </button>
                <button type="button" 
                        class="btn btn-warning btn-lg px-3 px-md-5 raised d-flex align-items-center justify-content-center gap-2 w-100 w-md-auto" 
                        wire:click="skip"
                        wire:loading.attr="disabled">
                    <i class="material-icons-outlined" wire:loading.remove wire:target="skip">skip_next</i>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading wire:target="skip"></span>
                    <span>Skip</span>
                </button>
            </div>
        </div>
    </div>
    @else
    <!-- No Current Ticket -->
    <div class="card rounded-4 border-warning border-2 mb-4">
        <div class="card-body text-center py-5">
            <i class="material-icons-outlined text-warning" style="font-size: 80px;">hourglass_empty</i>
            <h4 class="mt-3 text-secondary">No ticket currently being served</h4>
            <p class="text-secondary">Click "Call Next" to start serving a ticket</p>
        </div>
    </div>
    @endif

    <!-- Action Button -->
    <div class="text-center mb-4">
        <button type="button" 
                id="callNextBtn"
                class="btn btn-primary btn-lg px-3 px-md-5 raised d-flex align-items-center justify-content-center gap-2 mx-auto w-100 w-md-auto" 
                wire:click="callNext"
                wire:loading.attr="disabled"
                onclick="handleCallNextClick(event)"
                {{ $waitingTickets->count() == 0 ? 'disabled' : '' }}>
            <i class="material-icons-outlined" wire:loading.remove wire:target="callNext">call</i>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading wire:target="callNext"></span>
            <span wire:loading.remove wire:target="callNext">Call Next Ticket</span>
            <span wire:loading wire:target="callNext">Calling...</span>
        </button>
    </div>

    <div class="row g-3">
        <!-- Waiting Tickets Queue -->
        <div class="col-lg-8">
            <div class="card rounded-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="material-icons-outlined">queue</i>
                        <span>Waiting Queue ({{ $waitingTickets->count() }})</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($waitingTickets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Ticket Number</th>
                                        <th>Category</th>
                                        <th>Generated At</th>
                                        <th>Wait Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($waitingTickets as $index => $ticket)
                                        <tr class="{{ $index == 0 ? 'table-warning' : '' }}">
                                            <td>
                                                @if($index == 0)
                                                    <span class="badge bg-warning text-dark">Next</span>
                                                @else
                                                    <span class="text-secondary">{{ $index + 1 }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold fs-5 text-primary">{{ $ticket->code }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $ticket->category->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <small class="text-secondary">{{ $ticket->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <small class="text-secondary">{{ $ticket->created_at->diffForHumans() }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="material-icons-outlined text-secondary" style="font-size: 64px;">inbox</i>
                            <p class="text-secondary mt-3 mb-0">No waiting tickets</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Up Next Preview -->
        <div class="col-lg-4">
            <div class="card rounded-4">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="material-icons-outlined">schedule</i>
                        <span>Up Next</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($upNextTickets->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($upNextTickets as $ticket)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <span class="fw-bold text-primary">{{ $ticket->code }}</span>
                                        <br>
                                        <small class="text-secondary">{{ $ticket->category->name ?? 'N/A' }}</small>
                                    </div>
                                    <span class="badge bg-info">{{ $loop->iteration }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="material-icons-outlined text-secondary" style="font-size: 48px;">schedule</i>
                            <p class="text-secondary mt-2 mb-0 small">No upcoming tickets</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card rounded-4 mt-3">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="material-icons-outlined">assessment</i>
                        <span>Today's Stats</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="stat-box">
                                <div class="stat-number text-primary">{{ $waitingTickets->count() }}</div>
                                <div class="stat-label small text-secondary">Waiting</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box">
                                <div class="stat-number text-success">{{ $currentTicket ? 1 : 0 }}</div>
                                <div class="stat-label small text-secondary">Serving</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .ticket-code-large {
            font-size: 5rem;
            font-weight: 900;
            color: #0d6efd;
            letter-spacing: 8px;
            display: block;
            line-height: 1.2;
        }

        .stat-box {
            padding: 15px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            line-height: 1;
        }

        .stat-label {
            margin-top: 5px;
        }

        .table-warning {
            background-color: #fff3cd !important;
        }

        @media (max-width: 768px) {
            .ticket-code-large {
                font-size: 3rem;
                letter-spacing: 4px;
            }
        }

        /* Blinking animation for ticket number */
        @keyframes blink {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.05); }
        }

        .ticket-code-large.blinking {
            animation: blink 0.4s ease-in-out infinite;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // WebSocket listeners for immediate ticket updates
        let echoListenersInitialized = false;
        let echoChannel = null;
        const currentUserId = @json(Auth::id());

        function initializeEchoListeners() {
            if (echoListenersInitialized) {
                return;
            }

            if (window.Echo && window.Livewire) {
                echoListenersInitialized = true;

                if (!echoChannel) {
                    echoChannel = window.Echo.channel('tickets');
                }
                
                // Listen for ticket called - update immediately
                echoChannel.listen('.ticket.called', (e) => {
                    if (e.ticket && e.ticket.teller && e.ticket.teller.id === currentUserId) {
                        // This ticket is for current teller - update IMMEDIATELY (no delays)
                        const ticketData = {
                            code: e.ticket.code || '',
                            category: e.ticket.category?.name || ''
                        };
                        
                        // Update display IMMEDIATELY (synchronous, no delays)
                        updateTicketDisplayImmediately(ticketData);
                        
                        // Trigger Livewire refresh in background (non-blocking)
                        // Small delay to ensure DOM update is visible first
                        setTimeout(() => {
                            if (window.Livewire) {
                                const component = document.querySelector('[wire\\:id]');
                                if (component) {
                                    const componentId = component.getAttribute('wire:id');
                                    const livewireComponent = window.Livewire.find(componentId);
                                    if (livewireComponent) {
                                        livewireComponent.call('handleRefreshQueue');
                                    }
                                }
                            }
                        }, 100);
                    }
                })
                // Listen for ticket recalled - update and blink
                .listen('.ticket.recalled', (e) => {
                    if (e.ticket && e.ticket.teller && e.ticket.teller.id === currentUserId) {
                        // This ticket is for current teller - update immediately and blink
                        const ticketData = {
                            code: e.ticket.code || '',
                            category: e.ticket.category?.name || ''
                        };
                        updateTicketDisplayImmediately(ticketData);
                        blinkTicketNumber(ticketData.code, 8);
                    }
                })
                // Listen for ticket completed - clear display
                .listen('.ticket.completed', (e) => {
                    if (e.ticket && e.ticket.teller && e.ticket.teller.id === currentUserId) {
                        // This ticket was completed by current teller - clear display
                        clearTicketDisplay();
                        
                        // Trigger Livewire refresh in background
                        setTimeout(() => {
                            if (window.Livewire) {
                                const component = document.querySelector('[wire\\:id]');
                                if (component) {
                                    const componentId = component.getAttribute('wire:id');
                                    const livewireComponent = window.Livewire.find(componentId);
                                    if (livewireComponent) {
                                        livewireComponent.call('handleRefreshQueue');
                                    }
                                }
                            }
                        }, 100);
                    }
                })
                // Listen for ticket created - refresh waiting list
                .listen('.ticket.created', (e) => {
                    // Refresh waiting list when new ticket is created
                    setTimeout(() => {
                        if (window.Livewire) {
                            const component = document.querySelector('[wire\\:id]');
                            if (component) {
                                const componentId = component.getAttribute('wire:id');
                                const livewireComponent = window.Livewire.find(componentId);
                                if (livewireComponent) {
                                    livewireComponent.call('handleRefreshQueue');
                                }
                            }
                        }
                    }, 100);
                });
            } else {
                if (!window.Echo) {
                    setTimeout(initializeEchoListeners, 500);
                } else if (!window.Livewire) {
                    setTimeout(initializeEchoListeners, 500);
                }
            }
        }

        // Create ticket card structure if it doesn't exist (optimized for speed)
        function ensureTicketCardExists() {
            // Check for both real Livewire cards and temp cards
            let ticketCard = document.querySelector('[wire\\:key^="current-ticket-"]');
            
            // If no card exists and we're in the "no ticket" state (check for the "No Current Ticket" card)
            const noTicketCard = document.querySelector('.card.border-warning');
            const hasRealTicketCard = document.querySelector('[wire\\:key^="current-ticket-"]:not([wire\\:key="current-ticket-temp"])');
            
            // Only create temp card if:
            // 1. No real Livewire card exists
            // 2. We're in the "no ticket" state (noTicketCard exists)
            // 3. No temp card already exists
            if (!hasRealTicketCard && noTicketCard && !ticketCard) {
                // Find insertion point (breadcrumb or action button area)
                const breadcrumb = document.querySelector('.page-breadcrumb');
                const insertionPoint = breadcrumb ? breadcrumb.nextElementSibling : document.querySelector('.text-center.mb-4');
                if (!insertionPoint && !breadcrumb) return null;
                
                // Create card element directly (faster than innerHTML)
                ticketCard = document.createElement('div');
                ticketCard.className = 'card rounded-4 border-success border-3 shadow-lg mb-4';
                ticketCard.setAttribute('wire:key', 'current-ticket-temp');
                
                ticketCard.innerHTML = `
                    <div class="card-header bg-success text-white py-3">
                        <h4 class="mb-0 d-flex align-items-center gap-2">
                            <i class="material-icons-outlined">volume_up</i>
                            <span>NOW SERVING</span>
                        </h4>
                    </div>
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="material-icons-outlined text-success" style="font-size: 80px;">receipt_long</i>
                        </div>
                        <div class="ticket-display-large mb-3" wire:ignore>
                            <span id="currentTicketCode" class="ticket-code-large"></span>
                        </div>
                        <div class="ticket-info mb-4">
                            <span class="badge bg-primary fs-6 px-3 py-2" id="currentTicketCategory"></span>
                            <p class="text-secondary mt-2 mb-0" id="currentTicketTime"></p>
                        </div>
                        <div class="d-flex flex-column flex-md-row gap-2 justify-content-center">
                            <button type="button" class="btn btn-info btn-lg px-3 px-md-5 raised d-flex align-items-center justify-content-center gap-2 w-100 w-md-auto" wire:click="recall" wire:loading.attr="disabled">
                                <i class="material-icons-outlined" wire:loading.remove wire:target="recall">volume_up</i>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading wire:target="recall"></span>
                                <span>Recall</span>
                            </button>
                            <button type="button" class="btn btn-success btn-lg px-3 px-md-5 raised d-flex align-items-center justify-content-center gap-2 w-100 w-md-auto" wire:click="markDone" wire:loading.attr="disabled">
                                <i class="material-icons-outlined" wire:loading.remove wire:target="markDone">check_circle</i>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading wire:target="markDone"></span>
                                <span>Mark as Done</span>
                            </button>
                            <button type="button" class="btn btn-warning btn-lg px-3 px-md-5 raised d-flex align-items-center justify-content-center gap-2 w-100 w-md-auto" wire:click="skip" wire:loading.attr="disabled">
                                <i class="material-icons-outlined" wire:loading.remove wire:target="skip">skip_next</i>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading wire:target="skip"></span>
                                <span>Skip</span>
                            </button>
                        </div>
                    </div>
                `;
                
                // Insert immediately (replace the "no ticket" card)
                if (noTicketCard && noTicketCard.parentNode) {
                    noTicketCard.parentNode.replaceChild(ticketCard, noTicketCard);
                } else if (breadcrumb) {
                    breadcrumb.insertAdjacentElement('afterend', ticketCard);
                } else if (insertionPoint) {
                    insertionPoint.insertAdjacentElement('beforebegin', ticketCard);
                }
            }
            
            return ticketCard;
        }

        // Clear ticket display immediately
        function clearTicketDisplay() {
            const ticketCard = document.querySelector('[wire\\:key^="current-ticket-"]');
            if (ticketCard) {
                // Hide the card smoothly, Livewire will remove it
                ticketCard.style.transition = 'opacity 0.3s';
                ticketCard.style.opacity = '0';
                setTimeout(() => {
                    ticketCard.remove();
                }, 300);
            }
            // Clear the last update tracking
            lastImmediateUpdate = null;
        }

        // Blink ticket number animation
        function blinkTicketNumber(ticketCode, times) {
            const ticketCodeElement = document.getElementById('currentTicketCode');
            if (!ticketCodeElement) return;
            
            // Add blinking class
            ticketCodeElement.classList.add('blinking');
            
            // Remove blinking class after animation completes
            setTimeout(() => {
                ticketCodeElement.classList.remove('blinking');
            }, times * 400); // 400ms per blink cycle (matches CSS animation duration)
        }

        // Make function globally accessible
        window.blinkTicketNumber = blinkTicketNumber;

        // Intercept callNext button click to update display immediately
        function handleCallNextClick(event) {
            // Check if card already exists (like in recall scenario)
            let ticketCodeElement = document.getElementById('currentTicketCode');
            
            // If card doesn't exist, get ticket info from waiting table and create card immediately
            if (!ticketCodeElement) {
                const waitingTable = document.querySelector('.table tbody');
                if (waitingTable) {
                    const firstRow = waitingTable.querySelector('tr');
                    if (firstRow) {
                        const ticketCodeCell = firstRow.querySelector('td:nth-child(2)');
                        if (ticketCodeCell) {
                            const ticketCode = ticketCodeCell.textContent.trim();
                            const categoryCell = firstRow.querySelector('td:nth-child(3)');
                            const categoryName = categoryCell ? categoryCell.textContent.trim() : '';
                            
                            // Create card and update display IMMEDIATELY (synchronously, before Livewire processes)
                            const ticketData = {
                                code: ticketCode,
                                category: categoryName
                            };
                            
                            // This creates the card synchronously if it doesn't exist
                            updateTicketDisplayImmediately(ticketData);
                            
                            // Store this as the immediate update
                            lastImmediateUpdate = {
                                code: ticketCode,
                                timestamp: Date.now()
                            };
                            
                            // The WebSocket event will confirm/update this when it arrives
                        }
                    }
                }
            }
            // If card already exists, WebSocket event will handle the update (like recall)
        }

        // Initialize listeners
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeEchoListeners);
        } else {
            initializeEchoListeners();
        }

        document.addEventListener('livewire:init', initializeEchoListeners);
        
        // Re-initialize after Livewire updates (DOM morphing)
        document.addEventListener('livewire:morph', () => {
            // Reset initialization flag so listeners can be re-setup if needed
            echoListenersInitialized = false;
            setTimeout(initializeEchoListeners, 100);
        });
        
        // Store the last ticket code we updated to prevent Livewire from overwriting it
        let lastImmediateUpdate = null;
        
        // Update the stored value when we do an immediate update
        function updateTicketDisplayImmediately(ticketData) {
            if (!ticketData || !ticketData.code) return;
            
            // Ensure the card exists first (this is synchronous)
            const ticketCard = ensureTicketCardExists();
            if (!ticketCard) {
                console.warn('Could not create ticket card');
                return;
            }
            
            let ticketCodeElement = document.getElementById('currentTicketCode');
            
            // If element still doesn't exist, create it
            if (!ticketCodeElement) {
                const displayDiv = ticketCard.querySelector('.ticket-display-large');
                if (displayDiv) {
                    ticketCodeElement = document.createElement('span');
                    ticketCodeElement.id = 'currentTicketCode';
                    ticketCodeElement.className = 'ticket-code-large';
                    displayDiv.appendChild(ticketCodeElement);
                }
            }
            
            if (ticketCodeElement) {
                // Store this update FIRST (before DOM update)
                lastImmediateUpdate = {
                    code: ticketData.code,
                    timestamp: Date.now()
                };
                
                // Update ticket code immediately (synchronous DOM manipulation)
                ticketCodeElement.textContent = ticketData.code;
                
                // Force a reflow to ensure the update is visible
                ticketCodeElement.offsetHeight;
                
                // Update category if element exists
                let categoryBadge = document.getElementById('currentTicketCategory');
                if (!categoryBadge) {
                    categoryBadge = ticketCard.querySelector('.badge.bg-primary');
                }
                if (categoryBadge && ticketData.category) {
                    categoryBadge.textContent = ticketData.category;
                }
                
                // Update time
                const timeElement = document.getElementById('currentTicketTime');
                if (timeElement) {
                    const now = new Date();
                    const timeStr = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
                    timeElement.textContent = `Started at: ${timeStr}`;
                }
                
                // Blink the ticket number
                blinkTicketNumber(ticketData.code, 8);
            }
        }
        
        // After Livewire morphs, clean up temp cards and preserve immediate updates
        document.addEventListener('livewire:morph', () => {
            // Remove any temporary cards created by JavaScript (they have wire:key="current-ticket-temp")
            const tempCards = document.querySelectorAll('[wire\\:key="current-ticket-temp"]');
            tempCards.forEach(card => card.remove());
            
            // Only restore if we have a recent immediate update
            if (lastImmediateUpdate && (Date.now() - lastImmediateUpdate.timestamp) < 2000) {
                // Use requestAnimationFrame for immediate restoration
                requestAnimationFrame(() => {
                    const ticketCodeElement = document.getElementById('currentTicketCode');
                    if (ticketCodeElement && lastImmediateUpdate.code) {
                        // Restore the immediate update
                        ticketCodeElement.textContent = lastImmediateUpdate.code;
                    }
                });
            }
        });
    </script>
@endpush
