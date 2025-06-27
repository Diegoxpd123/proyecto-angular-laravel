<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    /**
     * Sube una imagen a Cloudflare R2 y devuelve el nombre del archivo.
     */
    public function uploadImage(Request $request)
    {
        // Validación del archivo
        $request->validate([
            'file' => 'required|image|max:5120', // máximo 5MB
        ]);

        // Obtener el archivo
        $file = $request->file('file');

        // Generar un nombre único (timestamp + slug + extensión)
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = Str::slug($originalName);
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '-' . $safeName . '.' . $extension;

        // Guardar en Cloudflare R2 (disco 'r2' debe estar configurado)
        $path = $file->storeAs('products', $fileName, 'r2');

        // Devolver solo el nombre del archivo, como se guardará en la BD
        return response()->json([
            'fileName' => $fileName,
            'path' => $path,
            'message' => 'Imagen subida correctamente.',
        ], 201);
    }
}
