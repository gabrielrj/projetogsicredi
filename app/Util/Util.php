<?php


namespace App\Util;


class Util
{
    public static function retiraPontosETracosCPFCNPJ($valor){
        $valor = str_replace('.', '', $valor);
        $valor = str_replace('-', '', $valor);
        $valor = str_replace('/', '', $valor);
        return $valor;
    }
}
