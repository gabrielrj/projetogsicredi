<?php

namespace App\Observers;

use App\Models\Empresa;

class EmpresaObserver
{
    public function deleting(Empresa $empresa){
        //Deleta os departamentos da empresa;
        foreach ($empresa->departamentos as $departamento)
            $departamento->delete();

        //Deleta os funcionários da empresa;
        foreach ($empresa->funcionarios as $funcionario)
            $funcionario->delete();

        //Delete os perfis de acesso/usuário da empresa;
        $empresa->perfis()->delete();
    }
}
