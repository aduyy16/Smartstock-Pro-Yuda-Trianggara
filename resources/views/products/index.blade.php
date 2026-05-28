<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Products Inventory') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Manage and track your products, warehouse stocks, and alerts.</p>
            </div>
            <div class="flex items-center space-x-3">
                @hasanyrole('Admin|Manager Gudang')
                <a href="{{ route('products.create') }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                    + Add New Product
                </a>
                @endhasanyrole
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Flash Message Success -->
            @if(session('success'))
                <div class="mb-6 p-4 text-sm text-green-700 bg-green-100 dark:bg-green-900/30 dark:text-green-400 rounded-lg shadow-sm flex items-center" role="alert">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Advanced Search, Filter & Sorting Panel -->
                    <div class="mb-8 bg-gray-50 dark:bg-gray-900/40 p-6 rounded-xl border border-gray-100 dark:border-gray-850">
                        <form id="filter-form" action="{{ route('products.index') }}" method="GET" class="space-y-4">
                            <!-- Top row: Search & Sorting -->
                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                                <div class="lg:col-span-3 relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </span>
                                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari berdasarkan nama produk, SKU, atau kategori..." 
                                           class="pl-10 block w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
                                </div>
                                <div>
                                    <select name="sort" class="block w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
                                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Terlama</option>
                                        <option value="stock_highest" {{ $sort === 'stock_highest' ? 'selected' : '' }}>Stok Tertinggi</option>
                                        <option value="stock_lowest" {{ $sort === 'stock_lowest' ? 'selected' : '' }}>Stok Terendah</option>
                                        <option value="price_highest" {{ $sort === 'price_highest' ? 'selected' : '' }}>Harga Tertinggi</option>
                                        <option value="price_lowest" {{ $sort === 'price_lowest' ? 'selected' : '' }}>Harga Terendah</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Bottom row: Dropdown Filters & Toggles -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                                <!-- Category filter -->
                                <div>
                                    <select name="category_id" class="block w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
                                        <option value="">Semua Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Supplier filter -->
                                <div>
                                    <select name="supplier_id" class="block w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
                                        <option value="">Semua Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ $supplierId == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Warehouse filter -->
                                <div>
                                    <select name="warehouse_id" class="block w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm">
                                        <option value="">Semua Warehouse</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ $warehouseId == $warehouse->id ? 'selected' : '' }}>
                                                {{ $warehouse->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Low stock checkbox toggle & Buttons -->
                                <div class="flex items-center justify-between gap-4 md:col-span-1">
                                    <label class="inline-flex items-center text-xs font-semibold text-gray-700 dark:text-gray-300 cursor-pointer">
                                        <input type="checkbox" name="low_stock" value="1" {{ $lowStock == '1' ? 'checked' : '' }}
                                               class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-2">
                                        Stok Rendah Alert
                                    </label>
                                    
                                    <div class="flex items-center space-x-2">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                            Filter
                                        </button>
                                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-lg shadow-sm transition">
                                            Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Products Table Container -->
                    <div class="relative overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        
                        <!-- Simple Loading Indicator Overlay -->
                        <div id="loading-indicator" class="hidden absolute inset-0 bg-white/50 dark:bg-gray-900/50 backdrop-blur-xs flex items-center justify-center z-50 rounded-lg">
                            <div class="flex items-center space-x-2 bg-white dark:bg-gray-800 px-4 py-3 rounded-lg shadow-md border border-gray-100 dark:border-gray-700">
                                <svg class="animate-spin h-5 w-5 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Memuat Katalog...</span>
                            </div>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr class="text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold">
                                    <th scope="col" class="px-6 py-4 text-left">SKU</th>
                                    <th scope="col" class="px-6 py-4 text-left">Product Name</th>
                                    <th scope="col" class="px-6 py-4 text-left">Category</th>
                                    <th scope="col" class="px-6 py-4 text-left">Supplier</th>
                                    <th scope="col" class="px-6 py-4 text-left">Price</th>
                                    <th scope="col" class="px-6 py-4 text-left">Total Stock</th>
                                    @hasanyrole('Admin|Manager Gudang')
                                    <th scope="col" class="px-6 py-4 text-right">Actions</th>
                                    @endhasanyrole
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                @forelse($products as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                        <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-gray-500 dark:text-gray-400">{{ $product->sku }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900 dark:text-white">{{ $product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->category->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $product->supplier->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product->stock <= $product->minimum_stock)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200/50 dark:border-red-800/30">
                                                    <span class="w-1.5 h-1.5 mr-1.5 bg-red-500 dark:bg-red-400 rounded-full animate-pulse"></span>
                                                    {{ $product->stock }} (Low Stock)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200/50 dark:border-green-800/30">
                                                    {{ $product->stock }}
                                                </span>
                                            @endif
                                        </td>
                                        @hasanyrole('Admin|Manager Gudang')
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('products.edit', $product) }}" 
                                                   class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-bold transition">
                                                    Edit
                                                </a>
                                                <span class="text-gray-300 dark:text-gray-600">|</span>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" 
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 font-bold transition">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        @endhasanyrole
                                    </tr>
                                @empty
                                    <tr>
                                        @hasanyrole('Admin|Manager Gudang')
                                        <td colspan="7" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                        @else
                                        <td colspan="6" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                        @endhasanyrole
                                            <div class="max-w-md mx-auto">
                                                <div class="p-4 bg-indigo-50 dark:bg-indigo-950/20 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 text-indigo-500 dark:text-indigo-400">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <h3 class="text-base font-extrabold text-gray-900 dark:text-white">Tidak Ada Produk Ditemukan</h3>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-6 leading-relaxed">
                                                    Kami tidak menemukan produk yang cocok dengan pencarian atau kriteria filter aktif Anda saat ini.
                                                </p>
                                                <a href="{{ route('products.index') }}" 
                                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                                    Reset Filter Pencarian
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script to auto-submit selectors and show dynamic loading overlay -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('filter-form');
            const loader = document.getElementById('loading-indicator');
            
            if (form && loader) {
                // Show loading spinner on manual form submit
                form.addEventListener('submit', function () {
                    loader.classList.remove('hidden');
                });
                
                // Show spinner and auto-submit form on dropdown select changes
                form.querySelectorAll('select').forEach(select => {
                    select.addEventListener('change', function () {
                        loader.classList.remove('hidden');
                        form.submit();
                    });
                });

                // Show spinner and auto-submit form on checkbox change
                const checkbox = form.querySelector('input[type="checkbox"]');
                if (checkbox) {
                    checkbox.addEventListener('change', function () {
                        loader.classList.remove('hidden');
                        form.submit();
                    });
                }
            }
        });
    </script>
</x-app-layout>
