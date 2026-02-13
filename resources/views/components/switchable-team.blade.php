@props(['team', 'component' => 'dropdown-link'])

<form method="POST" action="{{ route('current-team.update') }}" x-data>
    @method('PUT')
    @csrf

    <!-- Hidden Team ID -->
    <input type="hidden" name="team_id" value="{{ $team->id }}">

    <x-dynamic-component :component="$component" href="#" x-on:click.prevent="$root.submit();">
        <div class="d-flex align-items-center">
            @if (Auth::user()->isCurrentTeam($team))
                <i class="material-icons-outlined me-2 text-success">check_circle</i>
            @endif

            <div class="text-truncate">{{ $team->name }}</div>
        </div>
    </x-dynamic-component>
</form>
