<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permite ejecutar la solicitud
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:250',
            'descripcion' => 'nullable|string|max:500',
        ];
    }
}
