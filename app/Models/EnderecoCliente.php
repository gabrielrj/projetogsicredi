<?php

namespace App\Models;

class EnderecoCliente extends ModelComSoftDeletes
{
    public function uf(){
        return $this->belongsTo(Uf::class, 'ufs_id');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'clientes_id');
    }
}
