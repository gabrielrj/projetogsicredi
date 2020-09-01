@extends('layouts.app')

@section('content')
    <vue-lista-cargos empresas-ou-empresa-json="{{$empresasOuEmpresa}}"
                             usuario-logado-json="{{$usuarioLogado}}"></vue-lista-cargos>
@endsection
