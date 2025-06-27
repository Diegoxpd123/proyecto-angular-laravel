<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Category;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $this->conexion();
        return Product::with('category', 'detalles')->get();
    }

    public function store(ProductRequest $request)
    {
        $this->conexion();

        // Validar que la categoría exista
        if (!Category::on('tenant')->where('id', $request->categoriaid)->exists()) {
            return response()->json(['message' => 'La categoría no existe en este tenant.'], 404);
        }

        // Crear producto
        $product = (new Product())->setConnection('tenant')->create($request->validated());

        return response()->json([
            'message' => 'Producto creado correctamente.',
            'data' => $product
        ], 201);
    }

    public function show($id)
    {
        $this->conexion();
        $product = Product::with('category', 'detalles')->findOrFail($id);
        return response()->json($product);
    }

    public function update(ProductRequest $request, $id)
    {
        $this->conexion();

        // Validar que la categoría exista
        if (!Category::on('tenant')->where('id', $request->categoriaid)->exists()) {
            return response()->json(['message' => 'La categoría no existe en este tenant.'], 404);
        }

        $product = Product::on('tenant')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $product->update($request->validated());

        return response()->json([
            'message' => 'Producto actualizado correctamente.',
            'data' => $product
        ]);
    }

    public function destroy($id)
    {
        $this->conexion();
        $product = Product::on('tenant')->findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Producto eliminado correctamente.']);
    }

    private function conexion()
    {
        $tid = request()->header('X-Tenant-ID');

        if (!$tid) {
            abort(400, 'No hay tenant en la sesión');
        }

        $tenant = \App\Models\Tenant::where('id', $tid)->first();

        if (!$tenant) {
            abort(404, 'Tenant no válido');
        }

        $dbName = $tenant->slug;

        DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName`");

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
