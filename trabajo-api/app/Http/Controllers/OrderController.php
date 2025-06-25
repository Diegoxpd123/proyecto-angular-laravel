<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveOrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    //
    public function index(): JsonResponse
    {
        $orders = Order::latest()->get();

        return response()->json($orders, Response::HTTP_OK);
    }
    //
    public function store(SaveOrderRequest $request): JsonResponse
    {


        $order = Order::create($request->validated());
        $order->save();


        return response()->json($order, Response::HTTP_CREATED);
    }
    //
    public function show(string $id): JsonResponse
    {
        $order = Order::findOrFail($id);

        return response()->json($order, Response::HTTP_OK);
    }
    //
    public function update(SaveOrderRequest $request, string $id): JsonResponse
    {

        $order = Order::findOrFail($id);



        $order->update($request->validated());
        $order->save();


        return response()->json($order, Response::HTTP_OK);
    }
    //
    public function destroy(string $id): JsonResponse
    {

        $order = Order::findOrFail($id);

        $order->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
