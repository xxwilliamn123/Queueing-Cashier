@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="modal-header border-bottom-0 py-2">
        <div class="d-flex align-items-center gap-3">
            <div class="flex-shrink-0">
                <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="material-icons-outlined text-danger">warning</i>
                </div>
            </div>
            <div class="flex-grow-1">
                <h5 class="modal-title mb-0">{{ $title }}</h5>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" x-on:click="$wire.set('{{ $attributes->wire('model')->value() }}', false)"></button>
    </div>

    <div class="modal-body">
        <div class="text-secondary">
            {{ $content }}
        </div>
    </div>

    <div class="modal-footer border-top-0">
        {{ $footer }}
    </div>
</x-modal>
