@extends('layouts.app')

@section('content')

    <vue-painel-atendimento usuario-logado-json="{{$usuarioLogado}}" ref="painel_atendimento"></vue-painel-atendimento>

@endsection
