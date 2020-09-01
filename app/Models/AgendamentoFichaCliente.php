<?php

namespace App\Models;

use App\User;

class AgendamentoFichaCliente extends ModelComSoftDeletes
{
    protected $fillable = ['id',
        'hora_agendamento',
        'data_agendamento',
        'data_hora_agendamento',
        'motivo_cancelamento',
        'ddd',
        'numero_telefone',
        'tipo_telefone',
        'cep',
        'tipo_logradouro',
        'logradouro',
        'numero_endereco',
        'complemento',
        'bairro',
        'cidade',
        'users_id',
        'ufs_id',
        'tipo_id',
        'ficha_id'];

    protected $appends = ['data_hora_formatado', 'ativo'];

    // Atributos

    public function getDataHoraFormatadoAttribute(){
        return date_format(date_create($this->attributes['data_hora_agendamento']), 'd/m/Y H:i');
    }

    public function getAtivoAttribute(){
        return $this->attributes['deleted_at'] == null;
    }

    public function ficha(){
        return $this->belongsTo(FichaCliente::class, 'ficha_id');
    }

    public function tipo_agendamento(){
        return $this->belongsTo(TipoAgendamentoCliente::class, 'tipo_id');
    }

    public function uf(){
        return $this->belongsTo(Uf::class, 'ufs_id');
    }

    public function usuario(){
        return $this->belongsTo(User::class, 'users_id');
    }
}
