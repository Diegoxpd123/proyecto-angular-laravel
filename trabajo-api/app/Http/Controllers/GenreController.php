<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Http\Requests\GenreRequest;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index()
    {
        return Genre::with('styles')->get();
    }

    public function store(GenreRequest $request)
    {
        $genre = Genre::create($request->validated());
        return response()->json($genre, 201);
    }

    public function show($id)
    {
        $genre = Genre::with('styles')->findOrFail($id);
        return response()->json($genre);
    }

    public function update(GenreRequest $request, $id)
    {
        $genre = Genre::findOrFail($id);
        $genre->update($request->validated());
        return response()->json($genre);
    }

    public function destroy($id)
    {
        $genre = Genre::findOrFail($id);
        $genre->delete();
        return response()->json(['message' => 'Género eliminado correctamente']);
    }
}
