<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            // Validación de la contraseña
            'password' => $this->isMethod('post') 
                ? 'required|string|min:8|confirmed' // Obligatorio en creación
                : 'nullable|string|min:8|confirmed', // Opcional en edición
        ];
    }
}
