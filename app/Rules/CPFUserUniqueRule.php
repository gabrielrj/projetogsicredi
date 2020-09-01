<?php

namespace App\Rules;

use App\Http\Controllers\FuncionarioController;
use App\Util\Util;
use Illuminate\Contracts\Validation\Rule;

class CPFUserUniqueRule implements Rule
{
    private $funcionario = null;
    private $cpf;
    private $metodo;
    private $empresaId;

    /**
     * Create a new rule instance.
     *
     * @param null $funcionarioId
     * @param $cpf
     * @param $metodo
     */
    public function __construct($funcionarioId = null, $cpf, $empresaId, $metodo)
    {
        if($funcionarioId != null)
            $this->funcionario = FuncionarioController::listaTodosOsFuncionariosCadastrados(['usuario', 'empresa'])->findOrFail($funcionarioId);

        $this->cpf = Util::retiraPontosETracosCPFCNPJ($cpf);
        $this->metodo = $metodo;
        $this->empresaId = $empresaId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $funcionarioComCPF = FuncionarioController::listaTodosOsFuncionariosCadastrados(['usuario', 'empresa'], [], true)
            ->where('cpf', '=', $this->cpf)
            ->where('empresa_id', '=', $this->empresaId)
            ->first();

        if($this->metodo == 'POST')
            return ($funcionarioComCPF == null); //Funcionário não encontrado com o CPF na empresa.
        else
            return ($funcionarioComCPF == null || ($this->funcionario != null && ($this->funcionario->id == $funcionarioComCPF->id)));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Esse CPF já está sendo utilizado por outro usuário ativo dessa empresa na base de dados.';
    }
}
