<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventParticipantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Devolver true si el usuario está autorizado a hacer esta solicitud.
        return true; // Puedes agregar lógica de autorización si es necesario.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
            'event_locations_id' => 'required|exists:event_locations,id',
            'observations' => 'nullable|string',
            'status' => 'nullable|in:registered,attended,absent',
        ];
    }

}
