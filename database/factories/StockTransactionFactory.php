<?php

namespace Database\Factories;

use App\Models\StockTransaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockTransactionFactory extends Factory
{
    protected $model = StockTransaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(['IN', 'OUT']),
            'quantity' => $this->faker->numberBetween(1, 40),
            'note' => $this->faker->randomElement([
                'Restock mingguan dari supplier utama.',
                'Pengeluaran stok untuk penjualan ritel.',
                'Stok masuk penggantian produk cacat.',
                'Penyesuaian stok berkala hasil stock opname.',
                'Mutasi stok internal gudang.',
                'Pengiriman pesanan e-commerce.'
            ]),
        ];
    }
}
