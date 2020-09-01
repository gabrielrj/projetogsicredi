@extends('layouts.app')

@section('content')
    <vue-lista-empresas usuario-logado-json="{{\App\Http\Controllers\UsuarioController::retornaUsuarioLogado()}}"
                        empresas-cadastradas-json="{{$empresasCadastradas}}"></vue-lista-empresas>
@endsection
