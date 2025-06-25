<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use App\Http\Requests\ProductDetailRequest;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    public function index()
    {
        return ProductDetail::with('product')->get();
    }

    public function store(ProductDetailRequest $request)
    {
        $detail = ProductDetail::create($request->validated());
        return response()->json($detail, 201);
    }

    public function show($id)
    {
        $detail = ProductDetail::with('product')->findOrFail($id);
        return response()->json($detail);
    }

    public function update(ProductDetailRequest $request, $id)
    {
        $detail = ProductDetail::findOrFail($id);
        $detail->update($request->validated());
        return response()->json($detail);
    }

    public function destroy($id)
    {
        $detail = ProductDetail::findOrFail($id);
        $detail->delete();
        return response()->json(['message' => 'Detalle de producto eliminado correctamente.']);
    }
}
