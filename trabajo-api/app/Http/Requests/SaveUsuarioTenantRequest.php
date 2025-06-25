<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveUsuarioTenantRequest extends FormRequest
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
            'usuarioid' => 'required|string',
            'tenantid' => 'required|string',
            'is_active' => 'required',
            'is_deleted' => 'required',
            'created_at' => 'required|string',
            'updated_at' => 'required|string'
        ];
    }
}
