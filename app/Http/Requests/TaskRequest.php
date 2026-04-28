<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
public function rules()
{
    return [
        'name' => 'required|min:3|max:100',
        'description' => 'required|min:10|max:255',
        'required_days' => 'required|array|min:1',
        'required_days.*' => 'in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',

        'start_hour' => 'required|date_format:H:i',
        'end_hour' => 'required|date_format:H:i|after:start_hour',

        'location' => 'nullable|max:150',

        'skills' => 'required|array|max:5',
        'skills.*' => 'exists:skills_catalog,id',

        'levels' => 'required|array',
        'levels.*' => 'in:básico,intermedio,avanzado',
    ];
}

}
