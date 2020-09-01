<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Auth\LoginController;
use Carbon\Carbon;
use Closure;

class CheckUserUniqueAndActivateAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /* Verifica se o valor da coluna/sessão "token_access" NÃO é compatível com o valor da sessão que criamos quando o usuário fez login
        */
        if (auth()->user()->token_access != session()->get('access_token')) {
            // Realiza o bloqueio do usuário devido ao acesso simultâneo
            $user = auth()->user();
            $user->bloqueia();

            // Faz o logout do usuário
            \Auth::logout();

            // Redireciona o usuário para a página de login, com session flash "message"
            return redirect()
                ->route('login')
                ->with('aviso', 'Foi identificado um duplo acesso a este usuário, por isso foi deslogado e bloqueado por 5 minutos!');
            /*if($user->numero_bloqueios < 3)
                return redirect()
                    ->route('login')
                    ->with('aviso', 'Foi identificado um duplo acesso a este usuário, por isso foi deslogado e bloqueado por 5 minutos! Depois de 3 ações de bloqueio o usuário será permanentemente bloqueado.');
            else
                return redirect()
                    ->route('login')
                    ->with('aviso', 'O número de bloqueios temporários ao usuário foi excedido, por isso o usuário está permanenetemente bloqueado.');*/
        }

        $loginController = new LoginController();
        $resposta = $loginController->usuarioEstaAtivo(auth()->user());

        if($resposta === true){
            // Permite o acesso, continua a requisição
            return $next($request);
        }else{
            // Faz o logout do usuário
            \Auth::logout();

            // Redireciona o usuário para a página de login, com session flash "message"
            return redirect()
                ->route('login')
                ->with('aviso', $resposta);
        }
    }
}
