<?php

namespace App\Rules;

use App\Http\Controllers\EmpresaController;
use Illuminate\Contracts\Validation\Rule;

class EmpresaNaoAtivaRule implements Rule
{
    private $empresa;

    /**
     * Create a new rule instance.
     *
     * @param $empresaId
     */
    public function __construct($empresaId)
    {
        $this->empresa = EmpresaController::listaTodasAsEmpresasCadastradas()->findOrFail($empresaId);
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
        return $this->empresa->ativo;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Não é possível cadastrar ou alterar funcionários para essa empresa pois ela encontra-se inativa.';
    }
}
