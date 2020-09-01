<?php

namespace App\Http\Controllers;

use App\Models\CarteiraFichaCliente;
use App\Models\Cliente;
use App\Models\ConfiguracaoFiltro;
use App\Models\FichaCliente;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CarteiraFichaClienteController extends Controller
{

    public function retornaCarteiraAtivaDoConsultor(){
        try{
            $usuarioLogado = UsuarioController::retornaUsuarioLogado();

            // Pega carteira Atual
            $carteiraId = $this->retornaCarteiraUsuario($usuarioLogado->id)->id;

            $this->verificaQuantidadeDeFichasNaCarteira($carteiraId);

            // Pega carteira Atualizada
            $carteiraCompleta = CarteiraFichaCliente::findOrFail($carteiraId);

            return $carteiraCompleta->toArray();
        }catch (\Exception $e){
            return response()->json([
                'status_resposta' => 'error',
                'msg'    => $e->getMessage(),
            ], 422);
        }
    }

    public function verificaQuantidadeDeFichasNaCarteira($id){
        try {
            $fichas = FichaCliente::with('historicoStatus')->whereHas('historicoStatus', function ($query){
                $query->whereIn('status_ficha_id', [1, 2])->whereNull('deleted_at');
            })->where('carteira_id', $id)->get();

            if($fichas != null && $fichas->count() < 15){
                $this->atualizaCarteira($id, $fichas->count());
            }
        }catch (\Exception $ex){
            throw $ex;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            //FichaClienteController::excluiTodasAsFichasPorCarteira($id);
            $carteira = CarteiraFichaCliente::findOrFail($id);

            if($carteira)
                $carteira->delete();

            return true;
        }catch (\Exception $e){
            return false;
        }
    }

    public function retornaCarteiraUsuario($usuarioId){
        try {
            $carteiraDoUsuario = CarteiraFichaCliente::where('users_id', $usuarioId)->first();
            if($carteiraDoUsuario)
                return $carteiraDoUsuario;
            else
                return $this->cadastraCarteira($usuarioId);
        }catch (\Exception $e){
            throw $e;
        }
    }

    public function cadastraCarteira($usuarioId){
        $configuracaoFiltro = ConfiguracaoFiltroController::retornaConfiguracaoDoUsuario($usuarioId);

        DB::beginTransaction();

        try{
            $clientesNovosId = $this->retornaClientesParaTransformarEmFichasEAplicaFiltros($configuracaoFiltro, 15);

            if($clientesNovosId == null || count($clientesNovosId) == 0)
            {
                if($configuracaoFiltro)
                    throw new \Exception('<strong>Desculpe</strong>, mas não foi possível criar a sua carteira de clientes pois o filtro aplicado pelo seu gestor não retornou nenhuma oportunidade!<br>Entre em contato com o seu gestor para que ele aplique um novo filtro.');
                else
                    throw new \Exception('<strong>Desculpe</strong>, mas não foi possível criar a sua carteira de clientes pois a tabela de clientes encontra-se vazia!<br>Entre em contato com o administrador do sistema.');
            }

            $carteira = CarteiraFichaCliente::create([
                'users_id' => $usuarioId
            ]);

            FichaClienteController::cadastraLoteDeFichas($carteira->id, $clientesNovosId);

            DB::commit();

            return $carteira;
        }catch (\Exception $e){
            DB::rollBack();

            throw $e;
        }
    }

    public function atualizaCarteira($carteira_id, $quantidade_restante_fichas){
        $quantidade_maxima_fichas = 15;
        $quantidade_novas_fichas = $quantidade_maxima_fichas - $quantidade_restante_fichas;

        $configuracaoFiltro = ConfiguracaoFiltroController::retornaConfiguracaoDoUsuario(Auth::user()->getAuthIdentifier());

        DB::beginTransaction();

        try{
            $clientesNovosId = $this->retornaClientesParaTransformarEmFichasEAplicaFiltros($configuracaoFiltro, $quantidade_novas_fichas);

            if($clientesNovosId == null || count($clientesNovosId) == 0)
            {
                if($configuracaoFiltro)
                    throw new \Exception('<strong>Desculpe</strong>, mas não foi possível atualizar a sua carteira de clientes pois o filtro aplicado pelo seu gestor não retornou nenhuma oportunidade!<br>Entre em contato com o seu gestor para que ele aplique um novo filtro.');
                else
                    throw new \Exception('<strong>Desculpe</strong>, mas não foi possível atualizar a sua carteira de clientes pois a tabela de clientes encontra-se vazia!<br>Entre em contato com o administrador do sistema.');
            }

            FichaClienteController::cadastraLoteDeFichas($carteira_id, $clientesNovosId);

            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();

            throw $e;
        }
    }

    private function retornaClientesParaTransformarEmFichasEAplicaFiltros(ConfiguracaoFiltro $configuracaoFiltro, $quantidadeFichasNovas){
        try {
            if($configuracaoFiltro == null)
                $clientesNovosId = Cliente::whereNotIn('id', $this->retornaClientesIgnoradosPeloUsuarioEClientesQueJaEstaoSendoUtilizados(UsuarioController::retornaUsuarioLogado()))
                    ->lockForUpdate()
                    ->inRandomOrder()
                    ->take($quantidadeFichasNovas)
                    ->pluck('id')
                    ->toArray();
            else{
                $clientesParaIgnorar = implode(',', $this->retornaClientesIgnoradosPeloUsuarioEClientesQueJaEstaoSendoUtilizados(UsuarioController::retornaUsuarioLogado()));

                $consultaSql = $configuracaoFiltro->retornaFichasDoFiltroConfiguradoParaCarteiraDeUsuario($quantidadeFichasNovas, $clientesParaIgnorar);

                $clientesNovosId = $consultaSql->count() > 0 ? $consultaSql->toArray() : null;
            }

            return $clientesNovosId;
        }catch (\Exception $ex){
            throw $ex;
        }
    }

    private function retornaClientesIgnoradosPeloUsuarioEClientesQueJaEstaoSendoUtilizados(User $usuario){
        try {
            if($usuario->funcionario->empresa_id == null)
                throw new \Exception('Não foi possível criar/atualizar a carteira de clientes do usuário devido a falta de atualização cadastral por parte da equipe GSI. Informe isso ao nosso administrativo!');

            $empresaId = $usuario->funcionario->empresa_id;

            $clientesQueJaEstaoSendoUtilizados = FichaCliente::with('')
                ->whereHas('carteira', function (Builder $carteira) use ($empresaId){
                    $carteira->whereHas('usuario', function (Builder $usuario) use ($empresaId) {
                        $usuario->whereHas('funcionario', function (Builder $funcionario) use ($empresaId) {
                            $funcionario->where('empresa_id', '=', $empresaId);
                        });
                    });
                })->pluck('clientes_id');

            $clientesQueJaEstaoSendoUtilizados = $clientesQueJaEstaoSendoUtilizados->count() > 0 ? $clientesQueJaEstaoSendoUtilizados->toArray() : [];

            $clientesQueOUsuarioIgnorou = FichaCliente::withTrashed()
                ->whereHas('historicoStatus', function (Builder $queryHistStatus) use ($usuario){
                    $queryHistStatus->where([
                        ['users_id', '=', $usuario->id],
                        ['status_ficha_id', '=', 9],
                        ['deleted_at', '=', null]
                    ]);
                })
                ->pluck('clientes_id');

            $clientesQueOUsuarioIgnorou = $clientesQueOUsuarioIgnorou->count() > 0 ? $clientesQueOUsuarioIgnorou->toArray() : [];

            return array_merge($clientesQueJaEstaoSendoUtilizados, $clientesQueOUsuarioIgnorou);
        }catch (\Exception $ex) {
            throw $ex;
        }
    }

    public static function resetaCarteiraDeFichasEmStatusDeDisponivelPorEquipePerfilOuUsuario($equipe = null,
                                                                                              $usuario = null){
        if($equipe != null) {
            $carteiras = collect(DB::table('carteira_ficha_clientes')
                ->join('users', 'carteira_ficha_clientes.users_id', '=', 'users.id')
                ->join('funcionarios', 'users.funcionarios_id', '=', 'funcionarios.id')
                ->whereRaw('funcionarios.equipes_id = ' . $equipe)
                ->selectRaw('carteira_ficha_clientes.*')
                ->get());

            foreach ($carteiras as $carteira){
                FichaClienteController::excluiTodasAsFichasPorCarteiraNoStatusDeDisponivel($carteira->id);
            }
        }
        /*elseif ($perfil != null){
            $usuarioLogado = User::with('funcionario.equipe')->findOrFail(Auth::user()->getAuthIdentifier());

            $usuariosParaExcluirACarteira = User::with('perfil',
                'carteiras',
                'funcionario.equipe')->where('perfil_id', '=', $perfil);

            if($usuarioLogado->funcionario->equipe)
                $usuariosParaExcluirACarteira = $usuariosParaExcluirACarteira->where('equipes_id', '=', $usuarioLogado->funcionario->equipe->id);

            $usuariosParaExcluirACarteira = $usuariosParaExcluirACarteira->get();

            foreach ($usuariosParaExcluirACarteira as $usuario){
                foreach ($usuario->carteiras as $carteira){
                    FichaClienteController::excluiTodasAsFichasPorCarteiraNoStatusDeDisponivel($carteira->id);
                }
            }
        }*/
        elseif ($usuario != null){
            $usuario = User::with('carteiras')->findOrFail($usuario);

            foreach ($usuario->carteiras as $carteira){
                FichaClienteController::excluiTodasAsFichasPorCarteiraNoStatusDeDisponivel($carteira->id);
            }

        }
    }
}
