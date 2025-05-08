<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;  // Cambiar según tus necesidades de autorización
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'description' => 'required|max:500',
        ];
    }


    /**
     * Get the custom messages for the validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder los 100 caracteres.',
            'name.unique' => 'Ya existe un tipo de donación con este nombre.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
        ];
    }
}
