<?php

namespace Database\Factories;

use App\Models\PlanVenta;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanVentaFactory extends Factory
{
    protected $model = PlanVenta::class;

    public function definition(): array
    {
        return [
            'nombre'            => $this->faker->word(),
            'descripcion'       => $this->faker->sentence(),
            'precio'            => $this->faker->randomFloat(2, 10, 100),
            'dias_prueba'       => $this->faker->numberBetween(7, 30),
            'modulos'           => implode(',', $this->faker->randomElements(['ventas', 'inventario', 'reportes'], 2)),
            'limite_productos'  => $this->faker->numberBetween(50, 1000),
        ];
    }
}
