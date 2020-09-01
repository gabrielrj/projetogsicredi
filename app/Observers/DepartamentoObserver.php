<?php

namespace App\Observers;

use App\Models\Departamento;

class DepartamentoObserver
{
    public function deleting(Departamento $departamento){
        //Excluíndo equipes do departamento que está sendo excluído
        foreach ($departamento->equipes as $equipe)
            $equipe->delete();

        //Excluíndo cargos do departamento que está sendo excluído
        $departamento->cargos()->delete();
    }
}
