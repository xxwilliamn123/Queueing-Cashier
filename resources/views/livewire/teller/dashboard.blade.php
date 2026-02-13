<div>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Teller</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <!-- Welcome Section -->
    <div class="card rounded-4 border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 rounded p-3">
                    <i class="material-icons-outlined text-primary fs-1">person</i>
                </div>
                <div>
                    <h2 class="mb-0 fw-bold">Welcome, {{ Auth::user()->name }}!</h2>
                    <p class="text-secondary mb-0">{{ Auth::user()->counter_name ?? 'Teller' }} - {{ Auth::user()->category->name ?? 'No Category' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 rounded p-3">
                            <i class="material-icons-outlined text-primary fs-2">receipt</i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-secondary">Total Today</h6>
                            <h4 class="mb-0 fw-bold">{{ $stats['total_today'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 rounded p-3">
                            <i class="material-icons-outlined text-warning fs-2">hourglass_empty</i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-secondary">Waiting</h6>
                            <h4 class="mb-0 fw-bold">{{ $stats['waiting'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-info bg-opacity-10 rounded p-3">
                            <i class="material-icons-outlined text-info fs-2">volume_up</i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-secondary">Serving</h6>
                            <h4 class="mb-0 fw-bold">{{ $stats['serving'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <i class="material-icons-outlined text-success fs-2">check_circle</i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-secondary">Completed</h6>
                            <h4 class="mb-0 fw-bold">{{ $stats['done'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Current Ticket (if serving) -->
        @if(isset($currentTicket) && $currentTicket)
        <div class="col-lg-6">
            <div class="card rounded-4 border-success border-2">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="material-icons-outlined">volume_up</i>
                        <span>Currently Serving</span>
                    </h5>
                </div>
                <div class="card-body text-center py-4">
                    <div class="ticket-display mb-3">
                        <span class="ticket-code-medium">{{ $currentTicket->code }}</span>
                    </div>
                    <span class="badge bg-primary fs-6">{{ $currentTicket->category->name ?? 'N/A' }}</span>
                    <p class="text-secondary mt-2 mb-0 small">Started at: {{ $currentTicket->updated_at->format('h:i A') }}</p>
                    <div class="mt-3">
                        <a href="{{ route('teller.my-queue') }}" class="btn btn-primary btn-sm">
                            <i class="material-icons-outlined me-1">queue</i>Manage Queue
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="col-lg-{{ isset($currentTicket) && $currentTicket ? '6' : '12' }}">
            <div class="card rounded-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="material-icons-outlined">dashboard</i>
                        <span>Quick Actions</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('teller.my-queue') }}" class="card border-0 shadow-sm text-decoration-none h-100">
                                <div class="card-body text-center py-4">
                                    <i class="material-icons-outlined text-primary" style="font-size: 48px;">queue</i>
                                    <h6 class="mt-3 mb-0 fw-bold">My Queue</h6>
                                    <p class="text-secondary mb-0 small">Manage tickets</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center py-4">
                                    <i class="material-icons-outlined text-info" style="font-size: 48px;">assessment</i>
                                    <h6 class="mt-3 mb-0 fw-bold">Statistics</h6>
                                    <p class="text-secondary mb-0 small">View today's stats</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tickets -->
    @if(isset($recentTickets) && $recentTickets->count() > 0)
    <div class="card rounded-4 mt-4">
        <div class="card-header bg-secondary text-white py-3">
            <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="material-icons-outlined">history</i>
                <span>Recent Tickets Served</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Ticket Number</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Completed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTickets as $ticket)
                            <tr>
                                <td>
                                    <span class="fw-bold text-primary">{{ $ticket->code }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $ticket->category->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @if($ticket->status == 'done')
                                        <span class="badge bg-success">Done</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Skipped</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-secondary">{{ $ticket->updated_at->format('h:i A') }}</small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
    <style>
        .ticket-code-medium {
            font-size: 3rem;
            font-weight: 900;
            color: #0d6efd;
            letter-spacing: 6px;
            display: block;
            line-height: 1.2;
        }

        @media (max-width: 768px) {
            .ticket-code-medium {
                font-size: 2rem;
                letter-spacing: 3px;
            }
        }
    </style>
@endpush
