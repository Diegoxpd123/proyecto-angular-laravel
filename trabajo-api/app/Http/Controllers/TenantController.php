<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Session;

class TenantController extends Controller
{
    public function setTenant(Request $request)
    {
        $tenantId = $request->input('tenant_id');

        $tenant = Tenant::where('tid', $tenantId)->first();

        if (!$tenant) {
            return response()->json(['message' => 'Tenant no encontrado'], 404);
        }

        // Guardamos el tenant en sesión
        session(['tenant' => $tenant]);

        return response()->json(['message' => 'Tenant seleccionado', 'tenant' => $tenant]);
    }

    public function getTenants()
    {
        return Tenant::all(); // para llenar el dropdown
    }
}
