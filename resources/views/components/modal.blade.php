@props(['id', 'maxWidth'])

@php
$hasWireModel = $attributes->has('wire:model') || $attributes->has('wire:model.live');
$modelValue = $hasWireModel ? $attributes->wire('model')->value() : null;
$id = $id ?? ($hasWireModel ? md5($modelValue) : md5(uniqid()));

// Debug logging
if ($hasWireModel) {
    \Log::info('Modal component - wire:model detected', [
        'model' => $modelValue,
        'has_wire_model' => $attributes->has('wire:model'),
        'has_wire_model_live' => $attributes->has('wire:model.live'),
    ]);
}

$maxWidth = [
    'sm' => 'modal-sm',
    'md' => '',
    'lg' => 'modal-lg',
    'xl' => 'modal-xl',
    '2xl' => 'modal-xl',
][$maxWidth ?? 'lg'];
@endphp

@if($hasWireModel)
<div
    wire:ignore.self
    wire:key="modal-{{ $id }}"
    x-data="{ 
        show: @entangle($attributes->wire('model'))
    }"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    id="{{ $id }}"
    class="modal fade"
    :class="{ 'show': show, 'd-block': show }"
    style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1055; overflow-x: hidden; overflow-y: auto; padding: 0 !important;"
    tabindex="-1"
    role="dialog"
    aria-modal="true"
    :aria-hidden="!show"
    x-bind:style="show ? 'display: block !important; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1055; overflow-x: hidden; overflow-y: auto; padding: 0 !important;' : 'display: none !important;'"
>
@else
<div
    x-data="{ show: false }"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    x-cloak
    id="{{ $id }}"
    class="modal fade"
    :class="{ 'show': show }"
    :style="show ? 'display: block !important; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1055; overflow-x: hidden; overflow-y: auto;' : 'display: none !important; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1055;'"
    tabindex="-1"
    role="dialog"
    aria-modal="true"
    :aria-hidden="!show"
>
@endif
    <div 
        wire:key="backdrop-{{ $id }}"
        class="modal-backdrop fade" 
        x-show="show" 
        :class="{ 'show': show }" 
        x-on:click="show = false" 
        :style="show ? 'position: fixed; top: 0; left: 0; z-index: 1054; width: 100vw; height: 100vh; background-color: rgba(0, 0, 0, 0.5); pointer-events: auto;' : ''"
    ></div>
    <div 
        wire:key="dialog-{{ $id }}"
        class="modal-dialog modal-dialog-centered {{ $maxWidth }}" 
        x-show="show" 
        style="position: relative; z-index: 1056; pointer-events: auto;"
    >
        <div class="modal-content" style="pointer-events: auto;" @click.stop wire:key="content-{{ $id }}">
            {{ $slot }}
    </div>
    </div>
</div>
