<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         // Ruta real a las imágenes (ajustar según tu estructura)
        $imageDir = public_path('images/books');
        $imageFiles = collect(glob($imageDir . '/*.{jpg,png,jpeg}', GLOB_BRACE))
            ->map(function ($path) {
                return 'http://localhost:8000/images/books/' . basename($path); // Ruta relativa para la web
            });

        return [

            'isbn' => Str::random(13),
            'name' => fake()->name(),
            'stock' => fake()->randomFloat(0,20,100),
            'price' => fake()->randomFloat(2,100,200),
            'image' => $imageFiles->random(),
            'created_at' => now(),
            'updated_at' => now(),
            'is_deleted' => 0,
            'is_actived' => 1,
        ];
    }
}
