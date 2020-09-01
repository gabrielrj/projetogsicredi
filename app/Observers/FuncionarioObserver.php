<?php

namespace App\Observers;

use App\Models\Funcionario;

class FuncionarioObserver
{
    public function deleting(Funcionario $funcionario){
        //Excluir usuário de acesso do funcionário excluído
        $funcionario->usuario->active = false;//->delete();
        $funcionario->usuario->save();
        $funcionario->usuario()->delete();
    }

    public function restoring(Funcionario $funcionario){
        //Restaurar usuário excluído
        $funcionario->usuario->active = true;//->delete();
        $funcionario->usuario->save();
        $funcionario->usuario()->withTrashed()->restore();
    }
}
