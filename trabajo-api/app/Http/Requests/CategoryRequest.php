<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:250',
            'descripcion' => 'nullable|string|max:500',
            'styleid' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'El nombre es obligatorio.',
            'name.max'             => 'El nombre no debe superar los 250 caracteres.',
            'descripcion.max'      => 'La descripción no debe superar los 500 caracteres.',
            'styleid.required'     => 'El género es obligatorio.',
        ];
    }
}
