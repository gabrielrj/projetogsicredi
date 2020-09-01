@extends('layouts.app')

@section('content')
    <vue-lista-equipes empresas-ou-empresa-json="{{$empresasOuEmpresa}}"
                             usuario-logado-json="{{$usuarioLogado}}"></vue-lista-equipes>
@endsection
