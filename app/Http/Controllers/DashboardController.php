<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        // 1. Statistics Cards
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $lowStockCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();

        // 2. Tables Data
        $latestProducts = Product::with(['category', 'supplier'])
            ->latest()
            ->take(5)
            ->get();

        $lowStockProducts = Product::with(['category', 'supplier'])
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->latest()
            ->take(5)
            ->get();

        // 3. Chart Data: Stock Transactions over the last 7 days
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(Carbon::now()->subDays($i)->format('Y-m-d'));
        }

        // Daily sum of quantities for IN/OUT stock transactions
        // Supports both MySQL and SQLite out of the box using standard DATE()
        $transactions = StockTransaction::selectRaw('DATE(created_at) as date, type, SUM(quantity) as total_qty')
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->groupBy('date', 'type')
            ->get();

        $chartLabels = [];
        $chartInData = [];
        $chartOutData = [];

        foreach ($dates as $date) {
            $chartLabels[] = Carbon::parse($date)->format('M d');
            
            $inVal = $transactions->where('date', $date)->where('type', 'IN')->first();
            $outVal = $transactions->where('date', $date)->where('type', 'OUT')->first();

            $chartInData[] = $inVal ? (int) $inVal->total_qty : 0;
            $chartOutData[] = $outVal ? (int) $outVal->total_qty : 0;
        }

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'lowStockCount',
            'latestProducts',
            'lowStockProducts',
            'chartLabels',
            'chartInData',
            'chartOutData'
        ));
    }
}
