@props(['style' => session('flash.bannerStyle', 'success'), 'message' => session('flash.banner')])

@php
$alertClass = match($style) {
    'success' => 'alert-success',
    'danger' => 'alert-danger',
    'warning' => 'alert-warning',
    default => 'alert-info',
};
@endphp

<div x-data="{{ json_encode(['show' => true, 'style' => $style, 'message' => $message]) }}"
    class="alert {{ $alertClass }} alert-dismissible fade show"
    style="display: none;"
    x-show="show && message"
    x-on:banner-message.window="
        style = event.detail.style;
        message = event.detail.message;
        show = true;
    ">
    <div class="d-flex align-items-center">
        <i class="material-icons-outlined me-2">
            @if($style == 'success')
                check_circle
            @elseif($style == 'danger')
                error
            @elseif($style == 'warning')
                warning
            @else
                info
            @endif
        </i>
        <span x-text="message"></span>
    </div>
    <button type="button" class="btn-close" aria-label="Close" x-on:click="show = false"></button>
</div>
