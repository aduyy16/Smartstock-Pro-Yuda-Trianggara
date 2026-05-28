<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true',
          sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
          sidebarOpen: false
      }"
      x-init="
          $watch('darkMode', val => localStorage.setItem('darkMode', val));
          $watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val));
      "
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Inventory Suite') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        
        <div class="min-h-screen flex">

            <!-- 1. LEFT COLLAPSIBLE SIDEBAR (DESKTOP) -->
            <aside :class="sidebarCollapsed ? 'w-20' : 'w-64'" 
                   class="hidden lg:flex flex-col bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 transition-all duration-300 flex-shrink-0 z-30">
                
                <!-- Sidebar Header -->
                <div class="h-16 flex items-center px-6 border-b border-gray-100 dark:border-gray-700">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 overflow-hidden">
                        <span class="inline-flex p-2 bg-indigo-600 rounded-xl text-white shadow-md shadow-indigo-600/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </span>
                        <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="font-black text-sm text-gray-900 dark:text-white uppercase tracking-wider whitespace-nowrap">
                            Inventory Suite
                        </span>
                    </a>
                </div>

                <!-- Navigation List -->
                <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                    <!-- Dashboard -->
                    <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
                        Dashboard
                    </x-sidebar-link>

                    <!-- Products -->
                    @hasanyrole('Admin|Manager Gudang|Viewer')
                    <x-sidebar-link :href="route('products.index')" :active="request()->routeIs('products.*')" icon="products">
                        Products Catalog
                    </x-sidebar-link>
                    @endhasanyrole

                    <!-- Transactions -->
                    @hasanyrole('Admin|Manager Gudang|Staff Gudang|Viewer')
                    <x-sidebar-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')" icon="transactions">
                        Movements Ledger
                    </x-sidebar-link>
                    @endhasanyrole

                    <!-- Warehouses -->
                    @hasanyrole('Admin|Manager Gudang|Staff Gudang|Viewer')
                    <x-sidebar-link :href="route('warehouses.index')" :active="request()->routeIs('warehouses.*')" icon="map">
                        Geographic Hubs
                    </x-sidebar-link>
                    @endhasanyrole

                    <!-- Monitoring (Admin only) -->
                    @role('Admin')
                    <x-sidebar-link :href="route('monitoring.index')" :active="request()->routeIs('monitoring.*')" icon="presentation">
                        System Monitor
                    </x-sidebar-link>
                    @endrole
                </nav>

                <!-- Sidebar Footer Collapse Toggle -->
                <div class="p-4 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                    <button @click="sidebarCollapsed = !sidebarCollapsed" class="p-2 rounded-lg bg-gray-50 hover:bg-gray-100 dark:bg-gray-900 dark:hover:bg-gray-800 text-gray-400 hover:text-indigo-600 transition shadow-sm">
                        <svg :class="sidebarCollapsed ? 'rotate-180' : ''" class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                        </svg>
                    </button>
                </div>
            </aside>

            <!-- 2. MOBILE DRAWER SIDEBAR -->
            <div x-show="sidebarOpen" class="fixed inset-0 flex z-50 lg:hidden" style="display: none;" role="dialog" aria-modal="true">
                <!-- Backdrop filter blur -->
                <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs" @click="sidebarOpen = false"></div>

                <!-- Slide Panel -->
                <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex-1 flex flex-col max-w-xs w-full bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700">
                    
                    <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100 dark:border-gray-700">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                            <span class="inline-flex p-2 bg-indigo-600 rounded-xl text-white shadow-md">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </span>
                            <span class="font-black text-sm text-gray-900 dark:text-white uppercase tracking-wider">Inventory</span>
                        </a>
                        <button @click="sidebarOpen = false" class="p-2 rounded-lg text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
                            Dashboard
                        </x-sidebar-link>

                        @hasanyrole('Admin|Manager Gudang|Viewer')
                        <x-sidebar-link :href="route('products.index')" :active="request()->routeIs('products.*')" icon="products">
                            Products Catalog
                        </x-sidebar-link>
                        @endhasanyrole

                        @hasanyrole('Admin|Manager Gudang|Staff Gudang|Viewer')
                        <x-sidebar-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')" icon="transactions">
                            Movements Ledger
                        </x-sidebar-link>
                        @endhasanyrole

                        @hasanyrole('Admin|Manager Gudang|Staff Gudang|Viewer')
                        <x-sidebar-link :href="route('warehouses.index')" :active="request()->routeIs('warehouses.*')" icon="map">
                            Geographic Hubs
                        </x-sidebar-link>
                        @endhasanyrole

                        @role('Admin')
                        <x-sidebar-link :href="route('monitoring.index')" :active="request()->routeIs('monitoring.*')" icon="presentation">
                            System Monitor
                        </x-sidebar-link>
                        @endrole
                    </nav>
                </div>
            </div>

            <!-- 3. MAIN SECTION WITH NAVBAR & CONTENT -->
            <div class="flex-1 flex flex-col min-w-0 overflow-x-hidden">
                
                <!-- STICKY NAVBAR WITH GLASSMORPHISM -->
                <header class="sticky top-0 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 z-40 h-16 flex items-center justify-between px-6 transition">
                    
                    <!-- Left: Mobile Menu Trigger -->
                    <div class="flex items-center space-x-4">
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <!-- Static Search decoration inside navbar -->
                        <div class="hidden sm:flex items-center space-x-2 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl px-3 py-1.5 w-60 lg:w-72 shadow-2xs">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="text-xs text-gray-400 dark:text-gray-500 select-none">Quick Search...</span>
                        </div>
                    </div>

                    <!-- Right: Tool icons (Dark Mode, Notifications, Profile) -->
                    <div class="flex items-center space-x-4">
                        
                        <!-- Dark Mode Toggle Button -->
                        <button @click="darkMode = !darkMode" class="p-2 rounded-xl bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 transition shadow-2xs border border-gray-100/50 dark:border-gray-700/50">
                            <!-- Sun icon for dark state -->
                            <svg x-show="darkMode" class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20" style="display: none;">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.46 5.05L5.75 4.343a1 1 0 10-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                            </svg>
                            <!-- Moon icon for light state -->
                            <svg x-show="!darkMode" class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                        </button>

                        <!-- Notification Dropdown -->
                        <x-dropdown align="right" width="80">
                            <x-slot name="trigger">
                                <button class="relative p-2 rounded-xl bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 transition shadow-2xs border border-gray-100/50 dark:border-gray-700/50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <span class="absolute top-1.5 right-1.5 inline-flex h-2 w-2 rounded-full bg-red-600 animate-ping"></span>
                                        <span class="absolute top-1.5 right-1.5 inline-flex h-2 w-2 rounded-full bg-red-600"></span>
                                    @endif
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="flex items-center justify-between px-4 py-2.5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Notifications ({{ auth()->user()->unreadNotifications->count() }})
                                    </span>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <form action="{{ route('notifications.read-all') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xxs text-indigo-600 dark:text-indigo-400 hover:underline">
                                                Mark all read
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <div class="max-h-64 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                                    @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                                        <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition flex items-start justify-between text-xs">
                                            <div class="mr-3">
                                                <p class="text-gray-600 dark:text-gray-400 leading-normal">{{ $notification->data['message'] }}</p>
                                                <p class="text-2xs text-gray-400 dark:text-gray-500 font-mono mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="inline flex-shrink-0">
                                                @csrf
                                                <button type="submit" class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition" title="Mark as read">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @empty
                                        <div class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                            <p class="text-xs font-semibold">All cleared!</p>
                                        </div>
                                    @endforelse
                                </div>
                            </x-slot>
                        </x-dropdown>

                        <!-- User Settings Dropdown -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-semibold rounded-xl text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 shadow-2xs">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ms-1.5 text-gray-400">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 11-1.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile Settings') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-750 transition duration-150">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-grow p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- 4. FLOATING TOAST NOTIFICATIONS PIPELINE -->
        <div class="fixed bottom-5 right-5 space-y-3.5 z-50 pointer-events-none" id="toast-notification-system">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" 
                     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                     class="pointer-events-auto flex items-center p-4 bg-white dark:bg-gray-800 border-l-4 border-emerald-500 rounded-xl shadow-lg shadow-gray-200/50 dark:shadow-none max-w-sm border border-gray-100 dark:border-gray-700">
                    <div class="mr-3 text-emerald-500 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 p-2 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-wider">Success Alert</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" 
                     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                     class="pointer-events-auto flex items-center p-4 bg-white dark:bg-gray-800 border-l-4 border-red-500 rounded-xl shadow-lg shadow-gray-200/50 dark:shadow-none max-w-sm border border-gray-100 dark:border-gray-700">
                    <div class="mr-3 text-red-500 dark:text-red-400 bg-red-50 dark:bg-red-950/30 p-2 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-wider">Exception Warning</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">{{ session('error') }}</p>
                    </div>
                </div>
            @endif
        </div>

    </body>
</html>
