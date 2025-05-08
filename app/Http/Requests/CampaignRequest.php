<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return true; // O agregar lógica personalizada si lo deseas
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [
            'creator_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }

    /**
     * Mensajes personalizados (opcional).
     */
    public function messages(): array
    {
        return [
            'creator_id.required' => 'Debe seleccionar un creador.',
            'creator_id.exists' => 'El creador seleccionado no existe.',
            'name.required' => 'El nombre de la campaña es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 150 caracteres.',
            'end_date.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        ];
    }
}
