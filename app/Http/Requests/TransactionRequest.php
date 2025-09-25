<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Puedes personalizar esto según tus reglas de autorización
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => ['required', 'exists:financial_accounts,id'],
            'type' => ['required', 'in:ingreso,gasto'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'related_campaign_id' => ['nullable', 'exists:campaigns,id'],
            'related_event_id' => ['nullable', 'exists:events,id'],
            'related_event_location_id' => ['nullable', 'exists:event_locations,id'],
            'transaction_date' => ['required', 'date'],
            'transaction_time' => ['required', 'date_format:H:i'],
        ];
    }


    public function messages(): array
    {
        return [
            'account_id.required' => 'Debes seleccionar una cuenta financiera.',
            'account_id.exists' => 'La cuenta seleccionada no existe.',
            'type.required' => 'Debes especificar el tipo de transacción.',
            'type.in' => 'El tipo de transacción debe ser "ingreso" o "gasto".',
            'amount.required' => 'Debes ingresar un monto.',
            'amount.numeric' => 'El monto debe ser un número válido.',
            'amount.min' => 'El monto debe ser mayor a cero.',
            'transaction_date.required' => 'La fecha de la transacción es obligatoria.',
            'transaction_date.date' => 'La fecha de transacción no es válida.',
        ];
    }
}
