<?php

namespace App\Observers;

use App\Models\Equipe;

class EquipeObserver
{
    public function deleting(Equipe $equipe){
        //Deleta filtros que estejam ligados a equipe
        $equipe->configuracaoFiltros()->delete();
    }
}
