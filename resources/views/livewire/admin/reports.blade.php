<div>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Tickets</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Reports</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <!-- Date Range Filter -->
    <div class="card rounded-4 mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="startDate" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="startDate" wire:model.live="startDate">
                </div>
                <div class="col-md-3">
                    <label for="endDate" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="endDate" wire:model.live="endDate">
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-outline-primary" wire:click="$set('startDate', '{{ now()->startOfMonth()->format('Y-m-d') }}'); $set('endDate', '{{ now()->format('Y-m-d') }}')">
                        <i class="material-icons-outlined">calendar_month</i> This Month
                    </button>
                    <button class="btn btn-outline-primary" wire:click="$set('startDate', '{{ now()->startOfWeek()->format('Y-m-d') }}'); $set('endDate', '{{ now()->format('Y-m-d') }}')">
                        <i class="material-icons-outlined">today</i> This Week
                    </button>
                    <button class="btn btn-outline-primary" wire:click="$set('startDate', '{{ now()->format('Y-m-d') }}'); $set('endDate', '{{ now()->format('Y-m-d') }}')">
                        <i class="material-icons-outlined">event</i> Today
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 rounded p-3">
                            <i class="material-icons-outlined text-primary fs-2">receipt</i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-secondary">Total Tickets</h6>
                            <h4 class="mb-0 fw-bold">{{ $stats['total'] ?? 0 }}</h4>
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
        <div class="col-md-3 col-sm-6">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-secondary bg-opacity-10 rounded p-3">
                            <i class="material-icons-outlined text-secondary fs-2">skip_next</i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-secondary">Skipped</h6>
                            <h4 class="mb-0 fw-bold">{{ $stats['skipped'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Category Statistics -->
        <div class="col-lg-6">
            <div class="card rounded-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="material-icons-outlined">category</i>
                        <span>Statistics by Category</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($categoryStats->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Category</th>
                                        <th>Total</th>
                                        <th>Completed</th>
                                        <th>Completion Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categoryStats as $category)
                                        @php
                                            $completionRate = $category->tickets_count > 0 
                                                ? round(($category->done_count / $category->tickets_count) * 100, 1) 
                                                : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">{{ $category->name }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ $category->tickets_count }}</span>
                                            </td>
                                            <td>
                                                <span class="text-success fw-bold">{{ $category->done_count }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress flex-grow-1" style="height: 20px;">
                                                        <div class="progress-bar bg-success" role="progressbar" 
                                                             style="width: {{ $completionRate }}%"
                                                             aria-valuenow="{{ $completionRate }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <span class="text-secondary small">{{ $completionRate }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="material-icons-outlined text-secondary" style="font-size: 48px;">category</i>
                            <p class="text-secondary mt-2 mb-0">No data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Teller Statistics -->
        <div class="col-lg-6">
            <div class="card rounded-4">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="material-icons-outlined">people</i>
                        <span>Statistics by Teller</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($tellerStats->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Teller</th>
                                        <th>Counter</th>
                                        <th>Total</th>
                                        <th>Completed</th>
                                        <th>Completion Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tellerStats as $teller)
                                        @php
                                            $completionRate = $teller->tickets_count > 0 
                                                ? round(($teller->done_count / $teller->tickets_count) * 100, 1) 
                                                : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="fw-bold">{{ $teller->name }}</span>
                                            </td>
                                            <td>
                                                <small class="text-secondary">{{ $teller->counter_name ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ $teller->tickets_count }}</span>
                                            </td>
                                            <td>
                                                <span class="text-success fw-bold">{{ $teller->done_count }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="progress flex-grow-1" style="height: 20px;">
                                                        <div class="progress-bar bg-success" role="progressbar" 
                                                             style="width: {{ $completionRate }}%"
                                                             aria-valuenow="{{ $completionRate }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <span class="text-secondary small">{{ $completionRate }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="material-icons-outlined text-secondary" style="font-size: 48px;">people</i>
                            <p class="text-secondary mt-2 mb-0">No data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
