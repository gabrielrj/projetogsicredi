<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class FichaCliente extends ModelComSoftDeletes
{
    protected $fillable = ['carteira_id', 'clientes_id', 'ultimo_acesso'];
    protected $appends = ['status_atual',
        'ultimo_acesso',
        'quantidade_de_agendamentos_de_visita',
        'quantidade_de_agendamentos_de_chamada'];

    public function getUltimoAcessoAttribute(){
        if($this->attributes['ultimo_acesso'] != null)
            return date_format(date_create($this->attributes['ultimo_acesso']), 'd/m/Y');
        else
            return null;
    }

    public function getStatusAtualAttribute(){
        $historico = $this->historicoStatus()->first();
        $status_atual = [
            'id' => $historico->status->id,
            'descricao' => $historico->status->descricao,
            'motivo_ficha_ignorada' => $historico->motivo_ficha_ignorada
        ];

        return (object)$status_atual;
    }

    //Métodos
    public function carteira(){
        return $this->belongsTo(CarteiraFichaCliente::class, 'carteira_id');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'clientes_id');
    }

    public function historicoStatus(){
        return $this->hasMany(HistoricoStatusFichaCliente::class, 'ficha_id');
    }

    public function agendamentos(){
        return $this->hasMany(AgendamentoFichaCliente::class, 'ficha_id')->withTrashed();
    }

    public function cadastraNovaFicha($carteira_id, $cliente_id, $usuario_id){
        $this->carteira_id = $carteira_id;
        $this->clientes_id = $cliente_id;
        $this->save();

        $novoHistorico = new HistoricoStatusFichaCliente();
        $novoHistorico->insereNovoStatus($this->id, $usuario_id, 1, null);
    }

    public function getQuantidadeDeAgendamentosDeVisitaAttribute(){
        $quantidadeDeVisitasAgendadas = $this->agendamentos()
            ->where('tipo_id', '=', 1)->count();

        return $quantidadeDeVisitasAgendadas;
    }

    public function getQuantidadeDeAgendamentosDeChamadaAttribute(){
        $quantidadeDeChamadasAgendadas = $this->agendamentos()
            ->where('tipo_id', '=', 2)->count();

        return $quantidadeDeChamadasAgendadas;
    }
}
