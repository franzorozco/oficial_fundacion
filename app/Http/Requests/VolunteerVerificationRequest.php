<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VolunteerVerificationRequest extends FormRequest
{
    public function authorize()
    {
        // Ajusta según tu lógica de autorización
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            //'user_resp_id' => ['required', 'exists:users,id'],
            'document_type' => ['nullable', 'string', 'max:100'],
            'document_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB máximo, ajusta según necesites
            'document_url' => ['nullable', 'url', 'max:255'],
            'name_document' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['pendiente', 'aprobado', 'rechazado', 'reconsideracion'])],
            'coment' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('document_file', 'required_without:document_url', function ($input) {
            return empty($input->document_url);
        });

        $validator->sometimes('document_url', 'required_without:document_file', function ($input) {
            return empty($input->document_file);
        });
    }
}
