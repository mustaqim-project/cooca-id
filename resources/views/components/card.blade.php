@props([
    'title' => null,
    'subtitle' => null,
    'footer' => null,
    'padding' => 'p-6',
    'noPadding' => false
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-surface-800 rounded-2xl border border-surface-200 dark:border-surface-700 shadow-sm overflow-hidden transition-shadow hover:shadow-md']) }}>
    
    @if($title || $subtitle || isset($header))
        <div class="px-6 py-4 border-b border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
            @if(isset($header))
                {{ $header }}
            @else
                @if($title)
                    <h3 class="text-lg font-heading font-semibold text-surface-900 dark:text-white">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="mt-1 text-sm text-surface-500 dark:text-surface-400">{{ $subtitle }}</p>
                @endif
            @endif
        </div>
    @endif

    <div class="{{ $noPadding ? '' : $padding }}">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-6 py-4 border-t border-surface-200 dark:border-surface-700 bg-surface-50/50 dark:bg-surface-900/50">
            {{ $footer }}
        </div>
    @endif
</div>
