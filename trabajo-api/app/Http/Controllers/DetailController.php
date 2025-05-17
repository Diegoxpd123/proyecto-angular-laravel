<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveDetailRequest;
use App\Models\Detail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DetailController extends Controller
{
    //
    public function index(): JsonResponse
    {
        $details = Detail::latest()->get();

        return response()->json($details, Response::HTTP_OK);

    }
    //
    public function store(SaveDetailRequest $request): JsonResponse
    {

        $detail = Detail::create($request->validated());

        return response()->json($detail, Response::HTTP_CREATED);

    }
    //
    public function show(string $id): JsonResponse
    {
        $detail = Detail::findOrFail($id);

        return response()->json($detail, Response::HTTP_OK);

    }
    //
    public function update(SaveDetailRequest $request, string $id): JsonResponse
    {

        $detail = Detail::findOrFail($id);

        $detail->update($request->validated());

        return response()->json($detail, Response::HTTP_OK);

    }
     //
    public function destroy(string $id): JsonResponse
    {

        $detail = Detail::findOrFail($id);

        $detail->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);

    }



}
