<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;
use Tenancy\Facades\Tenancy;

class TenantCreate extends Command
{
    protected $signature = 'tenant:create {name} {database}';
    protected $description = 'Crea un tenant con su base de datos y ejecuta migraciones';

    public function handle()
    {
        $name = $this->argument('name');
        $database = $this->argument('database');

        // Crear base de datos (puedes agregar validaciones si ya existe)
        try {
            DB::statement("CREATE DATABASE `$database`");
            $this->info("Base de datos '$database' creada.");
        } catch (\Exception $e) {
            $this->error("Error creando la base de datos: " . $e->getMessage());
            return;
        }

        // Crear el tenant
        $tenant = Tenant::create([
            'tid' => uniqid(),
            'name' => $name,
            'database' => $database,
        ]);

        // Inicializar y correr migraciones
        Tenancy::initialize($tenant);
        Artisan::call('tenants:migrate', [], $this->getOutput());
        Tenancy::end();

        $this->info("Tenant '$name' creado con éxito.");
    }
}
