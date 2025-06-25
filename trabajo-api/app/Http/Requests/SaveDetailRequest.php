<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'orderid'  => 'required|exists:orders,id',
            'name'     => 'required|string|max:100',
            'image1'   => 'nullable|string|max:250',
            'image2'   => 'nullable|string|max:250',
            'image3'   => 'nullable|string|max:250',
            'talla'    => 'required|string|max:100',
            'precio'   => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:1',
        ];
    }
}
