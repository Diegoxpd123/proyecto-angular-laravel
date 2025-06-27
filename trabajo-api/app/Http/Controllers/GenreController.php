<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Http\Requests\GenreRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
class GenreController extends Controller
{
    public function index()
    {
        $this->conexion();
        return Genre::with('styles')->get();
    }

    public function store(GenreRequest $request)
    {
        $this->conexion();
        $genre = Genre::create($request->validated());
        return response()->json($genre, 201);
    }

    public function show($id)
    {
        $this->conexion();
        $genre = Genre::with('styles')->findOrFail($id);
        return response()->json($genre);
    }

    public function update(GenreRequest $request, $id)
    {
        $this->conexion();
        $genre = Genre::findOrFail($id);
        $genre->update($request->validated());
        return response()->json($genre);
    }

    public function destroy($id)
    {
        $this->conexion();
        $genre = Genre::findOrFail($id);
        $genre->delete();
        return response()->json(['message' => 'Género eliminado correctamente']);
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
