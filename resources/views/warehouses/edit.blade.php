<x-app-layout>
    <!-- Leaflet Assets -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Warehouse Hub: ') }} {{ $warehouse->name }}
            </h2>
            <a href="{{ route('warehouses.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                &larr; Back to Map
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden p-6 lg:p-8">
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column: Form Details -->
                    <div>
                        <h3 class="text-md font-bold text-gray-900 dark:text-white mb-6">Warehouse Hub Specifications</h3>
                        
                        <form id="warehouse-form" action="{{ route('warehouses.update', $warehouse) }}" method="POST" class="space-y-5">
                            @csrf
                            @method('PUT')

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Warehouse Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $warehouse->name) }}" 
                                       class="block w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm" required>
                                @error('name')
                                    <p class="text-xs text-red-600 dark:text-red-400 mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address/Location -->
                            <div>
                                <label for="location" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Location Address</label>
                                <input type="text" name="location" id="location" value="{{ old('location', $warehouse->location) }}" 
                                       class="block w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm" required>
                                @error('location')
                                    <p class="text-xs text-red-600 dark:text-red-400 mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Coordinates (Latitude & Longitude) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="latitude" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Latitude Coordinate</label>
                                    <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $warehouse->latitude ?? -6.2088) }}" 
                                           class="block w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm font-mono" required>
                                    @error('latitude')
                                        <p class="text-xs text-red-600 dark:text-red-400 mt-1 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="longitude" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Longitude Coordinate</label>
                                    <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $warehouse->longitude ?? 106.8456) }}" 
                                           class="block w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm font-mono" required>
                                    @error('longitude')
                                        <p class="text-xs text-red-600 dark:text-red-400 mt-1 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Actions Buttons -->
                            <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end space-x-3">
                                <a href="{{ route('warehouses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-lg shadow-sm transition">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Right Column: Interactive Map Preview -->
                    <div>
                        <div class="mb-4">
                            <h3 class="text-md font-bold text-gray-900 dark:text-white">Geographic Map Selector</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Drag the marker or click anywhere on the map to auto-fill latitude and longitude coordinates.</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900 p-2 rounded-2xl border border-gray-100 dark:border-gray-800">
                            <div id="preview-map" class="w-full rounded-xl" style="height: 360px; z-index: 1;"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Map Preview synchronization script -->
    <script>
        let map;
        let marker;

        document.addEventListener('DOMContentLoaded', function () {
            // Get initial inputs
            const latInput = document.getElementById('latitude');
            const lonInput = document.getElementById('longitude');

            let lat = parseFloat(latInput.value) || -6.2088;
            let lon = parseFloat(lonInput.value) || 106.8456;

            // 1. Initialize Map centered on active coordinates
            map = L.map('preview-map').setView([lat, lon], 12);

            // 2. Attach standard tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // 3. Create single draggable marker representing warehouse location
            marker = L.marker([lat, lon], {
                draggable: true
            }).addTo(map);

            // 4. Marker drag end event handler (updates form inputs)
            marker.on('dragend', function (event) {
                const position = marker.getLatLng();
                latInput.value = position.lat.toFixed(6);
                lonInput.value = position.lng.toFixed(6);
            });

            // 5. Map click event handler (updates marker position and inputs)
            map.on('click', function (event) {
                const clickedCoords = event.latlng;
                
                marker.setLatLng(clickedCoords);
                latInput.value = clickedCoords.lat.toFixed(6);
                lonInput.value = clickedCoords.lng.toFixed(6);

                map.panTo(clickedCoords);
            });

            // 6. Manual inputs change handlers (repositions marker on map and pans)
            function updateMarkerFromInputs() {
                const typedLat = parseFloat(latInput.value);
                const typedLon = parseFloat(lonInput.value);

                if (!isNaN(typedLat) && !isNaN(typedLon)) {
                    const newPosition = [typedLat, typedLon];
                    marker.setLatLng(newPosition);
                    map.panTo(newPosition);
                }
            }

            latInput.addEventListener('input', updateMarkerFromInputs);
            lonInput.addEventListener('input', updateMarkerFromInputs);
        });
    </script>
</x-app-layout>
