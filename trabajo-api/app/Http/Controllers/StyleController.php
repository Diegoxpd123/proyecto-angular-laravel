<?php

namespace App\Http\Controllers;

use App\Models\Style;
use App\Http\Requests\StyleRequest;
use App\Models\Genre;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class StyleController extends Controller
{
    public function index()
    {
        $this->conexion();
        return Style::with('genre')->get();
    }

    public function store(StyleRequest $request)
    {
        $this->conexion();

        // Verificar manualmente si el genre existe en el tenant
        if (!Genre::on('tenant')->where('id', $request->genreid)->exists()) {
            return response()->json(['message' => 'El género no existe en este tenant.'], 404);
        }

        $style = (new Style())->setConnection('tenant')->create($request->validated());

        return response()->json([
            'message' => 'Estilo creado correctamente.',
            'data'    => $style,
        ]);
    }

    public function show($id)
    {
        $this->conexion();
        $style = Style::find($id);

        if (!$style) {
            return response()->json(['message' => 'Estilo no encontrado'], 404);
        }

        return response()->json($style);
    }

    public function update(StyleRequest $request, $id)
    {
        $this->conexion();

        // Validar que el género exista en la base de datos del tenant
        if (!\App\Models\Genre::on('tenant')->where('id', $request->genreid)->exists()) {
            return response()->json(['message' => 'El género no existe en este tenant.'], 404);
        }

        // Buscar el estilo en la conexión del tenant
        $style = \App\Models\Style::on('tenant')->find($id);

        if (!$style) {
            return response()->json(['message' => 'Estilo no encontrado'], 404);
        }

        $style->update($request->validated());

        return response()->json([
            'message' => 'Estilo actualizado correctamente.',
            'data'    => $style,
        ]);
    }


    public function destroy($id)
    {
        $this->conexion();
        $style = Style::find($id);

        if (!$style) {
            return response()->json(['message' => 'Estilo no encontrado'], 404);
        }

        $style->delete();

        return response()->json(['message' => 'Estilo eliminado correctamente.']);
    }

    public function restore($id)
    {
        $this->conexion();
        $style = Style::withTrashed()->find($id);

        if (!$style) {
            return response()->json(['message' => 'Estilo no encontrado para restaurar'], 404);
        }

        $style->restore();

        return response()->json(['message' => 'Estilo restaurado correctamente.']);
    }

    public function conexion()
    {
        $tid = request()->header('X-Tenant-ID');

        if (!$tid) {
            return response()->json(['message' => 'No hay tenant en la sesión ' . $tid], 400);
        }

        $tenant = \App\Models\Tenant::where('id', $tid)->first();

        if (!$tenant) {
            return response()->json(['message' => 'Tenant no válido'], 404);
        }

        $dbName = $tenant->slug;

        // Crear si no existe
        DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName`");

        // Configurar conexión
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
    }
}
