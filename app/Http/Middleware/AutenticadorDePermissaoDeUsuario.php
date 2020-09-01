<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class AutenticadorDePermissaoDeUsuario
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $funcaoDeAcessoId)
    {
        $usuario = User::with('perfil', 'perfil.funcoesDeAcesso')->findOrFail(Auth::user()->getAuthIdentifier());

        if($usuario->perfil->possuiPermissaoDeAcesso($funcaoDeAcessoId))
            return $next($request);
        else
            return redirect()->back()->with('info', 'O usuário não tem permissão para acessar essa funcionalidade.');
    }
}
