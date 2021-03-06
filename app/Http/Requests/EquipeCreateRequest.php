<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EquipeCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome' => 'required|max:50|min:2',
            'departamento.id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'departamento.id.required' => 'O campo departamento é obrigatório.'
        ];
    }
}
