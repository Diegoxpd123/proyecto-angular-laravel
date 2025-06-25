<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;
use Tenancy\Environment;

class TenantsMigrate extends Command
{
    protected $signature = 'tenants:migrate';
    protected $description = 'Crear BD y migrar para todos los tenants';

    public function handle()
    {
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {
            $this->migrateTenant($tenant);
        }

        $this->info("Todas las migraciones completadas.");
    }

    protected function migrateTenant(Tenant $tenant)
    {
        $this->info("Migrando tenant: {$tenant->name}");

        $dbName = $tenant->database;

        // 1. Crear la base de datos si no existe
        DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName`");

        // 2. Configurar conexión dinámica
        config([
            'database.connections.tenant' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => $dbName,
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
            ],
        ]);

        // 3. Forzar reconexión a la nueva base de datos
        DB::purge('tenant');
        DB::setDefaultConnection('tenant');
        DB::reconnect('tenant');

        // 4. Ejecutar migraciones específicas del tenant
        Artisan::call('migrate', [
            '--path' => 'database/migrations/tenant',
            '--database' => 'tenant',
            '--force' => true,
        ]);

        $this->info("Migración completada para: {$tenant->name}");
    }
}
