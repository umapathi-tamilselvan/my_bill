@props(['type' => 'button', 'variant' => 'primary', 'size' => '', 'class' => ''])

@php
    $classes = 'btn btn-' . $variant;
    if ($size) {
        $classes .= ' btn-' . $size;
    }
    if ($class) {
        $classes .= ' ' . $class;
    }
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>

