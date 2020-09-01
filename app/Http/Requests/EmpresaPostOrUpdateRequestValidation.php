<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmpresaPostOrUpdateRequestValidation extends FormRequest
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
            'cpf_cnpj' => [
                'required',
                'max:14',
                'min:11',
                Rule::unique('empresas', 'cpf_cnpj')->ignore($this->get('id'))->whereNull('deleted_at'),
            ],
            'razao_social' => 'required|max:1024|min:2',
            'nome_fantasia' => 'required|max:1024|min:2',
            'quantidade_licencas' => 'required|numeric|min:1',
        ];
    }
}
