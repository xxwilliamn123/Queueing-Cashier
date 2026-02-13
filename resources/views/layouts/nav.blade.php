<ul class="metismenu" id="menu">
    @auth
        @if(auth()->user()->isAdmin())
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">dashboard</i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="material-icons-outlined">people</i></div>
                    <div class="menu-title">Manage</div>
                </a>
                <ul>
                    <li><a href="{{ route('admin.categories') }}"><i class="material-icons-outlined">category</i>Categories</a></li>
                    <li><a href="{{ route('admin.tellers') }}"><i class="material-icons-outlined">person</i>Tellers</a></li>
                    <li><a href="{{ route('admin.settings') }}"><i class="material-icons-outlined">settings</i>Settings</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="material-icons-outlined">receipt</i></div>
                    <div class="menu-title">Tickets</div>
                </a>
                <ul>
                    <li><a href="{{ route('admin.tickets') }}"><i class="material-icons-outlined">list</i>All Tickets</a></li>
                    <li><a href="{{ route('admin.reports') }}"><i class="material-icons-outlined">assessment</i>Reports</a></li>
                </ul>
            </li>
        @elseif(auth()->user()->isTeller())
            <li>
                <a href="{{ route('teller.dashboard') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">dashboard</i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>
            <li>
                <a href="{{ route('teller.my-queue') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">queue</i></div>
                    <div class="menu-title">My Queue</div>
                </a>
            </li>
        @endif
        <li>
            <a href="{{ route('profile.show') }}">
                <div class="parent-icon"><i class="material-icons-outlined">person_outline</i></div>
                <div class="menu-title">Profile</div>
            </a>
        </li>
    @endauth
</ul>

