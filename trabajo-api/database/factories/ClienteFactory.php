<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doc_type' => fake()->randomFloat(0,1,3),
            'doc_number' => fake()->randomFloat(0,01000000,99999999),
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'phone' => fake()->randomFloat(0,900000000,999999999),
            'email' => fake()->unique()->safeEmail(),
            'is_active' => 1,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
