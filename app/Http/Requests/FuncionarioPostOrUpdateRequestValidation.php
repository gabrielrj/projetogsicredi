<?php

namespace App\Http\Requests;

use App\Rules\CPFUserUniqueRule;
use App\Rules\EmpresaNaoAtivaRule;
use App\Rules\EmpresaComLicencasExcedidasRule;
use App\Rules\LoginUserUniqueRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class FuncionarioPostOrUpdateRequestValidation extends FormRequest
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
            'rg' => ['max:20'],
            'cpf' => [
                'min:11',
                'max:11',
                'required',
                new CPFUserUniqueRule($this->get('id'), $this->get('cpf'), $this->get('empresa_id'), strtoupper($this->method())),
            ],
            'nome' => ['min:2', 'max:100', 'required'],
            'matricula' => ['max:150'],
            'empresa.id' => ['required'],
            'empresa' => [
                new EmpresaNaoAtivaRule($this->get('empresa')['id']),
                new EmpresaComLicencasExcedidasRule($this->get('empresa')['id'], strtoupper($this->method())),
            ],
            'usuario.login_username' => [
                'required',
                'max:250',
                new LoginUserUniqueRule($this->get('id'), $this->get('usuario')['login_username'], $this->get('empresa_id'), strtoupper($this->method())),
            ],
            'usuario.password' => [
                'max:20',
                Rule::requiredIf(function (){
                    return strtoupper($this->method()) == 'POST';
                })
            ],
            'usuario.perfil_id' => ['required'],
            'usuario.email' => ['max:150']
        ];
    }

    public function messages()
    {
        return [
            'rg.max' => 'O RG deve ter no máximo 20 caracteres.',
            'matricula.max' => 'A Matrícula deve ter no máximo 150 caracteres.',
            'cpf.required' => 'O CPF é um campo obrigatório.',
            'cpf.min' => 'O CPF deve ter no mínimo 11 caracteres.',
            'cpf.min' => 'O CPF deve ter no máximo 11 caracteres.',
            'nome.required' => 'O Nome é um campo obrigatório.',
            'nome.min' => 'O Nome deve ter no mínimo 2 caracteres.',
            'nome.max' => 'O Nome deve ter no máximo 100 caracteres.',
            'usuario.login_username.required' => 'O campo Login do usuário é obrigatório.',
            'usuario.login_username.max' => 'O campo Login do usuário deve ter no máximo 250 caracteres.',
            'usuario.password.required' => 'O campo senha do usuário (password) é obrigatório',
            'usuario.password.max' => 'A senha do usuário deve ter no máximo 20 caracteres.',
            'usuario.perfil_id.required' => 'O Perfil do usuário é um campo obrigatório.',
            'usuario.email.max' => 'O E-mail do usuário deve ter no máximo 150 caracteres.',
            'empresa.id' => 'É obrigatório selecionar a empresa do funcionário',
        ];
    }
}
