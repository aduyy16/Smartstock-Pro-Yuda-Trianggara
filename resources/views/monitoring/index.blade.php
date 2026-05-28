<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('System Monitoring & Queue Console') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Real-time infrastructure health metrics, database error console, and background job queues.</p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Live Synchronization Indicator -->
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 shadow-sm transition">
                    <span class="relative flex h-2 w-2 mr-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    Live Syncing
                </span>
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400 uppercase shadow-sm">
                    Admin
                </span>
            </div>
        </div>
    </x-slot>

    <!-- Chart.js and Custom JS dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 text-sm text-green-700 bg-green-100 dark:bg-green-900/30 dark:text-green-400 rounded-lg shadow-sm flex items-center" role="alert">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Main Multi-Column Layout mimicking premium Admin Sidebar Style -->
            <div class="flex flex-col lg:flex-row gap-8" x-data="{ currentTab: 'overview' }">
                
                <!-- 1. Left Responsive Sidebar (Collapsible on Mobile) -->
                <div class="w-full lg:w-64 flex-shrink-0">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                            <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">Console Menu</h3>
                            <p class="text-xxs text-gray-400 dark:text-gray-500 mt-1 font-mono">NODE_STATUS: active</p>
                        </div>
                        <nav class="p-4 space-y-1">
                            <button @click="currentTab = 'overview'" :class="currentTab === 'overview' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50'" class="w-full flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition duration-150">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                System Overview
                            </button>

                            <button @click="currentTab = 'charts'" :class="currentTab === 'charts' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50'" class="w-full flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition duration-150">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Performance Charts
                            </button>

                            <button @click="currentTab = 'logs'" :class="currentTab === 'logs' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50'" class="w-full flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition duration-150">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Error Console
                                @if($totalErrors > 0)
                                    <span class="ml-auto inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold bg-red-100 text-red-800 dark:bg-red-950 dark:text-red-400">
                                        {{ $totalErrors }}
                                    </span>
                                @endif
                            </button>

                            <button @click="currentTab = 'queues'" :class="currentTab === 'queues' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50'" class="w-full flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition duration-150">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                Background Jobs
                            </button>
                        </nav>
                    </div>

                    <!-- Dispatch Tools Sidebar Widget -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mt-6">
                        <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-4">Quick Dispatch</h4>
                        <div class="space-y-3">
                            <form action="{{ route('queue.generate-report') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Queue Excel Report
                                </button>
                            </form>

                            <form action="{{ route('queue.low-stock') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    Scan Low Stock Stock
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 2. Right Content Console Panels -->
                <div class="flex-1 space-y-8">
                    
                    <!-- TAB 1: OVERVIEW -->
                    <div x-show="currentTab === 'overview'" class="space-y-8">
                        <!-- Stat Cards Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Users Card -->
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 hover:shadow-md transition">
                                <div class="p-6 flex items-center space-x-4">
                                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Users</p>
                                        <p class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $totalUsers }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Transactions Card -->
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 hover:shadow-md transition">
                                <div class="p-6 flex items-center space-x-4">
                                    <div class="p-3 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Transactions</p>
                                        <p class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $totalTransactions }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Products Card -->
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 hover:shadow-md transition">
                                <div class="p-6 flex items-center space-x-4">
                                    <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Products</p>
                                        <p class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $totalProducts }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Active Issues Card -->
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 hover:shadow-md transition">
                                <div class="p-6 flex items-center space-x-4">
                                    <div class="p-3 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">
                                        <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">System Errors</p>
                                        <p class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $totalErrors }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Main Historical Graph Widget (Overview Tab) -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Active System Health Trends</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Real-time rolling performance charts (updated every 3 seconds)</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button @click="currentTab = 'charts'" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">View Detailed Charts &rarr;</button>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- CPU Sparkline Area -->
                                <div class="bg-gray-50/50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">CPU Usage</span>
                                        <span class="text-sm font-black text-indigo-600 dark:text-indigo-400" id="live-cpu-val">0%</span>
                                    </div>
                                    <div class="h-28">
                                        <canvas id="cpuMiniChart"></canvas>
                                    </div>
                                </div>
                                
                                <!-- RAM Sparkline Area -->
                                <div class="bg-gray-50/50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Memory Load</span>
                                        <span class="text-sm font-black text-sky-600 dark:text-sky-400" id="live-mem-val">0%</span>
                                    </div>
                                    <div class="h-28">
                                        <canvas id="memMiniChart"></canvas>
                                    </div>
                                </div>

                                <!-- Latency Sparkline Area -->
                                <div class="bg-gray-50/50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Response Latency</span>
                                        <span class="text-sm font-black text-amber-500 dark:text-amber-400" id="live-time-val">0ms</span>
                                    </div>
                                    <div class="h-28">
                                        <canvas id="timeMiniChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Combined Quick-view (Logs Console Preview & Background Jobs status) -->
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                            <!-- Critical Logs Preview -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-md font-bold text-gray-900 dark:text-white">Active Logs Preview</h3>
                                        <button @click="currentTab = 'logs'" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">Full Console &rarr;</button>
                                    </div>
                                    <div class="space-y-3">
                                        @forelse($logs->take(4) as $log)
                                            <div class="p-3 bg-gray-50 dark:bg-gray-900/30 border border-gray-100 dark:border-gray-800 rounded-lg flex items-start space-x-3 text-xs">
                                                @if($log->level === 'critical')
                                                    <span class="mt-0.5 inline-flex h-2.5 w-2.5 rounded-full bg-red-600 animate-pulse flex-shrink-0" title="Critical"></span>
                                                @elseif($log->level === 'warning')
                                                    <span class="mt-0.5 inline-flex h-2.5 w-2.5 rounded-full bg-amber-500 flex-shrink-0" title="Warning"></span>
                                                @else
                                                    <span class="mt-0.5 inline-flex h-2.5 w-2.5 rounded-full bg-blue-500 flex-shrink-0" title="Info"></span>
                                                @endif
                                                <div class="truncate">
                                                    <p class="font-semibold text-gray-900 dark:text-gray-200 truncate">{{ $log->message }}</p>
                                                    <p class="text-xxs text-gray-400 dark:text-gray-500 font-mono mt-0.5">COMP: {{ $log->component }} &bull; {{ $log->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="py-6 text-center text-gray-400 dark:text-gray-500">
                                                No logs seeded. Click dispatch tools to generate!
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- Background Jobs Preview -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-md font-bold text-gray-900 dark:text-white">Reports Process Tracker</h3>
                                        <button @click="currentTab = 'queues'" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">Full Queue &rarr;</button>
                                    </div>
                                    <div class="space-y-3">
                                        @forelse($reports->take(4) as $report)
                                            <div class="p-3 bg-gray-50 dark:bg-gray-900/30 border border-gray-100 dark:border-gray-800 rounded-lg flex items-center justify-between text-xs">
                                                <div class="truncate mr-3">
                                                    <p class="font-semibold text-gray-900 dark:text-gray-200 truncate">{{ $report->filename }}</p>
                                                    <p class="text-xxs text-gray-400 dark:text-gray-500 font-mono mt-0.5">USER: {{ $report->user->name }} &bull; {{ $report->created_at->diffForHumans() }}</p>
                                                </div>
                                                <div>
                                                    @if($report->status === 'completed')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                            Ready
                                                        </span>
                                                    @elseif($report->status === 'processing')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400 animate-pulse">
                                                            Compiling
                                                        </span>
                                                    @elseif($report->status === 'pending')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 animate-bounce">
                                                            Queued
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xxs font-bold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                            Failed
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div class="py-6 text-center text-gray-400 dark:text-gray-500">
                                                No reports compiled in the background yet.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: DETAILED CHARTS -->
                    <div x-show="currentTab === 'charts'" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-8" style="display: none;">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Detailed System Performance Timeline</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Comprehensive real-time diagnostics scrolling logs of standard infrastructure components.</p>
                        </div>
                        <div class="space-y-6">
                            <!-- Main CPU Chart -->
                            <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-800">
                                <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                                    <span class="w-3 h-3 rounded-full bg-indigo-600 dark:bg-indigo-400 mr-2 inline-block"></span>
                                    CPU Usage Timeline (%)
                                </h4>
                                <div style="height: 180px;">
                                    <canvas id="cpuFullChart"></canvas>
                                </div>
                            </div>

                            <!-- Main RAM Chart -->
                            <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-800">
                                <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                                    <span class="w-3 h-3 rounded-full bg-sky-600 dark:bg-sky-400 mr-2 inline-block"></span>
                                    Memory Consumption Timeline (%)
                                </h4>
                                <div style="height: 180px;">
                                    <canvas id="memFullChart"></canvas>
                                </div>
                            </div>

                            <!-- Main Response Time Chart -->
                            <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-800">
                                <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3 flex items-center">
                                    <span class="w-3 h-3 rounded-full bg-amber-500 mr-2 inline-block"></span>
                                    Application Latency Timeline (ms)
                                </h4>
                                <div style="height: 180px;">
                                    <canvas id="timeFullChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: ERROR CONSOLE LOGS -->
                    <div x-show="currentTab === 'logs'" class="space-y-6" style="display: none;">
                        <!-- Filter & Search Panel -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                            <form action="{{ route('monitoring.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                                <input type="hidden" name="tab" value="logs">
                                <div class="flex-1 w-full">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Search Logs</label>
                                    <div class="relative">
                                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by message or component..." class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                    </div>
                                </div>
                                <div class="w-full md:w-48">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Severity Level</label>
                                    <select name="level" class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                        <option value="">All Severities</option>
                                        <option value="critical" {{ request('level') === 'critical' ? 'selected' : '' }}>Critical</option>
                                        <option value="warning" {{ request('level') === 'warning' ? 'selected' : '' }}>Warning</option>
                                        <option value="info" {{ request('level') === 'info' ? 'selected' : '' }}>Info</option>
                                    </select>
                                </div>
                                <div class="flex gap-2 w-full md:w-auto">
                                    <button type="submit" class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                                        Search
                                    </button>
                                    <a href="{{ route('monitoring.index') }}" class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-lg shadow-sm transition">
                                        Reset
                                    </a>
                                </div>
                            </form>
                        </div>

                        <!-- Logs Table Panel -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                <div>
                                    <h3 class="text-md font-bold text-gray-900 dark:text-white">Active Logs Console</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Persisted logs registered in the SQLite monitor table.</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <!-- Seed Random Action -->
                                    <form action="{{ route('monitoring.generate') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="count" value="5">
                                        <button type="submit" class="text-xs inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg font-bold shadow-sm transition">
                                            Seed 5 Logs
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs divide-y divide-gray-100 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">
                                        <tr>
                                            <th class="px-6 py-4">Severity</th>
                                            <th class="px-6 py-4">Component</th>
                                            <th class="px-6 py-4">Message</th>
                                            <th class="px-6 py-4">Logged At</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @forelse($logs as $log)
                                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($log->level === 'critical')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xxs font-black uppercase tracking-wider bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200/50 dark:border-red-800/30">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5 animate-pulse"></span>
                                                            Critical
                                                        </span>
                                                    @elseif($log->level === 'warning')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xxs font-black uppercase tracking-wider bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200/50 dark:border-amber-800/30">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                                            Warning
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xxs font-black uppercase tracking-wider bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200/50 dark:border-blue-800/30">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>
                                                            Info
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 font-mono font-bold text-gray-700 dark:text-gray-300">
                                                    {{ $log->component }}
                                                </td>
                                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400 leading-normal font-sans">
                                                    {{ $log->message }}
                                                </td>
                                                <td class="px-6 py-4 text-gray-400 dark:text-gray-500 font-mono whitespace-nowrap">
                                                    {{ $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                                    <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                    </svg>
                                                    <p class="font-bold">Console Clear</p>
                                                    <p class="text-xs mt-1">No system logs met the search constraints.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- Persisted pagination footer links -->
                            @if($logs->hasPages())
                                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/30">
                                    {{ $logs->links() }}
                                </div>
                            @endif
                        </div>

                        <!-- Manual Simulator Panel -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                            <h3 class="text-md font-bold text-gray-900 dark:text-white mb-4">Manual Log Simulation Engine</h3>
                            <form action="{{ route('monitoring.simulate') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Severity Level</label>
                                    <select name="level" class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required>
                                        <option value="info">Info</option>
                                        <option value="warning">Warning</option>
                                        <option value="critical">Critical</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Component Node</label>
                                    <input type="text" name="component" placeholder="e.g. Auth, Redis, Storage" class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Error Event Log Message</label>
                                    <input type="text" name="message" placeholder="Describe the mock exception stack or log notification details..." class="w-full text-sm rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required>
                                </div>
                                <div class="md:col-span-3 text-right">
                                    <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                                        Simulate Event
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- TAB 4: QUEUES AND BACKGROUND REPORTS -->
                    <div x-show="currentTab === 'queues'" class="space-y-6" style="display: none;">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Background Process Console</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Trigger, monitor, and retrieve queued file compilations and scans background-safely.</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <!-- Trigger actions -->
                                <form action="{{ route('queue.generate-report') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                        Create New Excel Job
                                    </button>
                                </form>
                                <form action="{{ route('queue.low-stock') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                        Trigger Stock Check Job
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Queued Reports Table -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                                <h4 class="text-md font-bold text-gray-900 dark:text-white">Background Reports Ledger</h4>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs divide-y divide-gray-100 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">
                                        <tr>
                                            <th class="px-6 py-4">Filename</th>
                                            <th class="px-6 py-4">Requested By</th>
                                            <th class="px-6 py-4">Status</th>
                                            <th class="px-6 py-4">Queued At</th>
                                            <th class="px-6 py-4 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @forelse($reports as $report)
                                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition">
                                                <td class="px-6 py-4 font-mono font-bold text-gray-700 dark:text-gray-300">
                                                    {{ $report->filename }}
                                                </td>
                                                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                                    {{ $report->user->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if($report->status === 'completed')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xxs font-black uppercase tracking-wider bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                                            Completed
                                                        </span>
                                                    @elseif($report->status === 'processing')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xxs font-black uppercase tracking-wider bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400 animate-pulse">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-1.5"></span>
                                                            Processing
                                                        </span>
                                                    @elseif($report->status === 'pending')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xxs font-black uppercase tracking-wider bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 animate-bounce">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                                            Queued
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xxs font-black uppercase tracking-wider bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                                            Failed
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-gray-400 dark:text-gray-500 font-mono">
                                                    {{ $report->created_at ? $report->created_at->format('Y-m-d H:i:s') : '-' }}
                                                </td>
                                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                                    @if($report->status === 'completed' && $report->file_path)
                                                        <a href="{{ $report->file_path }}" download class="inline-flex items-center px-2.5 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded text-xxs font-bold shadow-sm transition">
                                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                            </svg>
                                                            Download
                                                        </a>
                                                    @else
                                                        <button class="inline-flex items-center px-2.5 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 rounded text-xxs font-bold cursor-not-allowed" disabled>
                                                            Processing...
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                                    No reports requested. Use the trigger options to queue job processes.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Script block for scrolling real-time diagnostics graphs -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initial historical data points loaded from controller
            const initialHistory = @json($historyData);
            
            const labels = initialHistory.map(item => item.timestamp);
            const cpuData = initialHistory.map(item => item.cpu);
            const memData = initialHistory.map(item => item.memory);
            const timeData = initialHistory.map(item => item.response_time);

            // Helper to generate gradients for Chart.js
            function createGradient(ctx, color1, color2) {
                const gradient = ctx.createLinearGradient(0, 0, 0, 100);
                gradient.addColorStop(0, color1);
                gradient.addColorStop(1, color2);
                return gradient;
            }

            // Setup Mini overview charts
            const cpuMiniCtx = document.getElementById('cpuMiniChart').getContext('2d');
            const memMiniCtx = document.getElementById('memMiniChart').getContext('2d');
            const timeMiniCtx = document.getElementById('timeMiniChart').getContext('2d');

            const miniCpuGradient = createGradient(cpuMiniCtx, 'rgba(79, 70, 229, 0.4)', 'rgba(79, 70, 229, 0.0)');
            const miniMemGradient = createGradient(memMiniCtx, 'rgba(14, 165, 233, 0.4)', 'rgba(14, 165, 233, 0.0)');
            const miniTimeGradient = createGradient(timeMiniCtx, 'rgba(245, 158, 11, 0.4)', 'rgba(245, 158, 11, 0.0)');

            const miniChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { display: false },
                    y: { display: false, min: 0, max: 100 }
                },
                elements: {
                    point: { radius: 0 },
                    line: { borderWidth: 2 }
                }
            };

            const cpuMiniChart = new Chart(cpuMiniCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        data: cpuData,
                        borderColor: '#4f46e5',
                        backgroundColor: miniCpuGradient,
                        fill: true
                    }]
                },
                options: miniChartOptions
            });

            const memMiniChart = new Chart(memMiniCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        data: memData,
                        borderColor: '#0ea5e9',
                        backgroundColor: miniMemGradient,
                        fill: true
                    }]
                },
                options: miniChartOptions
            });

            const timeMiniChart = new Chart(timeMiniCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        data: timeData,
                        borderColor: '#f59e0b',
                        backgroundColor: miniTimeGradient,
                        fill: true
                    }]
                },
                options: {
                    ...miniChartOptions,
                    scales: {
                        x: { display: false },
                        y: { display: false, min: 0, max: 500 }
                    }
                }
            });

            // Setup detailed Full Tab charts
            const cpuFullCtx = document.getElementById('cpuFullChart').getContext('2d');
            const memFullCtx = document.getElementById('memFullChart').getContext('2d');
            const timeFullCtx = document.getElementById('timeFullChart').getContext('2d');

            const fullCpuGradient = createGradient(cpuFullCtx, 'rgba(79, 70, 229, 0.25)', 'rgba(79, 70, 229, 0.0)');
            const fullMemGradient = createGradient(memFullCtx, 'rgba(14, 165, 233, 0.25)', 'rgba(14, 165, 233, 0.0)');
            const fullTimeGradient = createGradient(timeFullCtx, 'rgba(245, 158, 11, 0.25)', 'rgba(245, 158, 11, 0.0)');

            const fullChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { color: 'rgba(156, 163, 175, 0.1)' },
                        ticks: { color: '#9ca3af', font: { family: 'monospace', size: 10 } }
                    },
                    y: {
                        grid: { color: 'rgba(156, 163, 175, 0.1)' },
                        ticks: { color: '#9ca3af', font: { family: 'monospace', size: 10 } },
                        min: 0,
                        max: 100
                    }
                },
                elements: {
                    point: { radius: 3, hoverRadius: 5 },
                    line: { borderWidth: 2, tension: 0.3 }
                }
            };

            const cpuFullChart = new Chart(cpuFullCtx, {
                type: 'line',
                data: {
                    labels: [...labels],
                    datasets: [{
                        data: [...cpuData],
                        borderColor: '#4f46e5',
                        backgroundColor: fullCpuGradient,
                        fill: true
                    }]
                },
                options: fullChartOptions
            });

            const memFullChart = new Chart(memFullCtx, {
                type: 'line',
                data: {
                    labels: [...labels],
                    datasets: [{
                        data: [...memData],
                        borderColor: '#0ea5e9',
                        backgroundColor: fullMemGradient,
                        fill: true
                    }]
                },
                options: fullChartOptions
            });

            const timeFullChart = new Chart(timeFullCtx, {
                type: 'line',
                data: {
                    labels: [...labels],
                    datasets: [{
                        data: [...timeData],
                        borderColor: '#f59e0b',
                        backgroundColor: fullTimeGradient,
                        fill: true
                    }]
                },
                options: {
                    ...fullChartOptions,
                    scales: {
                        ...fullChartOptions.scales,
                        y: {
                            ...fullChartOptions.scales.y,
                            max: 500
                        }
                    }
                }
            });

            // Set initial dynamic values in overview
            if (cpuData.length > 0) {
                document.getElementById('live-cpu-val').innerText = cpuData[cpuData.length - 1] + '%';
                document.getElementById('live-mem-val').innerText = memData[memData.length - 1] + '%';
                document.getElementById('live-time-val').innerText = timeData[timeData.length - 1] + 'ms';
            }

            // Polling interval every 3 seconds
            setInterval(function () {
                fetch('{{ route('api.monitoring.metrics') }}')
                    .then(response => response.json())
                    .then(data => {
                        // Update text values
                        document.getElementById('live-cpu-val').innerText = data.cpu + '%';
                        document.getElementById('live-mem-val').innerText = data.memory + '%';
                        document.getElementById('live-time-val').innerText = data.response_time + 'ms';

                        // Push new data points and pop old ones to scrolling effect
                        [cpuMiniChart, memMiniChart, timeMiniChart, cpuFullChart, memFullChart, timeFullChart].forEach((chart, index) => {
                            let value;
                            if (index === 0 || index === 3) value = data.cpu;
                            else if (index === 1 || index === 4) value = data.memory;
                            else value = data.response_time;

                            chart.data.labels.push(data.timestamp);
                            chart.data.datasets[0].data.push(value);

                            if (chart.data.labels.length > 15) {
                                chart.data.labels.shift();
                                chart.data.datasets[0].data.shift();
                            }

                            chart.update('none'); // Update without full recalculation lag
                        });
                    })
                    .catch(error => console.error('Failed fetching real-time monitoring diagnostics:', error));
            }, 3000);

            // Handle custom URL tab state initializations
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab');
            if (activeTab === 'logs') {
                // Alpine x-data bindings handles this, but since we set tab via query string to make persisted search clean,
                // we trigger a simulated tab click
                setTimeout(() => {
                    const logsButton = document.querySelector('[x-data] button[active-tab-trigger]');
                    if (logsButton) logsButton.click();
                }, 100);
            }
        });
    </script>
</x-app-layout>
