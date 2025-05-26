<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationRequestDescriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Puedes modificar esto según el sistema de permisos que tengas
        return true;
    }

    public function rules(): array
    {
        return [
            'donation_request_id'   => 'required',
            'recipient_name'        => 'required|string|max:255',
            'recipient_address'     => 'nullable|string',
            'recipient_contact'     => 'nullable|string|max:100',
            'tipo_beneficiario'        => 'required|in:individual,organization,community,other',
            'reason'                => 'nullable|string',
            'latitude'              => 'nullable|numeric|between:-90,90',
            'longitude'             => 'nullable|numeric|between:-180,180',
            'extra_instructions'    => 'nullable|string',
            
            // Aquí la validación para el archivo PDF, opcional y máximo 5MB
            'supporting_document'   => 'nullable|file|mimes:pdf|max:5120',
            
            // Checkbox para eliminar archivo (no es obligatorio validar)
            'remove_supporting_document' => 'nullable|boolean',
            'supporting_document_name' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'donation_request_id.required'  => 'La solicitud de donación es obligatoria.',
            'donation_request_id.exists'    => 'La solicitud de donación seleccionada no existe.',
            'recipient_name.required'       => 'El nombre del destinatario es obligatorio.',
            'recipient_name.max'            => 'El nombre del destinatario no puede superar los 255 caracteres.',
            'recipient_contact.max'         => 'El contacto del destinatario no puede superar los 100 caracteres.',
            'tipo_beneficiario.required'       => 'El tipo de beneficiario es obligatorio.',
            'tipo_beneficiario.in'             => 'El tipo de beneficiario seleccionado no es válido.',
            'latitude.numeric'              => 'La latitud debe ser un número válido.',
            'latitude.between'              => 'La latitud debe estar entre -90 y 90 grados.',
            'longitude.numeric'             => 'La longitud debe ser un número válido.',
            'longitude.between'             => 'La longitud debe estar entre -180 y 180 grados.',
            'supporting_document.file' => 'El documento debe ser un archivo válido.',
            'supporting_document.mimes' => 'El documento debe ser un archivo PDF.',
            'supporting_document.max' => 'El documento no debe superar los 5MB.',
        ];
    }
}
