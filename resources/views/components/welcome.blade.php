<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="{{ asset('assets/images/logo-icon.png') }}" alt="Logo" class="img-fluid" style="max-height: 60px;">
                    <div>
                        <h2 class="mb-0 fw-bold">Welcome to NORSU-GUIHULNGAN Queue System</h2>
                        <p class="text-secondary mb-0">Efficient ticket management system</p>
                    </div>
                </div>

                <p class="text-secondary mb-4">
                    Manage your queue system efficiently with real-time updates, ticket generation, and teller management. 
                    This system helps streamline operations for Payment, Disbursement, and other services.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-2">
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 rounded p-3">
                        <i class="material-icons-outlined text-primary fs-2">dashboard</i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-secondary">Dashboard</h6>
                        <h4 class="mb-0 fw-bold">Overview</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 rounded p-3">
                        <i class="material-icons-outlined text-success fs-2">receipt</i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-secondary">Tickets</h6>
                        <h4 class="mb-0 fw-bold">Queue</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-info bg-opacity-10 rounded p-3">
                        <i class="material-icons-outlined text-info fs-2">people</i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-secondary">Tellers</h6>
                        <h4 class="mb-0 fw-bold">Staff</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-warning bg-opacity-10 rounded p-3">
                        <i class="material-icons-outlined text-warning fs-2">assessment</i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-secondary">Reports</h6>
                        <h4 class="mb-0 fw-bold">Analytics</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@auth
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if(auth()->user()->isAdmin())
                            <div class="col-md-4">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                    <i class="material-icons-outlined">dashboard</i>
                                    <span>Admin Dashboard</span>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('kiosk') }}" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center gap-2">
                                    <i class="material-icons-outlined">add_circle</i>
                                    <span>Kiosk Mode</span>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('display') }}" class="btn btn-outline-info w-100 d-flex align-items-center justify-content-center gap-2">
                                    <i class="material-icons-outlined">tv</i>
                                    <span>Display Monitor</span>
                                </a>
                            </div>
                        @elseif(auth()->user()->isTeller())
                            <div class="col-md-6">
                                <a href="{{ route('teller.dashboard') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                    <i class="material-icons-outlined">dashboard</i>
                                    <span>Teller Dashboard</span>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('display') }}" class="btn btn-outline-info w-100 d-flex align-items-center justify-content-center gap-2">
                                    <i class="material-icons-outlined">tv</i>
                                    <span>Display Monitor</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endauth
