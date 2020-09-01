<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public static function retornaUsuarioPorId($usuarioId){
        return User::with('configuracaoFiltros',
            'configuracaoFiltros.filtro_cargos',
            'configuracaoFiltros.filtro_cidades',
            'configuracaoFiltros.filtro_situacoes',
            'configuracaoFiltros.filtro_bancos_emps',
            'configuracaoFiltros.filtro_bancos_recs',
            'funcionario',
            'funcionario.empresa',
            'funcionario.departamento.empresa',
            'funcionario.cargo.departamento.empresa',
            'funcionario.equipe.departamento.empresa',
            'funcionario.equipe.configuracaoFiltros',
            'funcionario.equipe.configuracaoFiltros.filtro_cargos',
            'funcionario.equipe.configuracaoFiltros.filtro_cidades',
            'funcionario.equipe.configuracaoFiltros.filtro_situacoes',
            'funcionario.equipe.configuracaoFiltros.filtro_bancos_emps',
            'funcionario.equipe.configuracaoFiltros.filtro_bancos_recs',
            'perfil',
            'perfil.funcoesDeAcesso',
            'perfil.funcoesDeAcesso.menu',
            'perfil.funcoesDeAcesso.submenu.menu',
            'perfil.configuracaoFiltros',
            'perfil.configuracaoFiltros.filtro_cargos',
            'perfil.configuracaoFiltros.filtro_cidades',
            'perfil.configuracaoFiltros.filtro_situacoes',
            'perfil.configuracaoFiltros.filtro_bancos_emps',
            'perfil.configuracaoFiltros.filtro_bancos_recs')->findOrFail($usuarioId);
    }

    public static function retornaUsuarioLogado(){
        if(Auth::check())
            return self::retornaUsuarioPorId(Auth::user()->getAuthIdentifier());
        else
            return null;

    }

    public static function retornaUsuarioLogadoJson(){
        if(Auth::check())
            return self::retornaUsuarioPorId(Auth::user()->getAuthIdentifier())->toArray();
        else
            return null;
    }
}
