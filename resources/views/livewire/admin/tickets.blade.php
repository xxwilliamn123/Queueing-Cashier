<div>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Tickets</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">All Tickets</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card rounded-4">
        <div class="card-body">
            <!-- Filters -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="position-relative">
                        <input type="text" class="form-control px-5" wire:model.live.debounce.300ms="search" placeholder="Search tickets...">
                        <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="statusFilter">
                        <option value="">All Status</option>
                        <option value="waiting">Waiting</option>
                        <option value="serving">Serving</option>
                        <option value="done">Done</option>
                        <option value="skipped">Skipped</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="categoryFilter">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" wire:model.live="dateFilter" placeholder="All Dates">
                    @if($dateFilter)
                        <small class="text-secondary">Filtered by date</small>
                    @endif
                </div>
                <div class="col-md-3 text-end">
                    <button class="btn btn-outline-secondary" wire:click="$set('search', ''); $set('statusFilter', ''); $set('categoryFilter', ''); $set('dateFilter', '')">
                        <i class="material-icons-outlined">refresh</i> Reset
                    </button>
                </div>
            </div>

            <!-- Tickets Table -->
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Ticket Code</th>
                            <th>Category</th>
                            <th>Teller</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td>
                                    <span class="fw-bold text-primary fs-5">{{ $ticket->code }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $ticket->category->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @if($ticket->teller)
                                        <div class="d-flex align-items-center gap-2">
                                            <span>{{ $ticket->teller->name }}</span>
                                            @if($ticket->teller->counter_name)
                                                <small class="text-secondary">({{ $ticket->teller->counter_name }})</small>
                                            @endif
                                        </div>
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
                                    <small class="text-secondary">{{ $ticket->ticket_date->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <small class="text-secondary">{{ $ticket->created_at->format('h:i A') }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="material-icons-outlined text-secondary" style="font-size: 64px;">inbox</i>
                                    <p class="text-secondary mt-3 mb-0">No tickets found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</div>
