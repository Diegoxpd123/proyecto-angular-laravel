<?php

namespace Database\Factories;

use App\Models\Style;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

class StyleFactory extends Factory
{
    protected $model = Style::class;

    public function definition(): array
    {
        // Creamos un género (moda infantil, hombre o mujer)
        $genre = Genre::inRandomOrder()->first() ?? Genre::factory()->create();

        // Definimos los estilos posibles por género
        $stylesByGenre = [
            'Moda Infantil' => ['Ropa niña', 'Ropa niño', 'Ropa bebé', 'Calzado'],
            'Hombre' => ['Ropa casual hombre', 'Ropa formal hombre', 'Ropa interior hombre', 'Calzado hombre'],
            'Mujer' => ['Ropa casual mujer', 'Ropa interior mujer', 'Ropa formal mujer', 'Calzado mujer'],
        ];

        $genreName = $genre->name;

        // Si no está en los estilos permitidos, ponemos una palabra genérica
        $nameOptions = $stylesByGenre[$genreName] ?? [$this->faker->word()];
        $name = $this->faker->unique()->randomElement($nameOptions);

        return [
            'name' => $name,
            'descripcion' => $this->faker->sentence(),
            'genreid' => $genre->id,
        ];
    }
}
