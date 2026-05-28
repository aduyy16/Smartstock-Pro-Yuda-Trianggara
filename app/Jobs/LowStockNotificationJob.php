<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LowStockNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Get all products that have low stock
            $lowStockProducts = Product::whereColumn('stock', '<=', 'minimum_stock')->get();

            if ($lowStockProducts->isEmpty()) {
                Log::info('LowStockNotificationJob: No products found with low stock.');
                return;
            }

            // Get users to notify (Admins and Warehouse Managers)
            $usersToNotify = User::role(['Admin', 'Manager Gudang'])->get();
            if ($usersToNotify->isEmpty()) {
                $usersToNotify = User::all();
            }

            foreach ($lowStockProducts as $product) {
                foreach ($usersToNotify as $user) {
                    // Send database notification background-safely
                    $user->notify(new LowStockNotification($product));
                }
            }

            Log::info('LowStockNotificationJob: Processed low stock notifications for ' . $lowStockProducts->count() . ' products.');
        } catch (\Exception $e) {
            Log::error('Error processing LowStockNotificationJob: ' . $e->getMessage());
        }
    }
}
