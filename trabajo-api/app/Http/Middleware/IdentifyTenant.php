<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use Illuminate\Support\Facades\Log;


class IdentifyTenant
{
    public function handle(Request $request, Closure $next)
    {
        $tid = $request->header('tenant-id');

        if (!$tid) {
            return response()->json(['message' => 'No hay tenant en la sesión'], 400);
        }

        $tenant = Tenant::where('id', $tid)->first();

        if (!$tenant) {
            return response()->json(['message' => 'Tenant no válido'], 404);
        }

        $dbName = $tenant->database;


        Log::info('🌐 Middleware tenant.db ejecutado ' . $dbName);
        // Crear la base si no existe
        DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName`");

        // Configurar conexión dinámica
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

        DB::purge('tenant');
        DB::setDefaultConnection('tenant');
        DB::reconnect('tenant');

        return $next($request);
    }
}
