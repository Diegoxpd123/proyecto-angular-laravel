<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveBookRequest;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookController extends Controller
{

       //
    public function index(): JsonResponse
    {
        $books = Book::latest()->get();

        return response()->json($books, Response::HTTP_OK);

    }
    //
public function store(SaveBookRequest $request): JsonResponse
{
    $data = $request->validated();

    if (isset($data['image']) && $this->isBase64($data['image'])) {
        $data['image'] = $this->saveBase64Image($data['image']);
    }

    $book = Book::create($data);

    return response()->json($book, Response::HTTP_CREATED);
}

/**
 * Verifica si una cadena es una imagen Base64 válida
 */
private function isBase64(string $base64): bool
{
    return preg_match('/^data:image\/(\w+);base64,/', $base64) === 1;
}

/**
 * Guarda la imagen Base64 en disco y retorna la ruta relativa
 */
private function saveBase64Image(string $base64Image): string
{
    // Extraer tipo y contenido
    preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type);
    $imageType = $type[1]; // ej: png, jpg, jpeg, gif

    $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
    $imageData = base64_decode($imageData);

    // Generar nombre único para la imagen
    $imageName = Str::random(20) . '.' . $imageType;

// Ruta física del archivo en el sistema de archivos
    $directory = public_path('images/books'); // => C:\...\trabajo-api\public\images\books
    if (!file_exists($directory)) {
        mkdir($directory, 0755, true); // Crea la carpeta si no existe
    }

    $filePath = $directory . '/' . $imageName;
    file_put_contents($filePath, $imageData);

    // Guardar en carpeta public/assets/image
    $path = 'http://localhost:8000/images/books/' . $imageName;

    return $path; // ruta relativa para guardar en BD (puedes ajustar si quieres URL completa)
}

    //
    public function show(string $id): JsonResponse
    {
        $book = Book::findOrFail($id);

        return response()->json($book, Response::HTTP_OK);

    }
    //
    public function update(SaveBookRequest $request, string $id): JsonResponse
    {

        $book = Book::findOrFail($id);

         $data = $request->validated();

    if (isset($data['image']) && $this->isBase64($data['image'])) {
        $data['image'] = $this->saveBase64Image($data['image']);
    }

     $book->update($data);


    return response()->json($book, Response::HTTP_OK);
    }
     //
    public function destroy(string $id): JsonResponse
    {

        $book = Book::findOrFail($id);

        $book->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);

    }

}
