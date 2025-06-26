<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Cliente;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\PlanVenta;
use App\Models\User;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
 // Crear 2 planes de venta
        PlanVenta::factory()->count(2)->create();

        // Crear 3 usuarios
        User::factory()->count(3)->create();
    }
}
