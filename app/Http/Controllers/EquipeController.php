<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Equipe;
use App\Models\FuncaoAcesso;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EquipeCreateRequest;
use Illuminate\Support\Facades\Auth;

class EquipeController extends Controller
{
    /**
     * Retorna todos os equipes cadastrados
     *
     * @param array $relacionamentos
     * @param array $ordernacao
     * @param bool $somenteAtivos
     * @return Departamento|Builder|\Illuminate\Database\Query\Builder
     *
     */
    public static function listaTodasAsEquipesCadastradas(array $relacionamentos = [], array $ordernacao = [], $somenteAtivos = false){
        $dadosCadastrados = Equipe::withTrashed()->with($relacionamentos);

        if($somenteAtivos)
            $dadosCadastrados = $dadosCadastrados->whereNull('deleted_at');

        if(count($ordernacao) > 0){
            $campo = $ordernacao[0];
            $tipoOrdenacao = null;

            if(count($ordernacao) == 2){
                $campo = $ordernacao[0];
                $tipoOrdenacao = $ordernacao[1];
            }

            $dadosCadastrados = $dadosCadastrados->orderBy($campo, $tipoOrdenacao == null ? 'asc' : $tipoOrdenacao);
        }

        return $dadosCadastrados;
    }

    /**
     * Lista equipes de uma empresa
     *
     * @param int $empresaId
     * @param array $relacionamentos
     * @param array $ordernacao
     * @param bool $somenteAtivos
     * @return Builder|\Illuminate\Database\Query\Builder
     * @throws \Exception
     *
     */
    public static function listaEquipesPorEmpresa(int $empresaId = 0, array $relacionamentos = [], array $ordernacao = [], $somenteAtivos = false){
        if($empresaId === 0 || $empresaId == null)
            throw new \Exception('Erro! Selecione uma empresa válida.');

        return self::listaTodasAsEquipesCadastradas($relacionamentos, $ordernacao, $somenteAtivos)->where('empresa_id', '=', $empresaId)->get();
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function listaEquipesPorEmpresaJson(Request $request){
        try {
            $empresaId = $request->has('empresaId') ? $request['empresaId'] : null;
            $relacionamentos = $request->has('relacionamentos') ? $request['relacionamentos'] : [];
            $ordenacao = $request->has('ordenacao') ? $request['ordenacao'] : [];
            $somenteAtivos = $request->has('somenteAtivos') ? $request['somenteAtivos'] : false;

            if($empresaId === '0' || $empresaId === 0 || $empresaId == null)
                throw new \Exception('Selecione uma empresa válida.');

            return self::listaTodasAsEquipesCadastradas($relacionamentos, $ordenacao, $somenteAtivos)
                ->whereHas('departamento', function (Builder $qDepartamento) use($empresaId){
                    $qDepartamento->withTrashed()
                        ->where('empresa_id', '=', $empresaId);
                })
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
        $usuarioLogado = UsuarioController::retornaUsuarioLogado();

        if(!$usuarioLogado->perfil->possuiPermissaoDeAcesso(FuncaoAcesso::where('rota','/equipes')->get()->implode('id')))
            return redirect()->back()->with('info', 'O usuário não tem permissão para acessar essa funcionalidade.');

        if ($usuarioLogado->perfil->super_user) {
            $empresasOuEmpresa = EmpresaController::listaTodasAsEmpresasCadastradas(['departamentos.empresa', 'departamentos.equipes'])->get();
        } else {
            $empresasOuEmpresa = EmpresaController::listaTodasAsEmpresasCadastradas(['departamentos.empresa', 'departamentos.equipes'])->findOrFail($usuarioLogado->funcionario->empresa->id);
        }

        return view('configuracao.equipes.index', compact('empresasOuEmpresa', 'usuarioLogado'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EquipeCreateRequest $request)
    {
        try{
            $novoEquipe = new Equipe();
            $novoEquipe->nome = $request->nome;
            $novoEquipe->departamentos_id = $request->departamento['id'];
            $novoEquipe->save();

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Equipe cadastrada com sucesso.',
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                'status_resposta' => 'error',
                'msg'    => $e->getMessage(),
            ], 422);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EquipeCreateRequest $request, $id)
    {
        try{
            $equipeEditado = Equipe::findOrFail($id);
            $equipeEditado->nome = $request->nome;
            $equipeEditado->departamentos_id = $request->departamento['id'];
            $equipeEditado->save();

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Equipe alterada com sucesso.',
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                'status_resposta' => 'error',
                'msg'    => $e->getMessage(),
            ], 422);
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
        try{
            $equipeParaExclusao = Equipe::findOrFail($id);

            if($equipeParaExclusao)
                $equipeParaExclusao->delete();
            else
                throw new \Exception('Equipe não encontrada! A mesma já pode ter sido excluída por outro usuário.');

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Equipe excluida com sucesso. Os filtros associados a essa equipe também foram excluídos.',
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                'status_resposta' => 'error',
                'msg'    => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Restore the specified resource to storage
     *
     * @param $id
     * @return JsonResponse
     */
    public function restore($id){
        try {
            $equipe = Equipe::with('departamento')->withTrashed()->findOrFail($id);

            if(!$equipe->departamento->ativo)
                throw new \Exception('Não é possível reativar essa equipe pois seu departamento encontra-se excluído.');

            if($equipe)
                $equipe->restore();
            else
                throw new \Exception('Essa equipe não existe na base de dados!');

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Equipe reativada com sucesso! Esse processo não reativa automaticamente os cadastros associados a equipe, como os filtros. Se fazendo necessário reativar cada cadastro um por um. ',
            ], 201);
        }catch (\Exception $ex){
            return response()->json([
                'status_resposta' => 'error',
                'msg'    => $ex->getMessage(),
            ], 422);
        }
    }
}
