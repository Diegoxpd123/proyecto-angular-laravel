<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'clienteid' => 'required|exists:clientes,id',
            'total'     => 'required|numeric|min:0',
        ];
    }
}
