@extends('layouts.app')

@section('content')
    <div class="content">
        <vue-home usuario-logado-json="{{\App\Http\Controllers\UsuarioController::retornaUsuarioLogado()}}"></vue-home>
    </div>
@endsection
