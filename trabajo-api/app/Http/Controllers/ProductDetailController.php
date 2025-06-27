<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use App\Http\Requests\ProductDetailRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductDetailController extends Controller
{
    public function index()
    {
        $this->conexion();
        return ProductDetail::with('product')->get();
    }

    public function store(ProductDetailRequest $request)
    {
        $this->conexion();

        // Validar que el producto exista en el tenant
        if (!\App\Models\Product::on('tenant')->where('id', $request->productoid)->exists()) {
            return response()->json(['message' => 'El producto no existe en este tenant.'], 404);
        }

        $detail = (new ProductDetail())->setConnection('tenant')->create($request->validated());

        return response()->json($detail, 201);
    }

    public function show($id)
    {
        $this->conexion();
        $detail = ProductDetail::with('product')->findOrFail($id);
        return response()->json($detail);
    }

    public function update(ProductDetailRequest $request, $id)
    {
        $this->conexion();

        $detail = ProductDetail::on('tenant')->find($id);

        if (!$detail) {
            return response()->json(['message' => 'Detalle no encontrado'], 404);
        }

        // Validar que el producto relacionado exista
        if (!\App\Models\Product::on('tenant')->where('id', $request->productoid)->exists()) {
            return response()->json(['message' => 'El producto no existe en este tenant.'], 404);
        }

        $detail->update($request->validated());

        return response()->json($detail);
    }

    public function destroy($id)
    {
        $this->conexion();

        $detail = ProductDetail::on('tenant')->find($id);

        if (!$detail) {
            return response()->json(['message' => 'Detalle no encontrado'], 404);
        }

        $detail->delete();

        return response()->json(['message' => 'Detalle de producto eliminado correctamente.']);
    }

    public function conexion()
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
                'driver'   => 'mysql',
                'host'     => env('DB_HOST', '127.0.0.1'),
                'port'     => env('DB_PORT', '3306'),
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
