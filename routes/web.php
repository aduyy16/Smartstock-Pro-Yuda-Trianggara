<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Products Routes: Read-only for Viewer, Write for Admin and Manager Gudang
    Route::middleware('role:Admin|Manager Gudang|Viewer')->group(function () {
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
    });
    Route::middleware('role:Admin|Manager Gudang')->group(function () {
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // Transactions Routes: Read-only for Viewer, Write for Admin, Manager, and Staff Gudang
    Route::middleware('role:Admin|Manager Gudang|Staff Gudang|Viewer')->group(function () {
        Route::get('transactions', [StockTransactionController::class, 'index'])->name('transactions.index');
    });
    Route::middleware('role:Admin|Manager Gudang|Staff Gudang')->group(function () {
        Route::get('transactions/create', [StockTransactionController::class, 'create'])->name('transactions.create');
        Route::post('transactions', [StockTransactionController::class, 'store'])->name('transactions.store');
    });

    // Notification Routes
    Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Warehouse Geographic Routes
    Route::middleware('role:Admin|Manager Gudang|Staff Gudang|Viewer')->group(function () {
        Route::get('warehouses', [\App\Http\Controllers\WarehouseController::class, 'index'])->name('warehouses.index');
    });
    Route::middleware('role:Admin|Manager Gudang')->group(function () {
        Route::get('warehouses/create', [\App\Http\Controllers\WarehouseController::class, 'create'])->name('warehouses.create');
        Route::post('warehouses', [\App\Http\Controllers\WarehouseController::class, 'store'])->name('warehouses.store');
        Route::get('warehouses/{warehouse}/edit', [\App\Http\Controllers\WarehouseController::class, 'edit'])->name('warehouses.edit');
        Route::put('warehouses/{warehouse}', [\App\Http\Controllers\WarehouseController::class, 'update'])->name('warehouses.update');
        Route::delete('warehouses/{warehouse}', [\App\Http\Controllers\WarehouseController::class, 'destroy'])->name('warehouses.destroy');
    });

    // Monitoring Dashboard (Restricted to Admin)
    Route::middleware('role:Admin')->group(function () {
        Route::get('monitoring', [\App\Http\Controllers\MonitoringController::class, 'index'])->name('monitoring.index');
        Route::get('api/monitoring/metrics', [\App\Http\Controllers\MonitoringController::class, 'apiMetrics'])->name('api.monitoring.metrics');
        Route::post('monitoring/generate', [\App\Http\Controllers\MonitoringController::class, 'generateDummy'])->name('monitoring.generate');
        Route::post('monitoring/simulate', [\App\Http\Controllers\MonitoringController::class, 'storeSimulated'])->name('monitoring.simulate');
    });

    // Background Queue triggers
    Route::post('queue/generate-report', [\App\Http\Controllers\QueueTriggerController::class, 'generateReport'])->name('queue.generate-report');
    Route::post('queue/low-stock', [\App\Http\Controllers\QueueTriggerController::class, 'runLowStockCheck'])->name('queue.low-stock');
});

require __DIR__.'/auth.php';
