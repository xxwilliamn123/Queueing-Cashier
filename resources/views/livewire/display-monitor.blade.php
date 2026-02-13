<div class="display-monitor-container">
    <!-- Top Header -->
    <div class="top-header bg-black">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between py-3">
                <div class="logo-section">
                    <h1 class="logo-text mb-0 fw-bold text-success">{{ config('app.name') }}</h1>
                </div>
                <div class="date-time-header text-end" wire:ignore>
                    <div>
                        <span class="date-text-header fw-bold text-white"></span>
                        <span class="time-text-header fw-bold text-white ms-3"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="main-content-area">
        <div class="container-fluid p-0 h-100">
            <div class="row g-0 h-100 m-0">
                <!-- Left Side - Queue Display -->
                <div class="col-lg-5 col-md-12">
                    <div class="queue-display bg-success h-100">
                        <!-- Queue Headers -->
                        <div class="queue-headers bg-success text-white py-4">
                            <div class="row g-0 h-100">
                                <div class="col-6 border-end border-white border-2 d-flex align-items-center justify-content-center">
                                    <h2 class="mb-0 text-center fw-bold header-text">WINDOW</h2>
                                </div>
                                <div class="col-6 d-flex align-items-center justify-content-center">
                                    <h2 class="mb-0 text-center fw-bold header-text">NOW SERVING</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Queue Rows -->
                        <div class="queue-rows">
                            @if(count($activeTellers) > 0)
                                @php
                                    $tellerList = array_slice($activeTellers, 0, 10);
                                @endphp
                                @foreach($tellerList as $index => $tellerData)
                                    @php
                                        $teller = $tellerData['teller'];
                                        $ticket = $tellerData['ticket'] ?? null;
                                        $counterName = $teller && $teller->counter_name ? $teller->counter_name : '-';
                                    @endphp
                                    <div class="queue-row bg-success text-white border-bottom border-white border-1" data-teller-id="{{ $teller->id }}" data-counter-name="{{ $counterName }}">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-6 text-center border-end border-white border-2">
                                                <span class="counter-number d-block">{{ $counterName }}</span>
                                            </div>
                                            <div class="col-6 text-center">
                                                @if($ticket)
                                                    <span class="ticket-number d-block" data-ticket-code="{{ $ticket->code }}" wire:ignore>{{ $ticket->code }}</span>
                                                @else
                                                    <span class="ticket-number d-block" wire:ignore>-</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @for($i = count($tellerList); $i < 10; $i++)
                                    <div class="queue-row bg-success text-white border-bottom border-white border-1">
                                        <div class="row g-0 align-items-center h-100">
                                            <div class="col-6 text-center border-end border-white border-2">
                                                <span class="counter-number d-block">-</span>
                                            </div>
                                            <div class="col-6 text-center">
                                                <span class="ticket-number d-block">-</span>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            @else
                                @for($i = 0; $i < 10; $i++)
                                    <div class="queue-row bg-success text-white border-bottom border-white border-1">
                                        <div class="row g-0 align-items-center h-100">
                                            <div class="col-6 text-center border-end border-white border-2">
                                                <span class="counter-number d-block">-</span>
                                            </div>
                                            <div class="col-6 text-center">
                                                <span class="ticket-number d-block">-</span>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Side - Video Player -->
                <div class="col-lg-7 col-md-12">
                    <div class="video-section bg-dark h-100 d-flex align-items-center justify-content-center position-relative">
                        @if($videoUrl)
                            <div class="video-container position-absolute w-100 h-100">
                                @if(strpos($videoUrl, 'youtube.com/embed/') !== false || strpos($videoUrl, 'youtube-nocookie.com/embed/') !== false)
                                    <!-- YouTube Embed -->
                                    <iframe 
                                        src="{{ $videoUrl }}" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen
                                        class="w-100 h-100"
                                        style="min-height: 100%;"
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade">
                                    </iframe>
                                @else
                                    <!-- Uploaded Video File -->
                                    <video 
                                        src="{{ $videoUrl }}" 
                                        autoplay 
                                        loop 
                                        muted 
                                        playsinline
                                        class="w-100 h-100"
                                        style="object-fit: cover; min-height: 100%;">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                            </div>
                        @else
                            <div class="video-placeholder text-center text-white">
                                <i class="material-icons-outlined" style="font-size: 120px; opacity: 0.3;">play_circle_outline</i>
                                <p class="mt-4 mb-0 fs-4" style="opacity: 0.5;">Video Player</p>
                                <p class="mt-2 mb-0 small" style="opacity: 0.5;">Configure video in Settings</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Footer Bar -->
    <div class="bottom-footer bg-dark text-white py-3">
        <div class="container-fluid">
            <div class="row g-0 align-items-center">
                <div class="col-12">
                    <div class="footer-marquee">
                        <marquee width="100%" direction="left" height="40px" behavior="scroll" scrollamount="3">
                            <span class="marquee-text">{{ $marqueeText }}</span>
                        </marquee>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            overflow: hidden;
            margin: 0;
            padding: 0;
        }

        .display-monitor-container {
            height: 100vh;
            width: 100vw;
            background: #000;
            font-family: 'Arial', 'Helvetica', sans-serif;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
        }

        /* Top Header */
        .top-header {
            background: #000 !important;
            border-bottom: 3px solid #28a745;
            z-index: 100;
            flex-shrink: 0;
            padding: 1.5rem 0 !important;
        }

        .logo-text {
            font-size: clamp(1.8rem, 3vw, 3.5rem) !important;
            font-weight: 900 !important;
            color: #28a745 !important;
            letter-spacing: 2px;
            margin: 0 !important;
        }

        .date-time-header {
            min-width: 200px;
        }

        .date-text-header {
            font-size: clamp(1.5rem, 2.5vw, 2.5rem) !important;
            color: #ffffff !important;
            letter-spacing: 2px;
        }

        .time-text-header {
            font-size: clamp(2rem, 3vw, 3rem) !important;
            color: #ffffff !important;
            letter-spacing: 3px;
        }

        /* Main Content Area */
        .main-content-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            min-height: 0;
        }

        .main-content-area .container-fluid {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .main-content-area .row {
            flex: 1;
            min-height: 0;
        }

        .main-content-area .row > [class*="col-"] {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Queue Display (Left Side) */
        .queue-display {
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .queue-headers {
            background: #28a745 !important;
            border-bottom: 4px solid #1e7e34;
            flex-shrink: 0;
            padding: 2rem 0 !important;
            display: flex;
            align-items: center;
        }
        
        .queue-headers .row {
            height: 100%;
        }

        .header-text {
            font-size: clamp(1.5rem, 3vw, 3rem) !important;
            letter-spacing: 2px;
            margin: 0 !important;
        }

        .queue-rows {
            flex: 1;
            overflow-y: auto;
            overflow-x: visible;
            min-height: 0;
            display: flex;
            flex-direction: column;
        }

        .queue-row {
            flex: 1;
            min-height: 80px;
            display: flex;
            align-items: center;
            border-bottom: 2px solid rgba(255,255,255,0.3);
            padding: 1.5rem 0;
            overflow: visible;
        }

        .counter-number,
        .ticket-number {
            font-weight: 900 !important;
            letter-spacing: 2px;
            line-height: 1.3;
            font-size: clamp(1.8rem, 3.5vw, 3.5rem) !important;
            color: #ffffff !important;
            margin: 0 !important;
            display: block !important;
            transition: color 0.3s ease;
            white-space: nowrap;
            overflow: visible;
        }

        /* Video Section (Right Side) */
        .video-section {
            height: 100%;
            background: #000;
            overflow: hidden;
        }

        .video-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .video-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .video-placeholder {
            opacity: 0.5;
            z-index: 1;
        }

        /* Bottom Footer */
        .bottom-footer {
            background: #000 !important;
            border-top: 2px solid #28a745;
            flex-shrink: 0;
            z-index: 1000;
        }

        .footer-marquee {
            width: 100%;
            height: 40px;
            display: flex;
            align-items: center;
        }

        .footer-marquee marquee {
            width: 100%;
            height: 100%;
            line-height: 40px;
        }

        .footer-marquee .marquee-text {
            font-size: clamp(1.5rem, 2vw, 2.5rem) !important;
            font-weight: 700 !important;
            color: #ffffff !important;
        }

        /* Scrollbar Styling */
        .queue-rows::-webkit-scrollbar {
            width: 12px;
        }

        .queue-rows::-webkit-scrollbar-track {
            background: #1e7e34;
        }

        .queue-rows::-webkit-scrollbar-thumb {
            background: #ffffff;
            border-radius: 6px;
        }

        .queue-rows::-webkit-scrollbar-thumb:hover {
            background: #f0f0f0;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // Auto Fullscreen Function
        let fullscreenEnabled = false;

        function requestFullscreen() {
            const element = document.documentElement; // Full page
            
            if (element.requestFullscreen) {
                return element.requestFullscreen().then(() => {
                    fullscreenEnabled = true;
                    return true;
                }).catch(err => {
                    fullscreenEnabled = false;
                    return false;
                });
            } else if (element.webkitRequestFullscreen) { // Safari
                element.webkitRequestFullscreen();
                fullscreenEnabled = true;
                return Promise.resolve(true);
            } else if (element.webkitRequestFullScreen) { // Older Safari
                element.webkitRequestFullScreen();
                fullscreenEnabled = true;
                return Promise.resolve(true);
            } else if (element.mozRequestFullScreen) { // Firefox
                element.mozRequestFullScreen();
                fullscreenEnabled = true;
                return Promise.resolve(true);
            } else if (element.msRequestFullscreen) { // IE/Edge
                element.msRequestFullscreen();
                fullscreenEnabled = true;
                return Promise.resolve(true);
            } else {
                return Promise.reject('Not supported');
            }
        }

        // Check if already in fullscreen
        function isFullscreen() {
            return !!(document.fullscreenElement || 
                     document.webkitFullscreenElement || 
                     document.mozFullScreenElement || 
                     document.msFullscreenElement);
        }

        // Enable fullscreen on user interaction (REQUIRED by browsers)
        let interactionHandlers = [];
        
        function enableFullscreenOnInteraction() {
            // Remove existing handlers first
            interactionHandlers.forEach(({ event, handler }) => {
                document.removeEventListener(event, handler);
            });
            interactionHandlers = [];

            const handler = function(e) {
                // Only trigger on first interaction if not already in fullscreen
                if (!isFullscreen()) {
                    requestFullscreen().then(success => {
                        if (success) {
                            onFullscreenEntered();
                            // Remove listeners after successful fullscreen
                            interactionHandlers.forEach(({ event, handler }) => {
                                document.removeEventListener(event, handler);
                            });
                            interactionHandlers = [];
                        }
                    });
                }
            };

            // Add listeners for user interaction (required by Chrome)
            const events = ['click', 'touchstart', 'keydown'];
            events.forEach(event => {
                document.addEventListener(event, handler, { once: false });
                interactionHandlers.push({ event, handler });
            });
        }

        // Set up interaction-based fullscreen (REQUIRED - browsers block auto-fullscreen)
        enableFullscreenOnInteraction();

        // Auto re-enter fullscreen timer
        let reenterFullscreenTimeout = null;
        let fullscreenCheckInterval = null;

        function scheduleReenterFullscreen() {
            // Clear any existing timeout
            if (reenterFullscreenTimeout) {
                clearTimeout(reenterFullscreenTimeout);
            }
            
            // Schedule re-enter after 5 seconds
            reenterFullscreenTimeout = setTimeout(() => {
                if (!isFullscreen()) {
                    // Try to re-enter fullscreen
                    // Note: This will only work if there's been a user interaction
                    // If not, it will wait for the next click
                    requestFullscreen().catch(() => {
                        // Re-enable interaction listeners if auto re-enter fails
                        enableFullscreenOnInteraction();
                    });
                }
            }, 5000); // 5 seconds
        }

        // Continuous fullscreen check - monitors and re-enters if not in fullscreen
        function startFullscreenMonitor() {
            // Clear any existing interval
            if (fullscreenCheckInterval) {
                clearInterval(fullscreenCheckInterval);
            }

            // Check every 2 seconds if we're in fullscreen
            fullscreenCheckInterval = setInterval(() => {
                if (!isFullscreen()) {
                    // Try to re-enter fullscreen
                    requestFullscreen().catch(() => {
                        // If it fails, re-enable interaction listeners
                        enableFullscreenOnInteraction();
                    });
                }
            }, 2000); // Check every 2 seconds
        }

        // Start the continuous monitor after initial fullscreen is achieved
        function onFullscreenEntered() {
            fullscreenEnabled = true;
            // Start monitoring
            startFullscreenMonitor();
        }

        // Listen for fullscreen changes
        document.addEventListener('fullscreenchange', () => {
            if (isFullscreen()) {
                onFullscreenEntered();
                // Clear any pending re-enter timeout
                if (reenterFullscreenTimeout) {
                    clearTimeout(reenterFullscreenTimeout);
                    reenterFullscreenTimeout = null;
                }
            } else {
                fullscreenEnabled = false;
                // Schedule auto re-enter after 5 seconds
                scheduleReenterFullscreen();
                // Also start continuous monitoring
                startFullscreenMonitor();
            }
        });

        // Also listen for webkit fullscreen changes (Safari)
        document.addEventListener('webkitfullscreenchange', () => {
            if (isFullscreen()) {
                onFullscreenEntered();
                if (reenterFullscreenTimeout) {
                    clearTimeout(reenterFullscreenTimeout);
                    reenterFullscreenTimeout = null;
                }
            } else {
                fullscreenEnabled = false;
                scheduleReenterFullscreen();
                startFullscreenMonitor();
            }
        });

        // Listen for moz fullscreen changes (Firefox)
        document.addEventListener('mozfullscreenchange', () => {
            if (isFullscreen()) {
                onFullscreenEntered();
                if (reenterFullscreenTimeout) {
                    clearTimeout(reenterFullscreenTimeout);
                    reenterFullscreenTimeout = null;
                }
            } else {
                fullscreenEnabled = false;
                scheduleReenterFullscreen();
                startFullscreenMonitor();
            }
        });

        // Date and Time Updates
        if (!window.dateTimeInitialized) {
            window.dateTimeInitialized = true;
            
            function formatDate(date) {
                const day = String(date.getDate()).padStart(2, '0');
                const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                              'July', 'August', 'September', 'October', 'November', 'December'];
                const month = months[date.getMonth()];
                const year = date.getFullYear();
                return day + ' ' + month + ' ' + year;
            }

            function formatTime(date) {
                let hours = date.getHours();
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12;
                const hoursStr = String(hours).padStart(2, '0');
                return hoursStr + ':' + minutes + ' ' + ampm;
            }

            function updateDateTime() {
                const now = new Date();
                const dateElement = document.querySelector('.date-text-header');
                const timeElement = document.querySelector('.time-text-header');
                
                if (dateElement && timeElement) {
                    dateElement.textContent = formatDate(now);
                    timeElement.textContent = formatTime(now);
                }
            }

            updateDateTime();
            
            if (!window.dateTimeInterval) {
                window.dateTimeInterval = setInterval(updateDateTime, 1000);
            }

            document.addEventListener('livewire:update', () => {
                setTimeout(updateDateTime, 50);
            });
        }

        // WebSocket/Echo Listeners
        let echoListenersInitialized = false;
        let echoChannel = null;

        function initializeEchoListeners() {
            if (echoListenersInitialized) {
                return;
            }

            if (window.Echo && window.Livewire) {
                echoListenersInitialized = true;

                if (!echoChannel) {
                    echoChannel = window.Echo.channel('tickets');
                }
                
                // Listen for ticket created
                echoChannel.listen('.ticket.created', (e) => {
                    window.Livewire.dispatch('refresh-display');
                })
                // Listen for ticket called - update immediately
                .listen('.ticket.called', (e) => {
                    if (e.ticket && e.ticket.code) {
                        // Update ticket display IMMEDIATELY (before Livewire refresh)
                        updateTicketDisplayImmediately(e.ticket);
                        
                        // Trigger Livewire refresh in background
                        setTimeout(() => {
                            window.Livewire.dispatch('refresh-display');
                        }, 100);
                    }
                    
                    if (e.ticket) {
                        if ('speechSynthesis' in window) {
                            speechSynthesis.cancel();
                        }
                        setTimeout(() => {
                            if (window.announceTicketCall) {
                                window.announceTicketCall(e.ticket);
                            }
                        }, 100);
                    }
                    
                    if (e.ticket && e.ticket.code) {
                        // Blink immediately (no delay)
                        if (window.blinkTicketNumber) {
                            window.blinkTicketNumber(e.ticket.code, 8);
                        }
                    }
                })
                // Listen for ticket recalled - update immediately
                .listen('.ticket.recalled', (e) => {
                    if (e.ticket && e.ticket.code) {
                        // Update ticket display IMMEDIATELY (before Livewire refresh)
                        updateTicketDisplayImmediately(e.ticket);
                        
                        // Trigger Livewire refresh in background
                        setTimeout(() => {
                            window.Livewire.dispatch('refresh-display');
                        }, 100);
                    }
                    
                    if (e.ticket) {
                        if ('speechSynthesis' in window) {
                            // Cancel any ongoing speech
                            speechSynthesis.cancel();
                        }
                        setTimeout(() => {
                            if (window.announceTicketRecall) {
                                window.announceTicketRecall(e.ticket);
                            }
                        }, 100);
                    }
                    
                    if (e.ticket && e.ticket.code) {
                        // Blink immediately (no delay)
                        if (window.blinkTicketNumber) {
                            window.blinkTicketNumber(e.ticket.code, 8);
                        }
                    }
                })
                // Listen for ticket completed - clear ticket display
                .listen('.ticket.completed', (e) => {
                    if (e.ticket && e.ticket.teller) {
                        // Clear ticket number for this teller immediately
                        const tellerId = e.ticket.teller.id;
                        const tellerRow = document.querySelector(`[data-teller-id="${tellerId}"]`);
                        if (tellerRow) {
                            const ticketElement = tellerRow.querySelector('.ticket-number');
                            if (ticketElement) {
                                ticketElement.textContent = '-';
                                ticketElement.removeAttribute('data-ticket-code');
                            }
                        }
                        
                        // Remove from immediate updates tracking
                        lastImmediateUpdates.delete(tellerId);
                    }
                    
                    // Trigger Livewire refresh in background
                    setTimeout(() => {
                        window.Livewire.dispatch('refresh-display');
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

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeEchoListeners);
        } else {
            initializeEchoListeners();
        }

        document.addEventListener('livewire:init', initializeEchoListeners);
        
        // Store last immediate updates to preserve them through Livewire morphing
        let lastImmediateUpdates = new Map();
        
        // After Livewire morphs, preserve immediate ticket updates
        document.addEventListener('livewire:morph', () => {
            // Restore any immediate updates that were made recently
            lastImmediateUpdates.forEach((updateData, tellerId) => {
                if (Date.now() - updateData.timestamp < 2000) {
                    const tellerRow = document.querySelector(`[data-teller-id="${tellerId}"]`);
                    if (tellerRow) {
                        const ticketElement = tellerRow.querySelector('.ticket-number');
                        if (ticketElement && ticketElement.textContent !== updateData.code) {
                            ticketElement.textContent = updateData.code;
                            ticketElement.setAttribute('data-ticket-code', updateData.code);
                        }
                    }
                }
            });
        });

        // Update ticket display immediately (before Livewire refresh)
        function updateTicketDisplayImmediately(ticketData) {
            if (!ticketData || !ticketData.code || !ticketData.teller) {
                return;
            }
            
            const tellerId = ticketData.teller.id;
            const ticketCode = ticketData.code;
            const counterName = ticketData.teller.counter_name || '-';
            
            // Store this update for preservation through Livewire morphing
            lastImmediateUpdates.set(tellerId, {
                code: ticketCode,
                counterName: counterName,
                timestamp: Date.now()
            });
            
            // Find the teller's row
            let tellerRow = document.querySelector(`[data-teller-id="${tellerId}"]`);
            
            if (!tellerRow) {
                // Row doesn't exist yet, create it immediately
                tellerRow = createTellerRow(tellerId, counterName, ticketCode);
            } else {
                // Row exists, update ticket number immediately
                const ticketElement = tellerRow.querySelector('.ticket-number');
                if (ticketElement) {
                    ticketElement.textContent = ticketCode;
                    ticketElement.setAttribute('data-ticket-code', ticketCode);
                    // Force reflow to ensure update is visible
                    ticketElement.offsetHeight;
                }
            }
        }
        
        // Create a teller row immediately if it doesn't exist
        function createTellerRow(tellerId, counterName, ticketCode) {
            const queueRows = document.querySelector('.queue-rows');
            if (!queueRows) return null;
            
            // Create the row element
            const row = document.createElement('div');
            row.className = 'queue-row bg-success text-white border-bottom border-white border-1';
            row.setAttribute('data-teller-id', tellerId);
            row.setAttribute('data-counter-name', counterName);
            
            row.innerHTML = `
                <div class="row g-0 align-items-center">
                    <div class="col-6 text-center border-end border-white border-2">
                        <span class="counter-number d-block">${counterName}</span>
                    </div>
                    <div class="col-6 text-center">
                        <span class="ticket-number d-block" data-ticket-code="${ticketCode}" wire:ignore>${ticketCode}</span>
                    </div>
                </div>
            `;
            
            // Insert at the beginning of queue rows
            queueRows.insertBefore(row, queueRows.firstChild);
            
            return row;
        }

        // Blinking Animation Function
        window.blinkTicketNumber = function(ticketCode, times) {
            let ticketElement = document.querySelector(`[data-ticket-code="${ticketCode}"]`);
            
            if (!ticketElement) {
                // Try again after a short delay (element might be created by updateTicketDisplayImmediately)
                setTimeout(() => {
                    ticketElement = document.querySelector(`[data-ticket-code="${ticketCode}"]`);
                    if (ticketElement) {
                        startBlinkAnimation(ticketElement, times);
                    }
                }, 50);
                return;
            }

            startBlinkAnimation(ticketElement, times);
        }

        function startBlinkAnimation(element, times) {
            if (!element) {
                return;
            }
            
            const originalColor = element.style.color || '';
            const originalTextShadow = element.style.textShadow || '';
            
            let blinkCount = 0;
            const blinkInterval = 500;
            
            const blink = () => {
                if (blinkCount >= times * 2) {
                    element.style.color = originalColor;
                    element.style.textShadow = originalTextShadow;
                    return;
                }
                
                if (blinkCount % 2 === 0) {
                    element.style.color = '#ff0000';
                    element.style.textShadow = '0 0 20px rgba(255, 0, 0, 0.8), 3px 3px 6px rgba(0,0,0,0.3)';
                } else {
                    element.style.color = '#ffffff';
                    element.style.textShadow = '3px 3px 6px rgba(0,0,0,0.3)';
                }
                
                blinkCount++;
                setTimeout(blink, blinkInterval / 2);
            };
            
            blink();
        }

        window.startBlinkAnimation = startBlinkAnimation;

        // Text-to-Speech Functions
        let isAnnouncing = false;

        window.announceTicketCall = function(ticket) {
            if (isAnnouncing) {
                return;
            }

            if (!('speechSynthesis' in window)) {
                return;
            }

            const voiceType = localStorage.getItem('tts_voice_type') || 'female';
            const enabled = localStorage.getItem('tts_enabled') !== 'false';

            if (!enabled) {
                return;
            }

            isAnnouncing = true;

            let voices = speechSynthesis.getVoices();
            
            if (voices.length === 0) {
                setTimeout(() => {
                    voices = speechSynthesis.getVoices();
                    if (voices.length > 0) {
                        speakAnnouncement(ticket, voices, voiceType, 'called');
                    } else {
                        isAnnouncing = false;
                    }
                }, 100);
                return;
            }

            speakAnnouncement(ticket, voices, voiceType, 'called');
        }

        window.announceTicketRecall = function(ticket) {
            if (isAnnouncing) {
                return;
            }

            if (!('speechSynthesis' in window)) {
                return;
            }

            const voiceType = localStorage.getItem('tts_voice_type') || 'female';
            const enabled = localStorage.getItem('tts_enabled') !== 'false';

            if (!enabled) {
                return;
            }

            isAnnouncing = true;

            let voices = speechSynthesis.getVoices();
            
            if (voices.length === 0) {
                setTimeout(() => {
                    voices = speechSynthesis.getVoices();
                    if (voices.length > 0) {
                        speakAnnouncement(ticket, voices, voiceType, 'recalled');
                    } else {
                        isAnnouncing = false;
                    }
                }, 100);
                return;
            }

            speakAnnouncement(ticket, voices, voiceType, 'recalled');
        }

        function speakAnnouncement(ticket, voices, voiceType, type) {
            let selectedVoice = null;
            if (voiceType === 'male') {
                selectedVoice = voices.find(v => 
                    v.name.toLowerCase().includes('male') ||
                    v.name.toLowerCase().includes('david') ||
                    v.name.toLowerCase().includes('mark') ||
                    (v.name.toLowerCase().includes('zira') === false && v.name.toLowerCase().includes('susan') === false)
                ) || voices.find(v => v.lang.startsWith('en') && !v.name.toLowerCase().includes('zira'));
            } else {
                selectedVoice = voices.find(v => 
                    v.name.toLowerCase().includes('female') ||
                    v.name.toLowerCase().includes('zira') ||
                    v.name.toLowerCase().includes('susan') ||
                    v.name.toLowerCase().includes('hazel')
                ) || voices.find(v => v.lang.startsWith('en') && v.name.toLowerCase().includes('zira'));
            }

            if (!selectedVoice) {
                selectedVoice = voices.find(v => v.lang.startsWith('en')) || voices[0];
            }

            const ticketCode = ticket.code || 'Unknown';
            const counterName = ticket.teller?.counter_name || 'Counter';
            
            // Use same wording for both called and recalled
            const announcement = `Now serving ${ticketCode} to Window ${counterName}`;

            const utterance = new SpeechSynthesisUtterance(announcement);
            utterance.voice = selectedVoice;
            utterance.rate = 0.9;
            utterance.pitch = voiceType === 'male' ? 0.9 : 1.1;
            utterance.volume = 1.0; // Maximum volume - ensure it's always at max
            utterance.lang = 'en-US';

            // Cancel any pending speech to ensure clean start
            speechSynthesis.cancel();
            
            // Small delay to ensure cancellation is processed, then speak at max volume
            setTimeout(() => {
                // Re-ensure volume is at maximum right before speaking
                utterance.volume = 1.0;
                speechSynthesis.speak(utterance);
            }, 50);

            utterance.onend = () => {
                isAnnouncing = false;
            };

            utterance.onerror = (error) => {
                isAnnouncing = false;
            };
        }

        // Load voices when available
        if ('speechSynthesis' in window) {
            if (speechSynthesis.onvoiceschanged !== undefined) {
                speechSynthesis.onvoiceschanged = () => {
                    // Voices loaded
                };
            }
        }
    </script>
@endpush






