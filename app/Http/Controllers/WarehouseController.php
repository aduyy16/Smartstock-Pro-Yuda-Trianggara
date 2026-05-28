<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    /**
     * Display a listing of warehouses with map integrations.
     */
    public function index(): View
    {
        // 1. Auto-seed realistic Indonesia warehouses if empty for instant visual wow
        if (Warehouse::count() === 0) {
            Warehouse::create([
                'name' => 'Jakarta Central Logistics Hub',
                'location' => 'Jl. Industri Raya No.12, Kemayoran, Jakarta Pusat, DKI Jakarta',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
            ]);

            Warehouse::create([
                'name' => 'Surabaya Industrial Distribution',
                'location' => 'Jl. Margomulyo Indah No.8, Tandes, Surabaya, Jawa Timur',
                'latitude' => -7.2575,
                'longitude' => 112.7521,
            ]);

            Warehouse::create([
                'name' => 'Medan Northern Gateway',
                'location' => 'Jl. K.L. Yos Sudarso KM 8.5, Medan Deli, Medan, Sumatera Utara',
                'latitude' => 3.5952,
                'longitude' => 98.6722,
            ]);
        }

        $warehouses = Warehouse::orderBy('name')->get();

        return view('warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new warehouse.
     */
    public function create(): View
    {
        return view('warehouses.create');
    }

    /**
     * Store a newly created warehouse in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255', // address
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        Warehouse::create($validated);

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse created and mapped successfully.');
    }

    /**
     * Show the form for editing the specified warehouse.
     */
    public function edit(Warehouse $warehouse): View
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified warehouse in storage.
     */
    public function update(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255', // address
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $warehouse->update($validated);

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse updated and re-mapped successfully.');
    }

    /**
     * Remove the specified warehouse from storage.
     */
    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        // Check if warehouse has active stock before deletion (optional safety)
        $warehouse->delete();

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse removed successfully.');
    }
}
