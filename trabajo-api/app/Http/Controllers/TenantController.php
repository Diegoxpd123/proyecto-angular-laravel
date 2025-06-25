<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Http\Requests\TenantRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\Facades\JWTAuth;

class TenantController extends Controller
{
    /**
     * Obtener todos los tenants para un dropdown.
     */
    public function getTenants()
    {
        return response()->json(Tenant::select('id', 'razonsocial', 'slug')->get());
    }

    /**
     * Guardar el tenant seleccionado en sesión.
     */
    public function setTenant(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        $tenant = Tenant::find($request->tenant_id);

        session(['tenant' => $tenant]);

        return response()->json([
            'message' => 'Tenant seleccionado correctamente.',
            'tenant'  => $tenant,
        ]);
    }

    /**
     * Obtener el tenant actualmente en sesión.
     */
    public function getCurrentTenant()
    {
        $tenant = session('tenant');

        if (!$tenant) {
            return response()->json(['message' => 'No hay tenant seleccionado'], 404);
        }

        return response()->json(['tenant' => $tenant]);
    }

    public function displayImage($id)
    {

        $tenant = Tenant::find($id);
        $file = 'noimage.png';

        if (! is_null($tenant->logo)  && (file_exists(storage_path('app/tenant/')  . $tenant->logo))) {

            $file = storage_path('app/tenant/') . $tenant->logo;
        }


        return response()->file($file);
    }

    private function isBase64(string $base64): bool
    {
        return preg_match('/^data:image\/(\w+);base64,/', $base64) === 1;
    }

    public function show($id)
    {
        $tenant = Tenant::find($id);

        if (!$tenant) {
            return response()->json(['message' => 'Tenant no encontrado'], 404);
        }

        return response()->json($tenant);
    }


    public function store(TenantRequest $request)
    {
        try {
            $request->validated();

            $logo = null;
            if ($this->isBase64($request->logo)) {
                $logo = $this->saveBase64Image($request->logo, $request->slug);
            }

            Artisan::call('tenant:create', [
                'database'           => $request->slug,
                'tipo_documento'     => $request->tipo_documento,
                'nro_documento'      => $request->nro_documento,
                'plan_ventaid'       => $request->plan_ventaid,
                'razonsocial'        => $request->razonsocial,
                'nombre_contacto'    => $request->nombre_contacto,
                'logo'               => $logo,
                'industria_id'       => $request->industria_id,
                'paisid'             => $request->paisid,
                'regionid'           => $request->regionid,
                'tamanio_equipo_id'  => $request->tamanio_equipo_id,
                'telefono'           => $request->telefono,
                'email'              => $request->email,
                'direccion'          => $request->direccion,
                'contacto'           => $request->contacto ?? null,
            ]);

            return response()->json(['message' => 'Tenant creado con éxito']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el tenant.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }



    public function update(TenantRequest $request, $id)
    {
        try {
            $tenant = Tenant::find($id);

            if (!$tenant) {
                return response()->json(['message' => 'Tenant no encontrado'], 404);
            }

            $logo = $tenant->logo;

            // Procesar nuevo logo si viene como base64
            if (isset($request->logo) && $this->isBase64($request->logo)) {
                $logo = $this->saveBase64Image($request->logo, $tenant->slug);
            }

            $tenant->update([
                'tipo_documento'       => $request->tipo_documento,
                'nro_documento'        => $request->nro_documento,
                'plan_ventaid'         => $request->plan_ventaid,
                'nombre_contacto'      => $request->nombre_contacto,
                'razonsocial'          => $request->razonsocial,
                'logo'                 => $logo,
                'industria_id'         => $request->industria_id,
                'tamanio_equipo_id'    => $request->tamanio_equipo_id,
                'paisid'               => $request->paisid,
                'regionid'             => $request->regionid,
                'direccion'            => $request->direccion,
                'telefono'             => $request->telefono,
                'email'                => $request->email,
                'contacto'             => $request->contacto,
                // 'slug' no se actualiza
            ]);

            return response()->json([
                'message' => 'Tenant actualizado con éxito'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el tenant.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->delete(); // esto no elimina, solo marca deleted_at

        return response()->json(['message' => 'Tenant eliminado correctamente.']);
    }

    public function restore($id)
    {
        $tenant = Tenant::withTrashed()->find($id);
        $tenant->restore();

        return response()->json(['message' => 'Tenant restaurado.']);
    }


    private function saveBase64Image(string $base64Image, string $idmedio): string
    {
        // Extraer tipo y contenido
        preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type);
        $imageType = $type[1]; // ej: png, jpg, jpeg, gif

        $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
        $imageData = base64_decode($imageData);

        // Generar nombre único para la imagen
        $imageName = $idmedio . '_' . Str::random(8) . '.' . $imageType;

        // Ruta física del archivo en el sistema de archivos
        $directory = storage_path('app/tenant/'); // => C:\...\trabajo-api\public\images\books
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true); // Crea la carpeta si no existe
        }

        $filePath = $directory . '/' . $imageName;
        file_put_contents($filePath, $imageData);

        // Guardar en carpeta public/assets/image
        $path = $imageName;

        return $path; // ruta relativa para guardar en BD (puedes ajustar si quieres URL completa)
    }
}
