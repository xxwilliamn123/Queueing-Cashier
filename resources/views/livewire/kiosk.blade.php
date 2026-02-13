<div>
    <!-- Secret Key Entry Form -->
    @if(!$isKeyVerified)
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Kiosk Access</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="material-icons-outlined text-primary" style="font-size: 64px;">lock</i>
                            <h4 class="mt-3">Enter Secret Key</h4>
                            <p class="text-secondary">Please enter the secret key to access the kiosk system.</p>
                        </div>
                        <form wire:submit.prevent="verifyKey">
                            <div class="mb-3">
                                <label for="secretKey" class="form-label">Secret Key</label>
                                <input type="password" 
                                       class="form-control @error('secretKey') is-invalid @enderror" 
                                       id="secretKey" 
                                       wire:model="secretKey" 
                                       placeholder="Enter secret key"
                                       autofocus>
                                @error('secretKey')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="material-icons-outlined me-2">vpn_key</i>Verify & Access
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Kiosk Interface - Only shown when key is verified -->
    @if($isKeyVerified)
    <!-- Loading/Printing Overlay - Shows only when generateTicket is executing -->
    <div id="printingOverlay" 
         wire:ignore
         class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
         style="background-color: rgba(255, 255, 255, 0.95); z-index: 9999; display: none !important; pointer-events: none;">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="mt-4">
                <i class="material-icons-outlined" style="font-size: 64px; color: #0d6efd;">print</i>
            </div>
            <h3 class="mt-3 text-primary fw-bold">Printing Ticket...</h3>
            <p class="text-secondary">Please wait while your ticket is being generated and printed.</p>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">NORSU-GUIHULNGAN Queue System</h3>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-4">Select Ticket Type</h5>
                        
                        <div class="row g-3">
                            @foreach($categories as $category)
                                <div class="col-md-4" wire:key="category-{{ $category->id }}">
                                    <button 
                                        type="button"
                                        data-category-id="{{ $category->id }}"
                                        class="btn btn-outline-primary w-100 p-4 category-btn"
                                        style="min-height: 120px; font-size: 1.2rem;">
                                        <strong>{{ $category->name }}</strong><br>
                                        <small>Prefix: {{ $category->prefix }}</small>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 text-center">
                            <button 
                                id="generateTicketBtn"
                                class="btn btn-success btn-lg px-5"
                                disabled>
                                <span id="generateBtnText">Generate Ticket</span>
                                <span id="generateBtnLoading" style="display: none;">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Generating...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <style>
            /* Printing overlay styles */
            #printingOverlay {
                display: none !important;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.2s ease-in-out;
            }
            
            #printingOverlay.show {
                display: flex !important;
                opacity: 1;
                pointer-events: auto;
            }
        </style>
    @endpush

    <!-- Ticket Success Modal -->
    @if($showTicketModal && $generatedTicket)
    <div id="ticketModal" 
         class="modal fade show" 
         wire:key="ticket-modal-{{ $generatedTicket->id }}"
         style="display: block; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1055; overflow-x: hidden; overflow-y: auto;" 
         tabindex="-1" 
         role="dialog" 
         aria-modal="true">
        <div class="modal-backdrop fade show" style="position: fixed; top: 0; left: 0; z-index: 1054; width: 100vw; height: 100vh; background-color: rgba(0, 0, 0, 0.5);" onclick="closeModalInstantly()"></div>
        <div class="modal-dialog modal-dialog-centered" style="position: relative; z-index: 1056; pointer-events: auto;">
            <div class="modal-content" style="pointer-events: auto;">
                <div class="modal-header border-bottom-0 py-3 bg-success text-white">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i class="material-icons-outlined">check_circle</i>
                        Ticket Generated Successfully!
                    </h5>
                    <button type="button" 
                            class="btn-close btn-close-white" 
                            onclick="closeModalInstantly()"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-5">
                    <div class="mb-4">
                        <i class="material-icons-outlined text-success" style="font-size: 80px;">receipt_long</i>
                    </div>
                    <div class="mb-3">
                        <span class="fw-bold text-primary" style="font-size: 4.5rem; letter-spacing: 2px;">{{ $generatedTicket->code }}</span>
                    </div>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="row g-3 text-start">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-secondary">Ticket Number:</span>
                                        <span class="fw-bold fs-5">{{ $generatedTicket->code }}</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-secondary">Category:</span>
                                        <span class="fw-bold">{{ $generatedTicket->category->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-secondary">Date:</span>
                                        <span class="fw-bold">{{ $generatedTicket->ticket_date->format('F d, Y') }}</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-secondary">Time:</span>
                                        <span class="fw-bold">{{ $generatedTicket->created_at->format('h:i A') }}</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-secondary">Status:</span>
                                        <span class="badge bg-warning text-dark">{{ ucfirst($generatedTicket->status) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info d-flex align-items-center gap-2 mb-0">
                        <i class="material-icons-outlined">info</i>
                        <span>Please wait for your number to be called. Keep this ticket safe.</span>
                    </div>
                </div>
                <div class="modal-footer border-top-0 justify-content-center">
                    <button type="button" 
                            class="btn btn-primary px-4 raised d-flex gap-2" 
                            onclick="closeModalInstantly()">
                        <i class="material-icons-outlined">close</i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @endif

    @push('scripts')
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script>
            // Category selection handled purely in JavaScript
            let selectedCategoryId = null;

            // Use event delegation for category buttons (works even after Livewire updates)
            // Define selectCategory first to ensure it's available
            function selectCategory(categoryId) {
                selectedCategoryId = categoryId;
                updateCategoryButtons();
                updateGenerateButton();
            }

            // Event delegation for category buttons
            document.addEventListener('click', function(e) {
                // Check if clicked element is a category button or inside one
                const categoryBtn = e.target.closest('.category-btn');
                if (categoryBtn && !categoryBtn.disabled) {
                    e.preventDefault();
                    e.stopPropagation();
                    const categoryId = parseInt(categoryBtn.getAttribute('data-category-id'));
                    if (categoryId && !isNaN(categoryId)) {
                        selectCategory(categoryId);
                    }
                }
                
                // Check if clicked element is the generate ticket button
                const generateBtn = e.target.closest('#generateTicketBtn');
                if (generateBtn && selectedCategoryId) {
                    // Don't proceed if button is disabled (shouldn't happen, but safety check)
                    if (generateBtn.disabled) {
                        return;
                    }
                    
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Show printing overlay immediately
                    if (window.showPrintingOverlay) {
                        window.showPrintingOverlay();
                    }
                    
                    // Disable button and show loading
                    generateBtn.disabled = true;
                    const generateBtnText = document.getElementById('generateBtnText');
                    const generateBtnLoading = document.getElementById('generateBtnLoading');
                    if (generateBtnText) generateBtnText.style.display = 'none';
                    if (generateBtnLoading) generateBtnLoading.style.display = 'inline';
                    
                    // Find Livewire component and call method
                    const componentElement = generateBtn.closest('[wire\\:id]');
                    if (componentElement && window.Livewire) {
                        const componentId = componentElement.getAttribute('wire:id');
                        const livewireComponent = window.Livewire.find(componentId);
                        
                        if (livewireComponent) {
                            livewireComponent.call('generateTicket', selectedCategoryId)
                                .then(() => {
                                    // Hide overlay immediately when ticket is generated
                                    if (window.hidePrintingOverlay) {
                                        window.hidePrintingOverlay();
                                    }
                                    // Also check for modal appearance as backup
                                    setTimeout(() => {
                                        const modal = document.querySelector('#ticketModal');
                                        if (modal && window.hidePrintingOverlay) {
                                            window.hidePrintingOverlay();
                                        }
                                    }, 200);
                                })
                                .catch(() => {
                                    // Hide overlay and re-enable button on error
                                    if (window.hidePrintingOverlay) {
                                        window.hidePrintingOverlay();
                                    }
                                    resetGenerateButton();
                                });
                        } else {
                            // Fallback: hide overlay and re-enable button
                            if (window.hidePrintingOverlay) {
                                window.hidePrintingOverlay();
                            }
                            resetGenerateButton();
                        }
                    } else {
                        // Fallback: hide overlay and re-enable button
                        if (window.hidePrintingOverlay) {
                            window.hidePrintingOverlay();
                        }
                        resetGenerateButton();
                    }
                }
            }, true); // Use capture phase to catch events earlier

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                // Ensure overlay is hidden
                const overlay = document.getElementById('printingOverlay');
                if (overlay) {
                    overlay.style.display = 'none';
                    overlay.style.pointerEvents = 'none';
                    overlay.classList.remove('show');
                }
                updateGenerateButton();
            });

            // Re-initialize after Livewire updates
            document.addEventListener('livewire:init', () => {
                // Simplified overlay management
                window.hidePrintingOverlay = function() {
                    const overlay = document.getElementById('printingOverlay');
                    if (overlay) {
                        overlay.classList.remove('show');
                        overlay.style.display = 'none';
                        overlay.style.opacity = '0';
                        overlay.style.pointerEvents = 'none';
                        overlay.style.setProperty('display', 'none', 'important');
                    }
                };

                window.showPrintingOverlay = function() {
                    const overlay = document.getElementById('printingOverlay');
                    if (overlay) {
                        overlay.classList.add('show');
                        overlay.style.display = 'flex';
                        overlay.style.opacity = '1';
                        overlay.style.zIndex = '9999';
                        overlay.style.pointerEvents = 'auto';
                        overlay.style.setProperty('display', 'flex', 'important');
                    }
                };

                // Hide overlay on init
                window.hidePrintingOverlay();
                
                Livewire.on('hide-printing-overlay', window.hidePrintingOverlay);
                
                // Handle ticket generation - hide overlay when modal appears
                Livewire.hook('message.processed', (message) => {
                    const isGenerateTicket = message.updateQueue?.some(update => 
                        update.type === 'callMethod' && update.payload?.method === 'generateTicket'
                    );
                    
                    if (isGenerateTicket) {
                        // Hide overlay immediately
                        if (window.hidePrintingOverlay) {
                            window.hidePrintingOverlay();
                        }
                        
                        // Also check after a short delay to ensure modal is rendered
                        setTimeout(() => {
                            const modal = document.querySelector('#ticketModal');
                            if (modal) {
                                // Modal is visible, definitely hide overlay
                                if (window.hidePrintingOverlay) {
                                    window.hidePrintingOverlay();
                                }
                            }
                            // Reset selection and button state after ticket generation
                            selectedCategoryId = null;
                            updateCategoryButtons();
                            resetGenerateButton();
                        }, 150);
                    }
                });
                
                // Also watch for DOM changes to detect modal appearance
                Livewire.hook('morph.updated', () => {
                    const modal = document.querySelector('#ticketModal');
                    if (modal && modal.style.display !== 'none') {
                        // Modal is visible, hide overlay
                        if (window.hidePrintingOverlay) {
                            window.hidePrintingOverlay();
                        }
                    }
                });
                
                // Also listen for DOM updates
                Livewire.hook('morph.updated', () => {
                    // Check if modal is visible - if so, hide overlay
                    const modal = document.querySelector('#ticketModal');
                    if (modal && modal.style.display !== 'none') {
                        if (window.hidePrintingOverlay) {
                            window.hidePrintingOverlay();
                        }
                    }
                    
                    // Check if modal was closed (modal element no longer exists)
                    const modalVisible = document.querySelector('.modal.fade.show');
                    if (!modalVisible && selectedCategoryId === null) {
                        resetGenerateButton();
                    }
                    
                    // Update button state after Livewire updates
                    updateGenerateButton();
                });

                // Update button state initially
                updateGenerateButton();
                
                // Watch for modal appearance using MutationObserver as backup
                const observer = new MutationObserver(function(mutations) {
                    const modal = document.querySelector('#ticketModal');
                    if (modal && modal.style.display !== 'none' && modal.offsetParent !== null) {
                        // Modal is visible, hide overlay
                        if (window.hidePrintingOverlay) {
                            window.hidePrintingOverlay();
                        }
                    }
                });
                
                // Start observing after a short delay
                setTimeout(() => {
                    const targetNode = document.body;
                    if (targetNode) {
                        observer.observe(targetNode, {
                            childList: true,
                            subtree: true,
                            attributes: true,
                            attributeFilter: ['style', 'class']
                        });
                    }
                }, 500);
            });


            function updateCategoryButtons() {
                const buttons = document.querySelectorAll('.category-btn');
                buttons.forEach(btn => {
                    const btnCategoryId = parseInt(btn.getAttribute('data-category-id'));
                    if (btnCategoryId === selectedCategoryId) {
                        btn.classList.add('active');
                        btn.classList.remove('btn-outline-primary');
                        btn.classList.add('btn-primary');
                    } else {
                        btn.classList.remove('active');
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-outline-primary');
                    }
                });
            }

            function updateGenerateButton() {
                const generateBtn = document.getElementById('generateTicketBtn');
                const generateBtnText = document.getElementById('generateBtnText');
                const generateBtnLoading = document.getElementById('generateBtnLoading');
                
                if (generateBtn) {
                    // Reset loading state
                    if (generateBtnText) generateBtnText.style.display = 'inline';
                    if (generateBtnLoading) generateBtnLoading.style.display = 'none';
                    
                    // Enable/disable based on selection
                    if (selectedCategoryId) {
                        generateBtn.disabled = false;
                        generateBtn.removeAttribute('disabled');
                        generateBtn.style.cursor = 'pointer';
                    } else {
                        generateBtn.disabled = true;
                        generateBtn.setAttribute('disabled', 'disabled');
                        generateBtn.style.cursor = 'not-allowed';
                    }
                }
            }

            function resetGenerateButton() {
                const generateBtn = document.getElementById('generateTicketBtn');
                const generateBtnText = document.getElementById('generateBtnText');
                const generateBtnLoading = document.getElementById('generateBtnLoading');
                
                if (generateBtn) {
                    generateBtn.disabled = false;
                    generateBtn.removeAttribute('disabled');
                    if (generateBtnText) generateBtnText.style.display = 'inline';
                    if (generateBtnLoading) generateBtnLoading.style.display = 'none';
                }
            }

            // Make function globally accessible for onclick handlers
            window.resetGenerateButtonState = resetGenerateButton;

            // Close modal instantly with JavaScript, then clean up Livewire state
            window.closeModalInstantly = function() {
                const modal = document.getElementById('ticketModal');
                if (modal) {
                    // Hide modal instantly with CSS (no delay)
                    modal.style.display = 'none';
                    modal.style.opacity = '0';
                    modal.style.transition = 'opacity 0.1s';
                    
                    // Reset button state immediately
                    resetGenerateButton();
                    selectedCategoryId = null;
                    updateCategoryButtons();
                    
                    // Clean up Livewire state in background (non-blocking)
                    setTimeout(() => {
                        const componentElement = document.querySelector('[wire\\:id]');
                        if (componentElement && window.Livewire) {
                            const componentId = componentElement.getAttribute('wire:id');
                            const livewireComponent = window.Livewire.find(componentId);
                            if (livewireComponent) {
                                livewireComponent.call('closeTicketModal').catch(() => {
                                    // Silently fail if component not found
                                });
                            }
                        }
                    }, 50);
                }
            };

        </script>
    @endpush
</div>
