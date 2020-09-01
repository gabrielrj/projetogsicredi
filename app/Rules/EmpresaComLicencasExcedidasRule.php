<?php

namespace App\Rules;

use App\Http\Controllers\EmpresaController;
use Illuminate\Contracts\Validation\Rule;

class EmpresaComLicencasExcedidasRule implements Rule
{
    private $empresa;
    private $metodo_request;

    /**
     * Create a new rule instance.
     *
     * @param $empresaId
     */
    public function __construct($empresaId, $metodo_request)
    {
        $this->metodo_request = $metodo_request;
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
        return ($this->metodo_request == 'PUT' || $this->empresa->licencas_restantes > 0);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Não é possível cadastrar novos funcionários pois as suas licenças de usuário esgotaram.';
    }
}
