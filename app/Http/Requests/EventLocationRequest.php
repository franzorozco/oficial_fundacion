<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Puedes ajustar esto si necesitas lógica de autorización específica
        return true;
    }

    public function rules(): array
    {
        return [
            'event_id' => 'required|exists:events,id',
            'location_name' => 'required|string|max:150',
            'address' => 'nullable|string|max:255',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'start_hour' => 'nullable|date_format:H:i',
            'end_hour' => 'nullable|date_format:H:i',
        ];
    }
}
