<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

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

        // Ejecutar migraciones (descomenta si ya tienes migraciones para el tenant)
        // Artisan::call('migrate', [
        //     '--path' => 'database/migrations/tenant',
        //     '--database' => 'tenant',
        //     '--force' => true,
        // ]);

        $this->info("Tenant '{$razonsocial}' creado con éxito y base de datos lista.");

        return 0;
    }
}
