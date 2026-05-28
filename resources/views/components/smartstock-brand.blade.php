@props(['size' => 'md', 'showSubtitle' => true])

@php
    $sizes = [
        'sm' => ['icon' => 'w-6 h-6', 'iconBox' => 'p-2', 'title' => 'text-sm', 'subtitle' => 'text-2xs'],
        'md' => ['icon' => 'w-7 h-7', 'iconBox' => 'p-2.5', 'title' => 'text-base', 'subtitle' => 'text-xs'],
        'lg' => ['icon' => 'w-9 h-9', 'iconBox' => 'p-3', 'title' => 'text-xl', 'subtitle' => 'text-sm'],
    ];
    $s = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center space-x-3']) }}>
    {{-- Icon Box --}}
    <span class="inline-flex {{ $s['iconBox'] }} bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl text-white shadow-lg shadow-indigo-500/25 flex-shrink-0">
        <svg class="{{ $s['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" stroke-width="2" opacity="0.7"></path>
        </svg>
    </span>
    {{-- Text --}}
    <div class="overflow-hidden">
        <span class="{{ $s['title'] }} font-black text-gray-900 dark:text-white tracking-tight whitespace-nowrap block leading-tight">
            Smart<span class="text-indigo-600 dark:text-indigo-400">Stock</span> Pro
        </span>
        @if($showSubtitle)
            <span class="{{ $s['subtitle'] }} text-gray-400 dark:text-gray-500 font-medium whitespace-nowrap block leading-tight mt-0.5">
                Smart Inventory Management System
            </span>
        @endif
    </div>
</div>
