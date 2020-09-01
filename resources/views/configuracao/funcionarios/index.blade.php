@extends('layouts.app')

@section('content')
    <vue-lista-funcionarios empresas-ou-empresa-json="{{$empresasOuEmpresa}}"
                            usuario-logado-json="{{$usuarioLogado}}"></vue-lista-funcionarios>
@endsection
