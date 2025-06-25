<?php

namespace App\Http\Controllers;

use App\Models\Style;
use App\Http\Requests\StyleRequest;
use Illuminate\Http\Request;

class StyleController extends Controller
{
    public function index()
    {
        return response()->json(Style::all());
    }

    public function store(StyleRequest $request)
    {
        $style = Style::create($request->validated());

        return response()->json([
            'message' => 'Estilo creado correctamente.',
            'data'    => $style,
        ]);
    }

    public function show($id)
    {
        $style = Style::find($id);

        if (!$style) {
            return response()->json(['message' => 'Estilo no encontrado'], 404);
        }

        return response()->json($style);
    }

    public function update(StyleRequest $request, $id)
    {
        $style = Style::find($id);

        if (!$style) {
            return response()->json(['message' => 'Estilo no encontrado'], 404);
        }

        $style->update($request->validated());

        return response()->json([
            'message' => 'Estilo actualizado correctamente.',
            'data'    => $style,
        ]);
    }

    public function destroy($id)
    {
        $style = Style::find($id);

        if (!$style) {
            return response()->json(['message' => 'Estilo no encontrado'], 404);
        }

        $style->delete();

        return response()->json(['message' => 'Estilo eliminado correctamente.']);
    }

    public function restore($id)
    {
        $style = Style::withTrashed()->find($id);

        if (!$style) {
            return response()->json(['message' => 'Estilo no encontrado para restaurar'], 404);
        }

        $style->restore();

        return response()->json(['message' => 'Estilo restaurado correctamente.']);
    }
}
