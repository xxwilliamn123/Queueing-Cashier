<div>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
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
                    <i class="material-icons-outlined text-primary fs-1">admin_panel_settings</i>
                </div>
                <div>
                    <h2 class="mb-0 fw-bold">Welcome, {{ Auth::user()->name }}!</h2>
                    <p class="text-secondary mb-0">Admin Dashboard - Queue System Management</p>
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

    <div class="row g-3 mb-4">
        <!-- System Overview -->
        <div class="col-md-4 col-sm-6">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-secondary bg-opacity-10 rounded p-3">
                            <i class="material-icons-outlined text-secondary fs-2">category</i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-secondary">Categories</h6>
                            <h4 class="mb-0 fw-bold">{{ $stats['total_categories'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-info bg-opacity-10 rounded p-3">
                            <i class="material-icons-outlined text-info fs-2">people</i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-secondary">Total Tellers</h6>
                            <h4 class="mb-0 fw-bold">{{ $stats['total_tellers'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <i class="material-icons-outlined text-success fs-2">person</i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-secondary">Active Tellers</h6>
                            <h4 class="mb-0 fw-bold">{{ $stats['active_tellers'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Recent Tickets -->
        <div class="col-lg-8">
            <div class="card rounded-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="material-icons-outlined">receipt</i>
                        <span>Recent Tickets (Today)</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($todayTickets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ticket</th>
                                        <th>Category</th>
                                        <th>Teller</th>
                                        <th>Status</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayTickets as $ticket)
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-primary">{{ $ticket->code }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $ticket->category->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                @if($ticket->teller)
                                                    <span class="text-secondary">{{ $ticket->teller->name }}</span>
                                                @else
                                                    <span class="text-secondary">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($ticket->status == 'waiting')
                                                    <span class="badge bg-warning text-dark">Waiting</span>
                                                @elseif($ticket->status == 'serving')
                                                    <span class="badge bg-info">Serving</span>
                                                @elseif($ticket->status == 'done')
                                                    <span class="badge bg-success">Done</span>
                                                @elseif($ticket->status == 'skipped')
                                                    <span class="badge bg-secondary">Skipped</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-secondary">{{ $ticket->created_at->format('h:i A') }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="material-icons-outlined text-secondary" style="font-size: 64px;">inbox</i>
                            <p class="text-secondary mt-3 mb-0">No tickets generated today</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Categories -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card rounded-4 mb-3">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="material-icons-outlined">dashboard</i>
                        <span>Quick Actions</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.categories') }}" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2">
                            <i class="material-icons-outlined">category</i>
                            <span>Manage Categories</span>
                        </a>
                        <a href="{{ route('admin.tellers') }}" class="btn btn-outline-info d-flex align-items-center justify-content-center gap-2">
                            <i class="material-icons-outlined">people</i>
                            <span>Manage Tellers</span>
                        </a>
                        <a href="{{ route('admin.settings') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2">
                            <i class="material-icons-outlined">settings</i>
                            <span>Settings</span>
                        </a>
                        <a href="{{ route('display') }}" class="btn btn-outline-warning d-flex align-items-center justify-content-center gap-2" target="_blank">
                            <i class="material-icons-outlined">tv</i>
                            <span>Display Monitor</span>
                        </a>
                        <a href="{{ route('kiosk') }}" class="btn btn-outline-success d-flex align-items-center justify-content-center gap-2" target="_blank">
                            <i class="material-icons-outlined">add_circle</i>
                            <span>Kiosk Mode</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Categories Overview -->
            <div class="card rounded-4">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="material-icons-outlined">category</i>
                        <span>Categories</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($categories->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($categories as $category)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <span class="fw-bold">{{ $category->name }}</span>
                                        <br>
                                        <small class="text-secondary">Prefix: {{ $category->prefix }}</small>
                                    </div>
                                    <span class="badge bg-primary">{{ $category->tickets()->whereDate('ticket_date', now()->format('Y-m-d'))->count() }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="material-icons-outlined text-secondary" style="font-size: 48px;">category</i>
                            <p class="text-secondary mt-2 mb-0 small">No categories yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
