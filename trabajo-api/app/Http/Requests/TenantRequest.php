<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_documento'  => 'required|string|max:50',
            'nro_documento'   => 'required|string|max:20|unique:tenants,nro_documento,' . $this->id,
            'plan_ventaid'    => 'required|exists:plan_ventas,id',
            'razonsocial'     => 'required|string|max:255',
            'nombre_contacto' => 'required|string|max:255',
            'logo'            => 'nullable|string|max:255',
            'slug'            => 'required|string|max:255|unique:tenants,slug,' . $this->id,
            'direccion'       => 'required|string|max:255',
            'contacto'        => 'nullable|string|max:100',
            'telefono'        => 'required|string|max:20',
            'email'           => 'required|email|max:100',
        ];
    }
}
