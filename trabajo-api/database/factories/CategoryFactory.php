<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Style;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $style = Style::inRandomOrder()->first() ?? Style::factory()->create();

        $categoriesByStyle = [
            // Hombre
            'Ropa casual hombre' => [
                'Polos', 'Casacas y chalecos', 'Poleras y polerones', 'Jeans',
                'Chompas', 'Camisas', 'Pantalones', 'Joggers', 'Shorts', 'Ropa de baño'
            ],
            'Ropa formal hombre' => [
                'Camisas de vestir', 'Pantalones de vestir', 'Blazers y sacos',
                'Ternos', 'Accesorios'
            ],
            'Ropa interior hombre' => [
                'Bóxers y calzoncillos', 'Pijamas', 'Medias'
            ],
            'Calzado hombre' => [
                'Zapatillas', 'Zapatos de vestir', 'Sandalias'
            ],

            // Mujer
            'Ropa casual mujer' => [
                'Blusas', 'Crop tops', 'Faldas', 'Pantalones', 'Vestidos casuales'
            ],
            'Ropa interior mujer' => [
                'Brassieres', 'Panties', 'Lencería', 'Pijamas'
            ],
            'Ropa formal mujer' => [
                'Vestidos de noche', 'Blazers', 'Conjuntos elegantes', 'Faldas de vestir'
            ],
            'Calzado mujer' => [
                'Tacones', 'Zapatillas', 'Botines', 'Sandalias'
            ],

            // Moda Infantil
            'Ropa niña' => [
                'Vestidos', 'Poleras', 'Faldas', 'Pantalones', 'Casacas'
            ],
            'Ropa niño' => [
                'Polos', 'Shorts', 'Pantalones', 'Chompas', 'Casacas'
            ],
            'Ropa bebé' => [
                'Enterizos', 'Bodys', 'Conjuntos', 'Pijamas'
            ],
            'Calzado' => [
                'Zapatitos bebé', 'Zapatillas niño', 'Sandalias niña'
            ],
        ];

        $styleName = $style->name;
        $nameOptions = $categoriesByStyle[$styleName] ?? [$this->faker->word()];
        $name = $this->faker->unique()->randomElement($nameOptions);

        return [
            'name' => $name,
            'descripcion' => $this->faker->sentence(),
            'styleid' => $style->id,
        ];
    }
}
