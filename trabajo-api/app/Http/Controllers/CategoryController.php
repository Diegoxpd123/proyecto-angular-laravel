<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {

        $this->conexion();
        return Category::with('style')->get();
    }

    public function store(CategoryRequest $request)
    {
        $this->conexion();

        // Verificar que el género exista en la base de datos del tenant
        if (!\App\Models\Style::on('tenant')->where('id', $request->styleid)->exists()) {
            return response()->json(['message' => 'El Style no existe en este tenant.'], 404);
        }

        $category = (new \App\Models\Category())->setConnection('tenant')->create($request->validated());

        return response()->json([
            'message' => 'Categoría creada correctamente.',
            'data'    => $category,
        ], 201);
    }


    public function show($id)
    {

        $this->conexion();
        $category = Category::with('style', 'products')->findOrFail($id);
        return response()->json($category);
    }

    public function update(CategoryRequest $request, $id)
    {
        $this->conexion();

        // Validar que el género exista en la base de datos del tenant
        if (!\App\Models\Style::on('tenant')->where('id', $request->styleid)->exists()) {
            return response()->json(['message' => 'El Style no existe en este tenant.'], 404);
        }

        // Buscar la categoría en la conexión del tenant
        $category = \App\Models\Category::on('tenant')->find($id);

        if (!$category) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        $category->update($request->validated());

        return response()->json([
            'message' => 'Categoría actualizada correctamente.',
            'data'    => $category,
        ]);
    }


    public function destroy($id)
    {

        $this->conexion();
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Categoría eliminada correctamente']);
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
