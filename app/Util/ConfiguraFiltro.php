<?php


namespace App\Util;


use Illuminate\Support\Facades\DB;

class ConfiguraFiltro
{
    public static function retornaCargosEEsferasClientes(){
        $cargosEEsferas = DB::table('cargo_clientes')
            ->selectRaw('distinct esfera, nome as cargo, situacao')
            ->get();

        return collect($cargosEEsferas);
    }

    public static function retornaEstadosECidadesClientes(){
        $estadosECidades = DB::table('ufs')
            ->join('endereco_clientes', 'ufs.id', '=', 'endereco_clientes.ufs_id')
            ->whereRaw('(endereco_clientes.cidade is not null and endereco_clientes.cidade <> "") and endereco_clientes.deleted_at is null')
            ->selectRaw('distinct ufs.id, ufs.sigla, endereco_clientes.cidade')
            ->orderByRaw('ufs.sigla asc, endereco_clientes.cidade asc')
            ->get();

        return collect($estadosECidades);
    }

    public static function retornaSituacoesClientes(){
        $situacoes = DB::table('cargo_clientes')
            ->selectRaw('distinct situacao as situacao')
            ->orderBy('situacao')
            ->get();

        return collect($situacoes);
    }

    public static function retornaBancosRecClientes(){
        $bancosRec = DB::table('dados_bancarios_clientes')
            ->selectRaw('distinct banco')
            ->orderByRaw('banco asc')
            ->get();

        return collect($bancosRec);
    }

    public static function retornaBancosEmpClientes(){
        $bancosRec = DB::table('emprestimo_clientes')
            ->selectRaw('distinct banco')
            ->orderByRaw('banco asc')
            ->get();

        return collect($bancosRec);
    }
}
