<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationRequest extends FormRequest
{
    public function authorize()
    {
        // Aquí puedes agregar lógica para permitir o denegar el acceso
        return true;
    }

    public function rules()
    {
        return [
            'external_donor_id'    => 'nullable|exists:external_donor,id',
            'user_id'              => 'nullable|exists:users,id',
            //'received_by_id'       => '|exists:users,id',
            'status_id'            => 'required|exists:donation_statuses,id',
            'during_campaign_id'   => 'nullable|exists:campaigns,id',
            'donation_date'        => 'required|date',
            'notes'                => 'nullable|string',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $external = $this->input('external_donor_id');
            $user = $this->input('user_id');

            if (empty($external) && empty($user)) {
                $validator->errors()->add('external_donor_id', 'Debe seleccionar al menos un donante (externo o usuario del sistema).');
                $validator->errors()->add('user_id', 'Debe seleccionar al menos un donante (usuario del sistema o externo).');
            }
        });
    }
    public function messages()
    {
        return [
            'external_donor_id.exists'   => 'El donante externo no existe.',
            'user_id.exists'             => 'El usuario donante no existe.',
            //'received_by_id.required'    => 'Debe indicar quién recibió la donación.',
            //'received_by_id.exists'      => 'El usuario que recibió la donación no existe.',
            'status_id.required'         => 'Debe seleccionar un estado para la donación.',
            'status_id.exists'           => 'El estado seleccionado no es válido.',
            'during_campaign_id.exists'  => 'La campaña especificada no existe.',
            'donation_date.required'     => 'Debe especificar la fecha de la donación.',
            'donation_date.date'         => 'La fecha de donación debe ser una fecha válida.',
        ];
    }
}
