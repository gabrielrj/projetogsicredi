<?php

namespace App\Observers;

use App\Models\CarteiraFichaCliente;

class CarteiraFichaClienteObserver
{
    public function deleting(CarteiraFichaCliente $carteiraFichaCliente){
        $carteiraFichaCliente->fichas()->delete();
    }
}
