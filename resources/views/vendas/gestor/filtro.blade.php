@extends('layouts.app')

@section('content')
    <vue-gestor-lista-filtros cargos-json="{{$cargos}}"
                              estados-e-cidades-json="{{$estadosECidades}}"
                              bancos-recebimento-json="{{$bancosRec}}"
                              bancos-emprestimo-json="{{$bancosEmp}}"
                              empresas-ou-empresa-json="{{$empresasOuEmpresa}}"
                              usuario-logado-json="{{$usuarioLogado}}"></vue-gestor-lista-filtros>
@endsection
