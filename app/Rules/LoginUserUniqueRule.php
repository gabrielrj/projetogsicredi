<?php

namespace App\Rules;

use App\Http\Controllers\FuncionarioController;
use App\Util\Util;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class LoginUserUniqueRule implements Rule
{
    private $funcionario = null;
    private $login_username = null;
    private $metodo = null;
    private $empresaId = null;

    /**
     * Create a new rule instance.
     *
     * @param null $funcionarioId
     * @param $login_username
     * @param $empresaId
     * @param $metodo
     */
    public function __construct($funcionarioId = null, $login_username, $empresaId, $metodo)
    {
        if($funcionarioId != null)
            $this->funcionario = FuncionarioController::listaTodosOsFuncionariosCadastrados(['usuario', 'empresa'])->findOrFail($funcionarioId);

        $this->login_username = strtoupper($login_username);
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
        $loginUserName = $this->login_username;

        $funcionarioComLogin = FuncionarioController::listaTodosOsFuncionariosCadastrados(['usuario', 'empresa'], [], true)
            ->whereHas('usuario', function (Builder $qUsuario) use($loginUserName){
                $qUsuario->where('login_username', '=', $loginUserName)
                    ->where('active', '=', 1);
            })
            ->where('empresa_id', '=', $this->empresaId)
            ->first();

        if($this->metodo == 'POST')
            return ($funcionarioComLogin == null); //Funcionário não encontrado com o login na empresa.
        else
            return ($funcionarioComLogin == null || ($this->funcionario != null && ($this->funcionario->id == $funcionarioComLogin->id)));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Esse login já está sendo utilizado por outro usuário nessa empresa.';
    }
}
