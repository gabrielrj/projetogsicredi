<?php

namespace App\Models;

class EmprestimoCliente extends ModelComSoftDeletes
{
    protected $appends = ['valor_parcela_formatado', 'valor_total'];

    public function getValorParcelaFormatadoAttribute()
    {
        if($this->attributes['valor_parcela'] != '' && $this->attributes['valor_parcela'] != 0.00 && $this->attributes['valor_parcela'] != null)
            return  'R$ '.number_format($this->attributes['valor_parcela'], 2, ',', '.');
        else
            return 'R$ 0,00';
    }

    public function getValorTotalAttribute()
    {
        $valor_parcela = $this->attributes['valor_parcela'];
        $quantidade_parcelas = $this->attributes['quantidade_parcelas_total'];
        $valor_total = 'R$ 0,00';

        if($valor_parcela != null && $valor_parcela != '' && $quantidade_parcelas != null && $quantidade_parcelas != '' && is_numeric($valor_parcela) && is_numeric($quantidade_parcelas)){
            $valor_total = $valor_parcela * $quantidade_parcelas;
            $valor_total = 'R$ '.number_format($valor_total, 2, ',', '.');
        }

        return $valor_total;
    }
}
