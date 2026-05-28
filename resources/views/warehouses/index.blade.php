<x-app-layout>
    <!-- Leaflet JS & CSS Assets -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Warehouse Map Integration') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Geographic distribution, coordinates, and details of all global warehouses.</p>
            </div>
            @hasanyrole('Admin|Manager Gudang')
            <div class="flex items-center space-x-2">
                <a href="{{ route('warehouses.create') }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                    + Add New Warehouse
                </a>
            </div>
            @endhasanyrole
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="p-4 text-sm text-green-700 bg-green-100 dark:bg-green-900/30 dark:text-green-400 rounded-lg shadow-sm flex items-center" role="alert">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Map Layout Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Sidebar: Warehouses List (1/3 Width) -->
                <div class="lg:col-span-1 space-y-4 max-h-[550px] overflow-y-auto pr-2">
                    <h3 class="text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Registered Warehouses</h3>
                    
                    @forelse($warehouses as $warehouse)
                        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition flex flex-col justify-between"
                             id="warehouse-card-{{ $warehouse->id }}">
                            <div>
                                <div class="flex items-start justify-between gap-2">
                                    <h4 class="font-bold text-gray-900 dark:text-white text-sm leading-snug">{{ $warehouse->name }}</h4>
                                    <span class="inline-flex p-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">{{ $warehouse->location }}</p>
                                
                                @if($warehouse->latitude && $warehouse->longitude)
                                    <div class="flex items-center space-x-3 mt-3 text-xxs font-mono text-gray-400 dark:text-gray-500">
                                        <span>LAT: {{ number_format($warehouse->latitude, 5) }}</span>
                                        <span>LON: {{ number_format($warehouse->longitude, 5) }}</span>
                                    </div>
                                @else
                                    <p class="text-xxs text-amber-500 font-bold mt-2 flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        Missing Coords (No Map Marker)
                                    </p>
                                @endif
                            </div>

                            <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between gap-2">
                                @if($warehouse->latitude && $warehouse->longitude)
                                    <button onclick="focusOnWarehouse({{ $warehouse->id }}, {{ $warehouse->latitude }}, {{ $warehouse->longitude }})"
                                            class="inline-flex items-center text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                        Focus Map &rarr;
                                    </button>
                                @else
                                    <span class="text-xxs text-gray-400">Map Focus Unavailable</span>
                                @endif
                                
                                @hasanyrole('Admin|Manager Gudang')
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('warehouses.edit', $warehouse) }}" 
                                       class="text-xxs bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded font-bold transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus warehouse ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xxs bg-red-50 hover:bg-red-100 dark:bg-red-950/40 dark:hover:bg-red-950/80 text-red-600 px-2 py-1 rounded font-bold transition">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                                @endhasanyrole
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-750 text-gray-500">
                            No warehouses mapped.
                        </div>
                    @endforelse
                </div>

                <!-- Right Side: Leaflet OSM Map Canvas (2/3 Width) -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden relative">
                        <div id="map" class="w-full rounded-xl" style="height: 520px; z-index: 1;"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Map Leaflet OpenStreetMap Initialization script -->
    <script>
        let map;
        let markers = {};

        document.addEventListener('DOMContentLoaded', function () {
            // 1. Instantiate Leaflet Map centered on Indonesia coordinates with default overview zoom
            map = L.map('map').setView([-2.5489, 118.0149], 5);

            // 2. Attach premium OpenStreetMap tile layout layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // 3. Render markers for all warehouses mapped from database
            const warehouseList = @json($warehouses);
            let validCoords = [];

            warehouseList.forEach(function (warehouse) {
                if (warehouse.latitude && warehouse.longitude) {
                    const lat = parseFloat(warehouse.latitude);
                    const lon = parseFloat(warehouse.longitude);
                    validCoords.push([lat, lon]);

                    // Styled HTML popup content
                    const popupContent = `
                        <div class="p-2 text-gray-850 dark:text-gray-100 font-sans" style="min-width: 180px;">
                            <h4 class="font-extrabold text-sm mb-1 text-indigo-700 dark:text-indigo-400">${warehouse.name}</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-normal mb-2">${warehouse.location}</p>
                            <div class="flex items-center justify-between border-t pt-2 border-gray-100 mt-2">
                                <span class="text-xxs font-mono text-gray-400">LAT: ${lat.toFixed(4)}, LON: ${lon.toFixed(4)}</span>
                                @hasanyrole('Admin|Manager Gudang')
                                    <a href="/warehouses/${warehouse.id}/edit" class="text-xxs font-bold text-indigo-600 hover:underline">Edit Hub</a>
                                @endhasanyrole
                            </div>
                        </div>
                    `;

                    // Create leafleteer Marker and save reference
                    const marker = L.marker([lat, lon]).addTo(map).bindPopup(popupContent);
                    markers[warehouse.id] = marker;
                }
            });

            // 4. Autofit map bounds if there are valid coordinate markers on active nodes
            if (validCoords.length > 0) {
                const bounds = L.latLngBounds(validCoords);
                map.fitBounds(bounds, { padding: [50, 50] });
            }
        });

        /**
         * Panning & Focusing map dynamically onto selected warehouse marker node.
         */
        function focusOnWarehouse(id, lat, lon) {
            if (map && markers[id]) {
                // Focus camera to coordinates and zoom
                map.setView([parseFloat(lat), parseFloat(lon)], 13, {
                    animate: true,
                    duration: 1.5
                });

                // Highlight card sidebar visual effect
                document.querySelectorAll('[id^="warehouse-card-"]').forEach(card => {
                    card.classList.remove('border-indigo-500', 'ring-2', 'ring-indigo-500/20');
                });
                const selectedCard = document.getElementById(`warehouse-card-${id}`);
                if (selectedCard) {
                    selectedCard.classList.add('border-indigo-500', 'ring-2', 'ring-indigo-500/20');
                    selectedCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }

                // Wait for map pan animation to finish, then pop open marker details
                setTimeout(() => {
                    markers[id].openPopup();
                }, 1000);
            }
        }
    </script>
</x-app-layout>
