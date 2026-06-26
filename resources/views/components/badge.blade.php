@props([
    'variant' => 'default',
    'rounded' => 'rounded-full',
])

@php
    $baseClasses = "inline-flex items-center px-2.5 py-0.5 text-xs font-medium {$rounded}";
    
    $variantClasses = [
        'default' => 'bg-surface-100 text-surface-800 dark:bg-surface-700 dark:text-surface-200 border border-surface-200 dark:border-surface-600',
        'primary' => 'bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-300 border border-primary-200 dark:border-primary-800',
        'success' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-800',
        'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800',
        'danger' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border border-red-200 dark:border-red-800',
        'info' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
    ][$variant];
@endphp

<span {{ $attributes->merge(['class' => "{$baseClasses} {$variantClasses}"]) }}>
    {{ $slot }}
</span>
