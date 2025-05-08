<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationItemRequest extends FormRequest
{
    public function authorize()
    {
        // Puedes personalizar esto según la lógica de autorización que desees
        return true;
    }

    public function rules()
    {
        return [
            'donation_id' => 'required|exists:donations,id',
            'donation_type_id' => 'required|exists:donation_types,id',
            'item_name' => 'required|string|max:150',
            'quantity' => 'nullable|integer|min:1',
            'unit' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'donation_id.required' => 'La donación asociada es obligatoria.',
            'donation_id.exists' => 'La donación especificada no existe.',
            'donation_type_id.required' => 'El tipo de donación es obligatorio.',
            'donation_type_id.exists' => 'El tipo de donación especificado no existe.',
            'item_name.required' => 'El nombre del ítem es obligatorio.',
            'item_name.max' => 'El nombre del ítem no debe exceder los 150 caracteres.',
            'quantity.integer' => 'La cantidad debe ser un número entero.',
            'quantity.min' => 'La cantidad mínima permitida es 1.',
            'unit.max' => 'La unidad no debe exceder los 50 caracteres.',
        ];
    }
}
