@props([
    'total',
    'label' => 'Total:',
    'icon' => null,
    'color' => 'primary',
    'class' => '',
])

<div class="d-inline-flex align-items-center gap-2 bg-{{ $color }} bg-opacity-10 border border-{{ $color }} rounded-5 px-3 py-2 shadow-sm {{ $class }}">
    @if($icon)
        <i class="bi bi-{{ $icon }} text-{{ $color }} fs-5"></i>
    @endif

    <div class="text-{{ $color }}">
        <span class="fw-semibold">{{ $label }}</span>
        <span class="fw-bold">{{ $total }}</span>
    </div>
</div>
