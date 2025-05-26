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
        $user = $this->route('user'); // Obtiene el modelo User pasado en la ruta (o null en create)
        
        // Si estamos creando (no hay user), o el usuario no tiene contraseña (vacía o null),
        // entonces password es requerido, si no, es opcional.
        $passwordRule = 'nullable|string|min:8|confirmed';
        if (!$user || empty($user->password)) {
            $passwordRule = 'required|string|min:8|confirmed';
        }

        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => $passwordRule,
        ];
    }

}
