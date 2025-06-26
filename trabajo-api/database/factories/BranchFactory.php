<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition(): array
    {
        $brands = [
            'Zara',
            'H&M',
            'Uniqlo',
            'Bershka',
            'Pull&Bear',
            'Stradivarius',
            'Levi\'s',
            'Nike',
            'Adidas',
            'Puma',
            'Reebok',
            'Tommy Hilfiger',
            'Calvin Klein',
            'Mango',
            'Guess',
            'Lacoste',
            'Forever 21',
            'Shein',
            'Ovs',
            'Old Navy',
        ];

        return [
            'name' => $this->faker->unique()->randomElement($brands),
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
