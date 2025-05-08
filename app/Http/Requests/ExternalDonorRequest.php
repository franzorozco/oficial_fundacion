<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExternalDonorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'names' => 'required|string|max:100',
            'paternal_surname' => 'nullable|string|max:100',
            'maternal_surname' => 'nullable|string|max:100',

            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'names.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo debe ser válido.',
            'email.unique' => 'Este correo ya está registrado.',
        ];
    }
}