<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUsuarioTenantRequest;
use App\Models\UsuarioTenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UsuarioTenantController extends Controller
{
    //
    public function index(): JsonResponse
    {
        $usuarios = UsuarioTenant::latest()->get();

        return response()->json($usuarios, Response::HTTP_OK);
    }
    //
    public function store(SaveUsuarioTenantRequest $request): JsonResponse
    {

        $usuario = UsuarioTenant::create($request->validated());

        return response()->json($usuario, Response::HTTP_CREATED);
    }
    //
    public function show(string $id): JsonResponse
    {
        $usuario = UsuarioTenant::findOrFail($id);

        return response()->json($usuario, Response::HTTP_OK);
    }
    //
    public function update(SaveUsuarioTenantRequest $request, string $id): JsonResponse
    {

        $usuario = UsuarioTenant::findOrFail($id);

        $usuario->update($request->validated());

        return response()->json($usuario, Response::HTTP_OK);
    }
    //
    public function destroy(string $id): JsonResponse
    {

        $usuario = UsuarioTenant::findOrFail($id);

        $usuario->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
