@extends('layouts.app')

@section('content')
    <vue-lista-departamentos empresas-ou-empresa-json="{{$empresasOuEmpresa}}"
                             usuario-logado-json="{{$usuarioLogado}}"></vue-lista-departamentos>
@endsection
