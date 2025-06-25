<?php

namespace App\Http\Controllers;

use App\Models\PlanVenta;
use App\Http\Requests\PlanVentaRequest;
use Illuminate\Http\Request;

class PlanVentaController extends Controller
{
    public function index()
    {
        return PlanVenta::all();
    }

    public function store(PlanVentaRequest $request)
    {
        $plan = PlanVenta::create($request->validated());
        return response()->json($plan, 201);
    }

    public function show($id)
    {
        $plan = PlanVenta::findOrFail($id);
        return response()->json($plan);
    }

    public function update(PlanVentaRequest $request, $id)
    {
        $plan = PlanVenta::findOrFail($id);
        $plan->update($request->validated());
        return response()->json($plan);
    }

    public function destroy($id)
    {
        $plan = PlanVenta::findOrFail($id);
        $plan->delete();
        return response()->json(['message' => 'Plan eliminado correctamente.']);
    }
}
