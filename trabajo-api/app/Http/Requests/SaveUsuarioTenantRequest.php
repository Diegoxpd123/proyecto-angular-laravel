<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveUsuarioTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Puedes implementar lógica de autorización si es necesario
    }

    public function rules(): array
    {
        return [
            'tenant_id' => 'required|exists:tenants,id',
            'user_id'   => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'tenant_id.required' => 'El campo tenant_id es obligatorio.',
            'tenant_id.exists'   => 'El tenant especificado no existe.',
            'user_id.required'   => 'El campo user_id es obligatorio.',
            'user_id.exists'     => 'El usuario especificado no existe.',
        ];
    }
}
