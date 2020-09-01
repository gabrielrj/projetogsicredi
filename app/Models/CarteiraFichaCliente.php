<?php

namespace App\Models;

use App\User;

class CarteiraFichaCliente extends ModelComSoftDeletes
{
    protected $fillable = ['users_id'];
    protected $appends = ['fichas_ordenadas_ultimo_acesso'];

    public function getFichasOrdenadasUltimoAcessoAttribute(){
        return $this->fichas()->orderByDesc('ultimo_acesso')->with('agendamentos.tipo_agendamento',
            'agendamentos.uf',
            'agendamentos.usuario.funcionario',
            'cliente.enderecos',
            'cliente.enderecos.uf',
            'cliente.cargos',
            'cliente.dados_bancarios',
            'cliente.emprestimos',
            'historicoStatus.usuario',
            'historicoStatus.status')->get()->toArray();
    }

    //Metodos
    public function empresa(){
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function usuario(){
        return $this->belongsTo(User::class, 'users_id');
    }

    public function fichas(){
        return $this->hasMany(FichaCliente::class, 'carteira_id');
    }
}
