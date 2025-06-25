<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categoriaid' => 'nullable|exists:categories,id',
            'name'        => 'required|string|max:100',
            'image1'      => 'nullable|string|max:250',
            'image2'      => 'nullable|string|max:250',
            'image3'      => 'nullable|string|max:250',
        ];
    }
}
