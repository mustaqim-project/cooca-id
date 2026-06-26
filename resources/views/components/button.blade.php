@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'icon' => null
])

@php
    $baseClasses = 'inline-flex justify-center items-center font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-xs rounded-md',
        'md' => 'px-4 py-2 text-sm rounded-lg',
        'lg' => 'px-6 py-3 text-base rounded-xl',
    ][$size];
    
    $variantClasses = [
        'primary' => 'bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white shadow-sm hover:shadow focus:ring-primary-500 border border-transparent',
        'secondary' => 'bg-white dark:bg-surface-800 text-surface-700 dark:text-surface-200 border border-surface-300 dark:border-surface-600 hover:bg-surface-50 dark:hover:bg-surface-700 shadow-sm focus:ring-primary-500',
        'danger' => 'bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white shadow-sm hover:shadow focus:ring-red-500 border border-transparent',
        'ghost' => 'text-surface-600 dark:text-surface-300 hover:bg-surface-100 dark:hover:bg-surface-800 hover:text-surface-900 dark:hover:text-white',
    ][$variant];

    $classes = "{$baseClasses} {$sizeClasses} {$variantClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <i data-lucide="{{ $icon }}" class="{{ $size === 'sm' ? 'w-4 h-4 mr-1.5' : 'w-5 h-5 mr-2' }}"></i>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <i data-lucide="{{ $icon }}" class="{{ $size === 'sm' ? 'w-4 h-4 mr-1.5' : 'w-5 h-5 mr-2' }}"></i>
        @endif
        {{ $slot }}
    </button>
@endif
