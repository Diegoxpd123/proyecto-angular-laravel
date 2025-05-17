<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveBookRequest;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        $book = Book::create($request->validated());

        return response()->json($book, Response::HTTP_CREATED);

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

        $book->update($request->validated());

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
