<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           'cliente_id' => 'required',
            'voucher_type' => 'required|string',
            'voucher_number' => 'required|string',
            'voucher_pdf' => 'required|string',
            'created_at' => 'required',
            'updated_at' => 'required',
            'is_deleted' => 'required',
            'is_actived' => 'required'
        ];
    }
}
