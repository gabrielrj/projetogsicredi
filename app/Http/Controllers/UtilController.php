<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtilController extends Controller
{
    public static function retornaValorDecimalDeString($valor){
        if($valor == null || $valor == 'null' || $valor == '')
            return floatval(0);
        else
            return floatval(trim(str_replace(',', '.', str_replace('.', '', str_replace('R$', '', $valor)))));
    }

    public static function retornaIntOuNulo($string){
        return ($string == '' || $string == 'null' || $string == null || $string == 0) ? null : $string;
    }

    public static function InteiroVerificaTamanhoMinimoEMaximo($min = null, $max = null, $mensagemDeErroMin = null, $mensagemDeErroMax = null, $valor = null){
        if(($valor == null) || ($min == null && $max == null)){
            throw new \Exception('Uso inválido da função, os parâmetros não podem ser todos nulos!');
        }else {
            if($min != null){
                if($valor < $min){
                    if($mensagemDeErroMin != null)
                        throw new \Exception($mensagemDeErroMin);
                    else
                        throw new \Exception("O valor $valor não pode ser inferior a $min!");
                }
            }
            if($max != null){
                if($valor > $max){
                    if($mensagemDeErroMax != null)
                        throw new \Exception($mensagemDeErroMax);
                    else
                        throw new \Exception("O valor $valor não pode ser superior a $max!");
                }
            }
        }

        return true;
    }
}
