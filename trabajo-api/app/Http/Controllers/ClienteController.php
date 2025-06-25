<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveClienteRequest;
use App\Models\Cliente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Tenant;



class ClienteController extends Controller
{


    //
    public function index(): JsonResponse
    {
        $this->conexion();

        $clientes = \App\Models\Cliente::on('tenant')->latest()->get();
        return response()->json($clientes, \Illuminate\Http\Response::HTTP_OK);
    }
    //
    public function store(SaveClienteRequest $request): JsonResponse
    {


        $this->conexion();

        $cliente = \App\Models\Cliente::on('tenant')->create($request->validated());

        return response()->json($cliente, Response::HTTP_CREATED);
    }
    //
    public function show(string $id): JsonResponse
    {
        $this->conexion();

        $cliente = \App\Models\Cliente::on('tenant')->findOrFail($id);;

        return response()->json($cliente, Response::HTTP_OK);
    }
    //
    public function update(SaveClienteRequest $request, string $id): JsonResponse
    {

        $this->conexion();

        $cliente = \App\Models\Cliente::on('tenant')->findOrFail($id);
        $cliente->update($request->validated());

        return response()->json($cliente, Response::HTTP_OK);
    }
    //
    public function destroy(string $id): JsonResponse
    {
        $this->conexion();
        $cliente = \App\Models\Cliente::on('tenant')->findOrFail($id);
        $cliente->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function conexion()
    {
        $tid = request()->header('tenant-id');

        if (!$tid) {
            return response()->json(['message' => 'No hay tenant en la sesión ' . $tid], 400);
        }

        $tenant = \App\Models\Tenant::where('tid', $tid)->first();

        if (!$tenant) {
            return response()->json(['message' => 'Tenant no válido'], 404);
        }

        $dbName = $tenant->database;

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
