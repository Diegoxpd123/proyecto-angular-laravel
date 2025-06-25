<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_documento' => 'required|string|max:50',
            'numero_documento' => 'required|string|max:20|unique:clientes,numero_documento,' . $this->id,
            'nombre' => 'required|string|max:50',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:60',
            'direccion' => 'nullable|string|max:100',
        ];
    }
}
