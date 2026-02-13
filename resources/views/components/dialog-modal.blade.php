@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="modal-header border-bottom-0 py-2">
        <h5 class="modal-title">{{ $title }}</h5>
        <button type="button" class="btn-close" aria-label="Close" x-on:click="$wire.set('{{ $attributes->wire('model')->value() }}', false)"></button>
    </div>

    <div class="modal-body">
        {{ $content }}
    </div>

    <div class="modal-footer border-top-0">
        {{ $footer }}
    </div>
</x-modal>
