{{-- SmartStock Pro — Application Logo (used on guest pages: login, register, etc.) --}}
<div {{ $attributes->merge(['class' => 'flex flex-col items-center']) }}>
    <span class="inline-flex p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl text-white shadow-lg shadow-indigo-500/25">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" stroke-width="2" opacity="0.7"></path>
        </svg>
    </span>
    <h1 class="mt-4 text-2xl font-black text-gray-900 dark:text-white tracking-tight">
        Smart<span class="text-indigo-600 dark:text-indigo-400">Stock</span> Pro
    </h1>
    <p class="text-xs text-gray-400 dark:text-gray-500 font-medium mt-1">
        Smart Inventory Management System
    </p>
</div>
