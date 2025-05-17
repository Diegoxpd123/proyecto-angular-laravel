<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveClienteRequest;
use App\Models\Cliente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class ClienteController extends Controller
{
    //
    public function index(): JsonResponse
    {
        $clientes = Cliente::latest()->get();

        return response()->json($clientes, Response::HTTP_OK);

    }
    //
    public function store(SaveClienteRequest $request): JsonResponse
    {

        $cliente = Cliente::create($request->validated());

        return response()->json($cliente, Response::HTTP_CREATED);

    }
    //
    public function show(string $id): JsonResponse
    {
        $cliente = Cliente::findOrFail($id);

        return response()->json($cliente, Response::HTTP_OK);

    }
    //
    public function update(SaveClienteRequest $request, string $id): JsonResponse
    {

        $cliente = Cliente::findOrFail($id);

        $cliente->update($request->validated());

        return response()->json($cliente, Response::HTTP_OK);

    }
     //
    public function destroy(string $id): JsonResponse
    {

        $cliente = Cliente::findOrFail($id);

        $cliente->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);

    }



}
