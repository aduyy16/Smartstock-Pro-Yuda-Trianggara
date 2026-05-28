<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'supplier_id' => Supplier::factory(),
            'name' => $this->faker->unique()->randomElement([
                'Asus ROG Zephyrus G14',
                'MacBook Pro M3 Max',
                'Samsung Odyssey G9 Monitor',
                'Mechanical Keyboard Keychron K2',
                'Logitech MX Master 3S Mouse',
                'Sony WH-1000XM5 Headphones',
                'Ergonomic Steelcase Office Chair',
                'Standing Desk Autonomous AI',
                'iPhone 15 Pro Max',
                'iPad Pro 12.9 inch',
                'Dell XPS 15 Laptop',
                'HP LaserJet Enterprise Printer',
                'Bose QuietComfort Earbuds',
                'Sony Alpha 7 IV Camera',
                'Canon EOS R5 Mirrorless',
                'Steelcase Gesture Task Chair',
                'Blue Yeti USB Microphone',
                'Elgato Stream Deck MK.2',
                'Razer DeathAdder V3 Pro',
                'WD Black 2TB NVMe SSD',
                'Corsair Vengeance 32GB RAM',
                'Intel Core i9-14900K CPU',
                'NVIDIA GeForce RTX 4090 GPU',
                'ASUS ROG Swift Gaming Monitor',
                'Sony PlayStation 5 Console',
                'Nintendo Switch OLED Model',
                'Oculus Quest 3 VR Headset',
                'DJI Mini 4 Pro Drone',
                'GoPro HERO12 Black Action Cam',
                'Kindle Paperwhite E-reader',
                'Apple Watch Ultra 2 Smartwatch',
                'Samsung Galaxy S24 Ultra Phone',
                'Anker Prime 20000mAh Powerbank',
                'Yeti Rambler 30oz Tumbler',
                'Aeropress Coffee Maker Go'
            ]),
            'sku' => strtoupper($this->faker->unique()->bothify('SKU-####-???')),
            'stock' => $this->faker->numberBetween(15, 120),
            'minimum_stock' => $this->faker->numberBetween(5, 15),
            'price' => $this->faker->randomFloat(0, 150000, 35000000), // Rupiah style float values
            'description' => $this->faker->paragraph(),
            'image' => null
        ];
    }
}
