<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cambia a false si quieres manejar autorizaciones específicas
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name',
            'permission' => 'nullable|array',
            'permission.*' => 'exists:permissions,id',
        ];
    }
}
