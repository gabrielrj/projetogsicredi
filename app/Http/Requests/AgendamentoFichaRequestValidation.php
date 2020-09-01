<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgendamentoFichaRequestValidation extends FormRequest
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
            'data_agendamento' => ['required'],
            'hora_agendamento' => ['required'],
            'numero_telefone' => ['required', 'min:14', 'max:15'],
            'tipo_id' => ['required', 'exists:App\Models\TipoAgendamentoCliente,id'],
            'cep' => [
                'nullable',
                'size:9',
                Rule::requiredIf(function (){
                    return $this->get('tipo_id') == 1;
                })
            ],
            'logradouro' => [
                'nullable',
                'min:2',
                'max:150',
                Rule::requiredIf(function (){
                    return $this->get('tipo_id') == 1;
                })
            ],
            'bairro' => [
                'nullable',
                'min:2',
                'max:150',
                Rule::requiredIf(function (){
                    return $this->get('tipo_id') == 1;
                })
            ],
            'cidade' => [
                'nullable',
                'min:2',
                'max:150',
                Rule::requiredIf(function (){
                    return $this->get('tipo_id') == 1;
                })
            ],
            'ufs_id' => [
                'nullable',
                'exists:App\Models\Uf,id',
                Rule::requiredIf(function (){
                    return $this->get('tipo_id') == 1;
                })
            ],
        ];
    }

    public function messages()
    {
        return [
            'data_agendamento.required' => 'O campo Data do Agendamento é obrigatório.',
            'hora_agendamento.required' => 'O campo Hora do Agendamento é obrigatório.',
            'numero_telefone.required' => 'O campo Telefone é obrigatório.',
            'numero_telefone.min' => 'O campo Telefone deve ter no mínimo 14 caracteres.',
            'numero_telefone.max' => 'O campo Telefone deve ter no máximo 15 caracteres.',
            'tipo_id.required' => 'O campo Tipo de Agendamento é obrigatório.',
            'tipo_id.exists' => 'Tipo de Agendamento inválido.',
            'cep.required' => 'O campo CEP é obrigatório.',
            'cep.size' => 'O campo CEP deve ter exatos 9 caracteres.',
            'logradouro.required' => 'O campo Logradouro é obrigatório.',
            'logradouro.min' => 'Mínimo de 2 caracteres para o campo Logradouro.',
            'logradouro.max' => 'Máximo de 150 caracteres para o campo Logradouro.',
            'bairro.required' => 'O campo Bairro é obrigatório.',
            'bairro.min' => 'Mínimo de 2 caracteres para o campo Bairro.',
            'bairro.max' => 'Máximo de 150 caracteres para o campo Bairro.',
            'cidade.required' => 'O campo Cidade é obrigatório.',
            'cidade.min' => 'Mínimo de 2 caracteres para o campo Cidade.',
            'cidade.max' => 'Máximo de 150 caracteres para o campo Cidade.',
            'ufs_id.required' => 'O campo Estado (UF) é obrigatório.',
            'ufs_id.exists' => 'O campo Estado (UF) é obrigatório.',
        ];
    }
}
