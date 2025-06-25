<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'productoid'     => 'required|exists:products,id',
            'talla'          => 'required|string|max:100',
            'stock'          => 'required|integer|min:0',
            'cantidadmayor'  => 'nullable|integer|min:0',
            'precio'         => 'required|numeric|min:0',
            'preciomayor'    => 'nullable|numeric|min:0',
            'preciosale'     => 'nullable|numeric|min:0',
            'issale'         => 'required|boolean',
        ];
    }
}
