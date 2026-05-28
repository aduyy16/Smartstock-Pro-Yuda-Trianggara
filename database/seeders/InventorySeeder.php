<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\WarehouseStock;
use App\Models\StockTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed 8 unique categories
        $categories = Category::factory()->count(8)->create();

        // 2. Seed 10 suppliers
        $suppliers = Supplier::factory()->count(10)->create();

        // 3. Seed 5 warehouses
        $warehouses = Warehouse::factory()->count(5)->create();

        // Ensure we have at least one normal user for transaction causers
        if (User::count() === 0) {
            $user = User::create([
                'name' => 'Demo Operator',
                'email' => 'operator@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]);
            $user->assignRole('Staff Gudang');
        }
        $users = User::all();

        // 4. Seed 25 High-Stock Products
        $highStockProducts = Product::factory()
            ->count(25)
            ->sequence(fn ($seq) => [
                'category_id' => $categories->random()->id,
                'supplier_id' => $suppliers->random()->id,
                'stock' => rand(60, 250),
                'minimum_stock' => rand(10, 20),
            ])
            ->create();

        // 5. Seed 10 Low-Stock Products (to trigger alert metrics)
        $lowStockProducts = Product::factory()
            ->count(10)
            ->sequence(fn ($seq) => [
                'category_id' => $categories->random()->id,
                'supplier_id' => $suppliers->random()->id,
                'stock' => rand(1, 5),
                'minimum_stock' => rand(10, 15),
            ])
            ->create();

        $allProducts = Product::all();

        // 6. Seed Warehouse Stock Pivot Allocations (connect products stock to warehouses)
        foreach ($allProducts as $product) {
            $assignedWarehouses = $warehouses->random(rand(2, 3));
            $remainingStock = $product->stock;

            foreach ($assignedWarehouses as $index => $warehouse) {
                // Ensure all stock is fully distributed
                if ($index === $assignedWarehouses->count() - 1) {
                    $stockAlloc = $remainingStock;
                } else {
                    $stockAlloc = rand(1, max(1, floor($remainingStock / 2)));
                }

                $remainingStock -= $stockAlloc;

                if ($stockAlloc > 0) {
                    WarehouseStock::create([
                        'warehouse_id' => $warehouse->id,
                        'product_id' => $product->id,
                        'stock' => $stockAlloc,
                    ]);
                }
            }
        }

        // 7. Seed exactly 100 random transactions linked to products and users
        for ($i = 0; $i < 100; $i++) {
            $product = $allProducts->random();
            StockTransaction::factory()->create([
                'product_id' => $product->id,
                'user_id' => $users->random()->id,
                'type' => rand(0, 1) ? 'IN' : 'OUT',
                'quantity' => rand(2, 12),
            ]);
        }
    }
}
