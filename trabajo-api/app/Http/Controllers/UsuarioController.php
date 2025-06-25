<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUsuarioRequest;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UsuarioController extends Controller
{
    //
    public function index(): JsonResponse
    {

        $usuarios = Usuario::latest()->get();

        return response()->json($usuarios, Response::HTTP_OK);
    }
    //
    public function store(SaveUsuarioRequest $request): JsonResponse
    {

        $usuario = Usuario::create($request->validated());

        return response()->json($usuario, Response::HTTP_CREATED);
    }
    //
    public function show(string $id): JsonResponse
    {
        $usuario = Usuario::findOrFail($id);

        return response()->json($usuario, Response::HTTP_OK);
    }
    //
    public function update(SaveUsuarioRequest $request, string $id): JsonResponse
    {

        $usuario = Usuario::findOrFail($id);

        $usuario->update($request->validated());

        return response()->json($usuario, Response::HTTP_OK);
    }
    //
    public function destroy(string $id): JsonResponse
    {

        $usuario = Usuario::findOrFail($id);

        $usuario->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
