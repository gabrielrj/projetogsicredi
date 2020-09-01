<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracaoFiltro;
use App\Models\FuncaoAcesso;
use App\User;
use App\Util\ConfiguraFiltro;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConfiguracaoFiltroController extends Controller
{
    /**
     * Função responsável por listar as configurações de filtros cadastradas e possui filtros.
     *
     * @param array $relacionamentos
     * @param array $ordernacao
     * @param bool $somenteAtivos
     * @return ConfiguracaoFiltro|Builder
     *
     */
    public static function listaTodosOsFiltrosCadastrados(array $relacionamentos = [], array $ordernacao = [], $somenteAtivos = false, $empresaId = null){
        $filtrosCadastrados = ConfiguracaoFiltro::withTrashed()->with($relacionamentos);

        if($somenteAtivos)
            $filtrosCadastrados = $filtrosCadastrados->whereNull('deleted_at');

        if($empresaId != null){
            $filtrosCadastrados = $filtrosCadastrados->whereHas('equipe', function (Builder $equipe) use($empresaId){
                $equipe->whereHas('departamento', function (Builder $departamento) use($empresaId){
                    $departamento->where('empresa_id', $empresaId);
                });
            })->orWhereHas('usuario', function (Builder $usuario) use($empresaId){
                $usuario->whereHas('funcionario', function (Builder $funcionario) use($empresaId){
                    $funcionario->where('empresa_id', $empresaId);
                });
            });
        }

        if(count($ordernacao) > 0){
            $campo = $ordernacao[0];
            $tipoOrdenacao = null;

            if(count($ordernacao) == 2){
                $campo = $ordernacao[0];
                $tipoOrdenacao = $ordernacao[1];
            }

            $filtrosCadastrados = $filtrosCadastrados->orderBy($campo, $tipoOrdenacao == null ? 'asc' : $tipoOrdenacao);
        }

        return $filtrosCadastrados;
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function listaFiltrosPorEmpresaJson(Request $request){
        try {
            $empresaId = $request->has('empresaId') ? $request['empresaId'] : null;
            $relacionamentos = $request->has('relacionamentos') ? $request['relacionamentos'] : [];
            $ordenacao = $request->has('ordenacao') ? $request['ordenacao'] : [];
            $somenteAtivos = $request->has('somenteAtivos') ? $request['somenteAtivos'] : false;

            if($empresaId === '0' || $empresaId === 0 || $empresaId == null)
                throw new \Exception('Selecione uma empresa válida.');

            return self::listaTodosOsFiltrosCadastrados($relacionamentos, $ordenacao, $somenteAtivos, $empresaId)
                ->get()
                ->toArray();
        }catch (\Exception $ex){
            return response()->json([
                'status_resposta' => 'error',
                'msg'    => $ex->getMessage(),
            ], 422);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarioLogado = User::with('perfil','perfil.funcoesDeAcesso', 'funcionario.equipe', 'funcionario.empresa')->findOrFail(Auth::user()->getAuthIdentifier());

        if(!$usuarioLogado->perfil->possuiPermissaoDeAcesso(FuncaoAcesso::where('rota','/configuracaofiltros')->get()->implode('id')))
            return redirect()->back()->with('info', 'O usuário não tem permissão para acessar essa funcionalidade.');

        if ($usuarioLogado->perfil->super_user) {
            $empresasOuEmpresa = EmpresaController::listaTodasAsEmpresasCadastradas(['funcionarios.usuario'])->get();
        } else {
            $empresasOuEmpresa = EmpresaController::listaTodasAsEmpresasCadastradas(['funcionarios.usuario'])->findOrFail($usuarioLogado->funcionario->empresa->id);
        }

        $cargos = ConfiguraFiltro::retornaCargosEEsferasClientes();
        //$cargos = collect();
        $estadosECidades = ConfiguraFiltro::retornaEstadosECidadesClientes();
        //$estadosECidades = collect();
        //$situacoes = ConfiguraFiltro::retornaSituacoesClientes();
        //$situacoes = collect();
        $bancosRec = ConfiguraFiltro::retornaBancosRecClientes();
        //$bancosRec = collect();
        $bancosEmp = ConfiguraFiltro::retornaBancosEmpClientes();
        //$bancosEmp = collect();

        return view('vendas.gestor.filtro', compact('cargos',
            'estadosECidades',
            'bancosRec',
            'bancosEmp',
            'usuarioLogado',
            'empresasOuEmpresa'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $equipe_id = $request->equipe_id == 0 ? null : $request->equipe_id;
            $usuario_id = $request->usuario_id == 0 ? null : $request->usuario_id;

            $idade = UtilController::retornaIntOuNulo($request->idade);
            $idade_max = UtilController::retornaIntOuNulo($request->idade_max);

            if($idade != null && $idade_max != null){
                if($idade > $idade_max)
                    throw new \Exception('A idade mínima não pode ser maior que a idade maxima.');
                if($idade_max < $idade)
                    throw new \Exception('A idade maxima não pode ser maior que a idade mínima');
            }


            $rendaMinima = UtilController::retornaValorDecimalDeString($request->renda);
            $rendaMinima = $rendaMinima == 0.0 ? null : $rendaMinima;

            $margemMinima = UtilController::retornaValorDecimalDeString($request->margem);
            $margemMinima = $margemMinima == 0.0 ? null : $margemMinima;

            $parcelaMinima = UtilController::retornaValorDecimalDeString($request->parcela);
            $parcelaMinima = $parcelaMinima == 0.0 ? null : $parcelaMinima;

            $qtd_par_totais = UtilController::retornaIntOuNulo($request->qtd_par_totais);
            $qtd_par_rest = UtilController::retornaIntOuNulo($request->qtd_par_rest);

            $somente_com_emprestimos = $request->somente_com_emprestimos;
            $somente_atualizados_confirme = $request->somente_atualizados_confirme;

            if($equipe_id != null)
                $this->deletaFiltroEquipe($equipe_id);
            if($usuario_id != null)
                $this->deletaFiltroUsuario($usuario_id);

            $novaConfiguracaoDeFiltro = ConfiguracaoFiltro::create([
                'equipe_id'=> $equipe_id,
                'perfil_id'=> null,
                'usuario_id'=> $usuario_id,
                'idade'=> $idade,
                'idade_max' => $idade_max,
                'renda'=> $rendaMinima,
                'margem'=> $margemMinima,
                'parcela'=>$parcelaMinima,
                'qtd_par_totais'=> $qtd_par_totais,
                'qtd_par_rest'=> $qtd_par_rest,
                'somente_com_emprestimos' => $somente_com_emprestimos,
                'somente_atualizados_confirme' => $somente_atualizados_confirme,
            ]);

            $this->salvaFiltroCargos($novaConfiguracaoDeFiltro, $request->filtro_cargos);
            //if($request->cidades == null )
            $this->salvaFiltroCidades($novaConfiguracaoDeFiltro, $request->filtro_cidades);
            //$this->salvaFiltroSituacoes($novaConfiguracaoDeFiltro, $request->situacoes);
            $this->salvaFiltroBancosRec($novaConfiguracaoDeFiltro, $request->filtro_bancos_recs);
            $this->salvaFiltroBancosEmp($novaConfiguracaoDeFiltro, $request->filtro_bancos_emps);

            if($novaConfiguracaoDeFiltro->total_fichas_filtradas == 0)
                throw new \Exception('Não foi possível cadastrar esse filtro pois ele retornou um total de 0 (zero) fichas. Por favor tente aplicar melhor os filtros disponibilizados no cadastro.');

            CarteiraFichaClienteController::resetaCarteiraDeFichasEmStatusDeDisponivelPorEquipePerfilOuUsuario($equipe_id,
                $usuario_id);

            DB::commit();

            $msgDeSucesso = 'Filtro salvo e aplicado com sucesso. Foram encontrados um total de '.$novaConfiguracaoDeFiltro->total_fichas_filtradas.' fichas com os filtros utilizados.';

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => $msgDeSucesso
            ], 201);
        }catch (\Exception $e){
            DB::rollBack();

            return response()->json([
                'status_resposta' => 'error',
                'msg'    => $e->getMessage(),
            ], 422);
        }

    }

    private function salvaFiltroCargos(ConfiguracaoFiltro $novaConfiguracaoFiltro, $cargos){
        if(count($cargos) > 0){
            foreach ($cargos as $c){
                $cargo = (object) $c;

                $novaConfiguracaoFiltro->filtro_cargos()->create([
                    'esfera' => $cargo->esfera,
                    'cargo' => $cargo->cargo,
                    'situacao' => $cargo->situacao,
                ]);
            }
        }
    }

    private function salvaFiltroCidades(ConfiguracaoFiltro $novaConfiguracaoFiltro, $cidadesEstado){
        if(count($cidadesEstado) > 0){
            foreach ($cidadesEstado as $c){
                $c = (object) $c;
                $estado = intval($c->ufs_id);
                $cidade = trim($c->cidade);

                $novaConfiguracaoFiltro->filtro_cidades()->create([
                    'ufs_id'=>$estado,
                    'cidade'=>$cidade
                ]);
            }
        }
    }

    private function salvaFiltroEstado(ConfiguracaoFiltro $novaConfiguracaoFiltro, $estado){
        $novaConfiguracaoFiltro->filtro_cidades()->create([
            'ufs_id'=>$estado,
            'cidade'=>'Todas as cidades'
        ]);
    }

    private function salvaFiltroSituacoes(ConfiguracaoFiltro $novaConfiguracaoFiltro, $situacoes){
        if(count($situacoes) > 0){
            foreach ($situacoes as $s){
                $novaConfiguracaoFiltro->filtro_situacoes()->create(['situacao'=>$s]);
            }
        }
    }

    private function salvaFiltroBancosRec(ConfiguracaoFiltro $novaConfiguracaoFiltro, $bancosRec){
        if(count($bancosRec) > 0){
            foreach ($bancosRec as $b){
                $b = (object) $b;
                $novaConfiguracaoFiltro->filtro_bancos_recs()->create(['banco'=>$b->banco]);
            }
        }
    }

    private function salvaFiltroBancosEmp(ConfiguracaoFiltro $novaConfiguracaoFiltro, $bancosEmp){
        if(count($bancosEmp) > 0){
            foreach ($bancosEmp as $b){
                $b = (object) $b;
                $novaConfiguracaoFiltro->filtro_bancos_emps()->create(['banco'=>$b->banco]);
            }
        }
    }

    public static function retornaConfiguracaoDoUsuario($usuarioId){
        $usuario = UsuarioController::retornaUsuarioPorId($usuarioId);

        $configuracaoFiltro = null;

        if($usuario->configuracaoFiltros != null)
            $configuracaoFiltro = $usuario->configuracaoFiltros;
        else
            if($usuario->perfil->configuracaoFiltros != null)
                $configuracaoFiltro = $usuario->perfil->configuracaoFiltros;
            else
                if($usuario->funcionario->equipe != null && $usuario->funcionario->equipe->configuracaoFiltros != null)
                    $configuracaoFiltro = $usuario->funcionario->equipe->configuracaoFiltros;

        return $configuracaoFiltro;

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $filtroParaExclusao = ConfiguracaoFiltro::findOrFail($id);

            if($filtroParaExclusao)
                $filtroParaExclusao->delete();
            else
                throw new \Exception('Filtro não encontrado! O mesmo já pode ter sido excluído por outro usuário.');

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Filtro(s) excluido(s) com sucesso.',
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                'status_resposta' => 'error',
                'msg'    => $e->getMessage(),
            ], 422);
        }
    }

    public function deletaFiltroEquipe($equipeId){
        $filtros = ConfiguracaoFiltro::where('equipe_id', '=', $equipeId)->get();

        foreach ($filtros as $filtro){
            $filtro->delete();
        }
    }

    public function deletaFiltroUsuario($usuarioId){
        $filtros = ConfiguracaoFiltro::where('usuario_id', '=', $usuarioId)->get();

        foreach ($filtros as $filtro){
            $filtro->delete();
        }
    }
}
