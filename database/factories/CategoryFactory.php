<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Elektronik', 
                'Peralatan Kantor', 
                'Bahan Bangunan', 
                'Alat Olahraga', 
                'Kesehatan', 
                'Otomotif', 
                'Pakaian', 
                'Makanan & Minuman',
                'Furnitur',
                'Alat Tulis Kantor'
            ]),
            'description' => $this->faker->sentence(),
        ];
    }
}
