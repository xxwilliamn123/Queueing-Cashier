@props(['for'])

@error($for)
    <div {{ $attributes->merge(['class' => 'text-danger small mt-1']) }}>{{ $message }}</div>
@enderror
