<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignFinanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Puedes modificar esta lógica según tus necesidades de autorización
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'campaign_id' => 'required|exists:campaigns,id', // Asegura que el `campaign_id` sea válido y exista en la tabla `campaigns`
            'manager_id' => 'required|exists:users,id', // Asegura que el `manager_id` sea válido y exista en la tabla `users`
            'income' => 'nullable|numeric|min:0', // Ingresos deben ser numéricos y mayores o iguales a 0
            'expenses' => 'nullable|numeric|min:0', // Gastos deben ser numéricos y mayores o iguales a 0
            'net_balance' => 'nullable|numeric|min:0', // Balance neto debe ser numérico y mayor o igual a 0
        ];
    }

    /**
     * Get the custom messages for validation errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'campaign_id.required' => 'El campo de campaña es obligatorio.',
            'campaign_id.exists' => 'La campaña seleccionada no existe.',
            'manager_id.required' => 'El campo de gerente es obligatorio.',
            'manager_id.exists' => 'El gerente seleccionado no existe.',
            'income.numeric' => 'Los ingresos deben ser un número válido.',
            'income.min' => 'Los ingresos no pueden ser menores a 0.',
            'expenses.numeric' => 'Los gastos deben ser un número válido.',
            'expenses.min' => 'Los gastos no pueden ser menores a 0.',
            'net_balance.numeric' => 'El balance neto debe ser un número válido.',
            'net_balance.min' => 'El balance neto no puede ser menor a 0.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Aquí puedes modificar los datos antes de la validación si es necesario
    }
}
