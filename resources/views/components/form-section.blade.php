@props(['submit'])

<div {{ $attributes->merge(['class' => 'row']) }}>
    <div class="col-md-12">
        <x-section-title>
            <x-slot name="title">{{ $title }}</x-slot>
            <x-slot name="description">{{ $description }}</x-slot>
        </x-section-title>
    </div>

    <div class="col-md-12 mt-3">
        <form wire:submit="{{ $submit }}">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        {{ $form }}
                    </div>
                </div>
                @if (isset($actions))
                    <div class="card-footer bg-transparent border-top">
                        <div class="d-flex justify-content-end">
                            {{ $actions }}
                        </div>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
