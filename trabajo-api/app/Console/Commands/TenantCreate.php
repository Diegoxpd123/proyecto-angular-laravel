<?php

namespace App\Console\Commands;

use App\Models\Genre;
use App\Models\Branch;
use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\Style;
use App\Models\Category;

class TenantCreate extends Command
{
    protected $signature = 'tenant:create
        {slug}
        {database}
        {tipo_documento}
        {nro_documento}
        {plan_ventaid}
        {razonsocial}
        {nombre_contacto}
        {logo}
        {direccion}
        {contacto}
        {telefono}
        {email}';

    protected $description = 'Crea un tenant con su base de datos y ejecuta migraciones';

    public function handle()
    {
        // Argumentos
        $database = $this->argument('database');
        $slug = $this->argument('slug');
        $tipo_documento = $this->argument('tipo_documento');
        $nro_documento = $this->argument('nro_documento');
        $plan_ventaid = $this->argument('plan_ventaid');
        $razonsocial = $this->argument('razonsocial');
        $nombre_contacto = $this->argument('nombre_contacto');
        $logo = $this->argument('logo');
        $direccion = $this->argument('direccion');
        $contacto = $this->argument('contacto');
        $telefono = $this->argument('telefono');
        $email = $this->argument('email');

        // Crear base de datos
        try {
            DB::statement("CREATE DATABASE `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->info("Base de datos '$database' creada.");
        } catch (\Exception $e) {
            $this->error("Error creando la base de datos: " . $e->getMessage());
            return 1;
        }

        // Crear tenant en base de datos central
        $tenant = Tenant::create([
            'tipo_documento'    => $tipo_documento,
            'nro_documento'     => $nro_documento,
            'plan_ventaid'      => $plan_ventaid,
            'razonsocial'       => $razonsocial,
            'nombre_contacto'   => $nombre_contacto,
            'logo'              => $logo,
            'slug'              => $slug,
            'direccion'         => $direccion,
            'contacto'          => $contacto,
            'telefono'          => $telefono,
            'email'             => $email,
        ]);

        // Configuración de conexión dinámica para migrar
        config([
            'database.connections.tenant' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => $database,
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ],
        ]);

        DB::purge('tenant');
        DB::setDefaultConnection('tenant');
        DB::reconnect('tenant');


        Artisan::call('migrate', [
            '--path' => 'database/migrations/tenant',
            '--database' => 'tenant',
            '--force' => true,
        ]);

        // Géneros (fijos)
        $genres = ['Moda Infantil', 'Hombre', 'Mujer'];
        foreach ($genres as $genre) {
            Genre::create([
                'name' => $genre,
                'descripcion' => fake()->sentence(),
            ]);
        }

        // Sucursales (marcas conocidas o simuladas)
        $branches = [
            'Zara',
            'H&M',
            'Uniqlo',
            'Nike',
            'Adidas',
            'Levi\'s',
            'Pull&Bear',
            'Bershka',
            'Puma',
            'Reebok',
            'Guess',
            'Stradivarius',
            'Mango',
            'Old Navy',
            'GAP',
            'Columbia',
            'Under Armour',
            'Lacoste',
            'Tommy Hilfiger',
            'Forever 21'
        ];
        foreach ($branches as $branch) {
            Branch::create([
                'name' => $branch,
                'descripcion' => fake()->catchPhrase(),
            ]);
        }

        // Estilos (relacionados con Genre)
        $genreMap = Genre::pluck('id', 'name'); // ['Moda Infantil' => 1, ...]

        $stylesMap = [
            'Moda Infantil' => ['Ropa Niña', 'Ropa Niño', 'Ropa Bebé', 'Calzado Infantil'],
            'Hombre' => ['Ropa Casual Hombre', 'Ropa Formal Hombre', 'Ropa Interior Hombre', 'Calzado Hombre'],
            'Mujer' => ['Ropa Casual Mujer', 'Ropa Interior Mujer', 'Ropa Formal Mujer', 'Calzado Mujer'],
        ];

        foreach ($stylesMap as $genreName => $styles) {
            foreach ($styles as $style) {
                Style::create([
                    'name' => $style,
                    'descripcion' => fake()->sentence(),
                    'genreid' => $genreMap[$genreName],
                ]);
            }
        }

        // Categorías (por estilo)
        $styleMap = Style::pluck('id', 'name');

        $categoriesMap = [
            'Ropa Casual Hombre' => [
                'Polos',
                'Casacas y Chalecos',
                'Poleras y Polerones',
                'Jeans',
                'Chompas',
                'Camisas',
                'Pantalones',
                'Joggers',
                'Shorts',
                'Ropa de Baño'
            ],
            'Ropa Formal Hombre' => [
                'Camisas de Vestir',
                'Pantalones de Vestir',
                'Blazers y Sacos',
                'Ternos',
                'Accesorios'
            ],
            'Ropa Interior Hombre' => ['Bóxers', 'Pijamas', 'Medias'],
            'Calzado Hombre' => ['Zapatillas', 'Zapatos de vestir', 'Botines'],

            'Ropa Casual Mujer' => ['Blusas', 'Faldas', 'Pantalones', 'Vestidos', 'Jeans'],
            'Ropa Interior Mujer' => ['Sostenes', 'Pijamas', 'Panties'],
            'Ropa Formal Mujer' => ['Vestidos de noche', 'Blazers', 'Faldas formales'],
            'Calzado Mujer' => ['Tacones', 'Sandalias', 'Botas'],

            'Ropa Niña' => ['Vestidos', 'Faldas', 'Leggings'],
            'Ropa Niño' => ['Pantalones', 'Polos', 'Chompas'],
            'Ropa Bebé' => ['Enterizos', 'Bodies', 'Gorros'],
            'Calzado Infantil' => ['Zapatillas', 'Botitas', 'Sandalias'],
        ];

        foreach ($categoriesMap as $styleName => $categories) {
            foreach ($categories as $category) {
                Category::create([
                    'name' => $category,
                    'descripcion' => fake()->sentence(),
                    'styleid' => $styleMap[$styleName] ?? null,
                ]);
            }
        }

        $this->info("Tenant '{$razonsocial}' creado con éxito y base de datos lista.");

        return 0;
    }
}
