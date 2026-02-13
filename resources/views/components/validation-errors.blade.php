@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'alert alert-danger alert-dismissible fade show']) }}>
        <strong>{{ __('Whoops! Something went wrong.') }}</strong>
        <ul class="mb-0 mt-2 list-unstyled">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
