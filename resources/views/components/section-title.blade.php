<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">{{ $title }}</h3>
        @if(isset($description))
            <p class="text-secondary mb-0 small">{{ $description }}</p>
        @endif
    </div>

    @if(isset($aside))
        <div>
            {{ $aside }}
        </div>
    @endif
</div>
