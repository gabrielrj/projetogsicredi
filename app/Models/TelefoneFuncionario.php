<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelefoneFuncionario extends Model
{
    protected $fillable = ['id', 'ddd', 'numero', 'tipo', 'funcionarios_id'];

    public function funcionario(){
        return $this->belongsTo(Funcionario::class);
    }
}
