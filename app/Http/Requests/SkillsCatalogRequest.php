<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SkillsCatalogRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $skill = $this->route('skills_catalog'); // objeto

        $id = $skill ? $skill->id : null;

        return [
            'name' => 'required|string|max:100|unique:skills_catalog,name,' . $id,
            'description' => 'nullable|string',
        ];
    }
}