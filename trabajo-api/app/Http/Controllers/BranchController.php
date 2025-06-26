<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Http\Requests\BranchRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    public function index()
    {

        $this->conexion();
        return Branch::all();
    }

    public function store(BranchRequest $request)
    {

        $this->conexion();
        $branch = Branch::create($request->validated());
        return response()->json($branch, 201);
    }

    public function show($id)
    {

        $this->conexion();
        $branch = Branch::findOrFail($id);
        return response()->json($branch);
    }

    public function update(BranchRequest $request, $id)
    {

        $this->conexion();
        $branch = Branch::findOrFail($id);
        $branch->update($request->validated());
        return response()->json($branch);
    }

    public function destroy($id)
    {

        $this->conexion();
        $branch = Branch::findOrFail($id);
        $branch->delete();
        return response()->json(['message' => 'Sucursal eliminada']);
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
