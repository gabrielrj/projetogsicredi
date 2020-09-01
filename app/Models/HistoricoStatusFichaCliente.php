<?php

namespace App\Models;

use App\User;

class HistoricoStatusFichaCliente extends ModelComSoftDeletes
{
    protected $fillable = ['users_id', 'status_ficha_id', 'ficha_id', 'ficha_ignorada_motivo'];
    protected $appends = ['motivo_ficha_ignorada'];

    //Métodos
    public function ficha(){
        return $this->belongsTo(FichaCliente::class, 'ficha_id');
    }

    public function usuario(){
        return $this->belongsTo(User::class, 'users_id');
    }

    public function status(){
        return $this->belongsTo(StatusFichaCliente::class, 'status_ficha_id');
    }

    public function insereNovoStatus($ficha_id, $usuario_id, $novo_status_id, $ficha_ignorada_motivo = null){
        $this->cancelaStatusAnterior($ficha_id);

        $this->ficha_id = $ficha_id;
        $this->users_id = $usuario_id;
        $this->status_ficha_id = $novo_status_id;
        $this->ficha_ignorada_motivo = $ficha_ignorada_motivo;

        return $this->save();
    }

    private function cancelaStatusAnterior($ficha_id){
        $historico = HistoricoStatusFichaCliente::where('ficha_id', $ficha_id)->get();
        foreach ($historico as $statusAnterior){
            $statusAnterior->delete();
        }
    }

    public function getMotivoFichaIgnoradaAttribute(){
        if($this->attributes['ficha_ignorada_motivo'] == null || $this->attributes['ficha_ignorada_motivo'] == '')
            return 'Não foi informado o motivo';
        else if($this->attributes['ficha_ignorada_motivo'] == 'VF')
            return 'Venda fechada (contrato realizado)';
        else if($this->attributes['ficha_ignorada_motivo'] == 'CF')
            return 'Cliente falecido';
        else if($this->attributes['ficha_ignorada_motivo'] == 'CSI')
            return 'Cliente sem interesse';
        else if($this->attributes['ficha_ignorada_motivo'] == 'CFOE')
            return 'Cliente fechou com outra empresa';
        else if($this->attributes['ficha_ignorada_motivo'] == 'CA')
            return 'Cliente ausente';
        else if($this->attributes['ficha_ignorada_motivo'] == 'DI')
            return 'Dados incorretos';
        else if($this->attributes['ficha_ignorada_motivo'] == 'BP')
            return 'Bloqueio/Procon';
        else
            return '';
    }
}
