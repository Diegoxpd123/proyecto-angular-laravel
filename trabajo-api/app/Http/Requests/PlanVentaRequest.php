<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanVentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'           => 'required|string|max:255',
            'descripcion'      => 'nullable|string|max:500',
            'precio'           => 'required|numeric|min:0',
            'dias_prueba'      => 'nullable|integer|min:0',
            'modulos'          => 'nullable|string|max:255',
            'limite_productos' => 'nullable|integer|min:0',
        ];
    }
}
