<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveDetailRequest extends FormRequest
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
            'order_id' => 'required',
            'book_id' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'created_at' => 'required|string',
            'updated_at' => 'required|string',
            'is_deleted' => 'required',
            'is_actived' => 'required'
        ];
    }
}
