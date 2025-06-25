<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StyleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Aquí podrías aplicar lógica de autorización
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:250',
            'descripcion' => 'nullable|string|max:500',
            'genreid'     => 'required|exists:genres,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'El nombre es obligatorio.',
            'name.max'             => 'El nombre no debe superar los 250 caracteres.',
            'descripcion.max'      => 'La descripción no debe superar los 500 caracteres.',
            'genreid.required'     => 'El género es obligatorio.',
            'genreid.exists'       => 'El género especificado no existe.',
        ];
    }
}
