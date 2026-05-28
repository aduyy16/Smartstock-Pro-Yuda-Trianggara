<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Jakarta Central Logistics Hub',
                'Surabaya Industrial Distribution',
                'Medan Northern Gateway',
                'Bandung Highlands Warehouse',
                'Makassar Eastern Gateway',
                'Semarang Port Supply Hub',
                'Yogyakarta Cultural Hub',
                'Denpasar Island Logistics'
            ]),
            'location' => $this->faker->address(),
            'latitude' => $this->faker->latitude(-8.2, -5.8),
            'longitude' => $this->faker->longitude(106.2, 113.8),
        ];
    }
}
