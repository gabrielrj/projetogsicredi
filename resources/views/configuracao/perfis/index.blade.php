@extends('layouts.app')

@section('content')
    <vue-lista-perfis empresas-ou-empresa-json="{{$empresasOuEmpresa}}"
                      usuario-logado-json="{{$usuarioLogado}}"
                      funcoes-de-acesso-json="{{$funcoesDeAcesso}}"></vue-lista-perfis>
@endsection
