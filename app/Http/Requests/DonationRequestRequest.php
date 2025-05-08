<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationRequestRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     *
     * @return bool
     */
    public function authorize()
    {
        // Aquí puedes verificar si el usuario tiene permiso para hacer la solicitud.
        return true; // Cambia esto según las necesidades de autorización.
    }

    /**
     * Obtener las reglas de validación que se aplicarán a la solicitud.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'applicant_user__id' => 'required|exists:users,id',
            'user_in_charge_id' => 'nullable|exists:users,id',
            'donation_id' => 'required|exists:donations,id',
            'request_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'state' => 'required|in:pendiente,en revision,aceptado,rechazado,finalizado',
        ];
    }
    

    /**
     * Obtener los mensajes de error personalizados para las reglas de validación.
     *
     * @return array
     */
    public function messages()
{
    return [
        'applicant_user_id.required' => 'El ID del solicitante es obligatorio.',
        'applicant_user_id.exists' => 'El usuario solicitante no existe.',
        'user_in_charge_id.exists' => 'El usuario a cargo no existe.',
        'donation_id.required' => 'El ID de la donación es obligatorio.',
        'donation_id.exists' => 'La donación especificada no existe.',
        'request_date.date' => 'La fecha de solicitud debe ser una fecha válida.',
        'notes.string' => 'Las notas deben ser un texto válido.',
        'state.required' => 'El estado de la solicitud es obligatorio.',
        'state.in' => 'El estado de la solicitud debe ser uno de los siguientes: pendiente, en revision, aceptado, rechazado, finalizado.',
    ];
}
}
