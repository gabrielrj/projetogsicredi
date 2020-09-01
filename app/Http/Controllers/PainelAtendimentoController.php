<?php

namespace App\Http\Controllers;

use App\Models\FuncaoAcesso;
use App\Models\Uf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PainelAtendimentoController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissao:'.FuncaoAcesso::where('rota', '/painel')->get()->implode('id'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $usuarioLogado = UsuarioController::retornaUsuarioLogado();

            return view('vendas.painelconsultor.index', compact( 'usuarioLogado'));
        }catch (\Exception $e){
            return view('home')->withErrors([$e->getMessage()]);
        }
    }
}
