<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSkillsCatalogRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('skills_catalog');

        return [
            'name' => 'required|string|max:100|unique:skills_catalog,name,' . $id,
            'description' => 'nullable|string',
        ];
    }
}