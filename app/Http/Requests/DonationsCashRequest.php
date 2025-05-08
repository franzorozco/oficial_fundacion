<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationsCashRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'donor_id' => 'nullable|exists:users,id',
            'external_donor_id' => 'nullable|exists:external_donor,id',
            'amount' => 'required|numeric|min:0.01|max:9999999999.99',
            'method' => 'nullable|string|max:50',
            'donation_date' => 'required|date',
            'campaign_id' => 'nullable|exists:campaigns,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'donor_id.exists' => 'El donante seleccionado no existe.',
            'external_donor_id.exists' => 'El donante externo no es válido.',
            'amount.required' => 'El monto de la donación es obligatorio.',
            'amount.numeric' => 'El monto debe ser un número válido.',
            'amount.min' => 'El monto debe ser mayor a 0.',
            'amount.max' => 'El monto excede el máximo permitido.',
            'method.string' => 'El método de pago debe ser una cadena de texto.',
            'method.max' => 'El método de pago no debe exceder los 50 caracteres.',
            'donation_date.required' => 'La fecha de la donación es obligatoria.',
            'donation_date.date' => 'La fecha de la donación debe ser válida.',
            'campaign_id.exists' => 'La campaña seleccionada no existe.',
        ];
    }
}
