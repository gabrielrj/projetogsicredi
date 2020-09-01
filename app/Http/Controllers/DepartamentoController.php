<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartamentoCreateRequest;
use App\Models\Departamento;
use App\Models\FuncaoAcesso;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartamentoController extends Controller
{
    /**
     * Retorna todos os departamentos cadastrados
     *
     * @param array $relacionamentos
     * @param array $ordernacao
     * @param bool $somenteAtivos
     * @return Departamento|Builder|\Illuminate\Database\Query\Builder
     *
     */
    public static function listaTodosOsDepartamentosCadastrados(array $relacionamentos = [], array $ordernacao = [], $somenteAtivos = false){
        $dadosCadastrados = Departamento::withTrashed()->with($relacionamentos);

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
     * Lista departamentos de uma empresa
     *
     * @param int $empresaId
     * @param array $relacionamentos
     * @param array $ordernacao
     * @param bool $somenteAtivos
     * @return Builder|\Illuminate\Database\Query\Builder
     * @throws \Exception
     *
     */
    public static function listaDepartamentosPorEmpresa(int $empresaId = 0, array $relacionamentos = [], array $ordernacao = [], $somenteAtivos = false){
        if($empresaId === 0 || $empresaId == null)
            throw new \Exception('Erro! Selecione uma empresa válida.');

        return self::listaTodosOsDepartamentosCadastrados($relacionamentos, $ordernacao, $somenteAtivos)->where('empresa_id', '=', $empresaId)->get();
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function listaDepartamentosPorEmpresaJson(Request $request){
        try {
            $empresaId = $request->has('empresaId') ? $request['empresaId'] : null;
            $relacionamentos = $request->has('relacionamentos') ? $request['relacionamentos'] : [];
            $ordenacao = $request->has('ordenacao') ? $request['ordenacao'] : [];
            $somenteAtivos = $request->has('somenteAtivos') ? $request['somenteAtivos'] : false;

            if($empresaId === '0' || $empresaId === 0 || $empresaId == null)
                throw new \Exception('Selecione uma empresa válida.');

            return self::listaTodosOsDepartamentosCadastrados($relacionamentos, $ordenacao, $somenteAtivos)
                ->where('empresa_id', '=', $empresaId)
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
        $usuarioLogado = User::with('perfil','perfil.funcoesDeAcesso', 'funcionario.empresa')->findOrFail(Auth::user()->getAuthIdentifier());

        if(!$usuarioLogado->perfil->possuiPermissaoDeAcesso(FuncaoAcesso::where('rota','/departamentos')->get()->implode('id')))
            return redirect()->back()->with('info', 'O usuário não tem permissão para acessar essa funcionalidade.');

        if ($usuarioLogado->perfil->super_user) {
            $empresasOuEmpresa = EmpresaController::listaTodasAsEmpresasCadastradas(['departamentos'])->get();
        } else {
            $empresasOuEmpresa = EmpresaController::listaTodasAsEmpresasCadastradas(['departamentos'])->findOrFail($usuarioLogado->funcionario->empresa->id);
        }

        return view('configuracao.departamentos.index', compact('empresasOuEmpresa', 'usuarioLogado'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartamentoCreateRequest $request)
    {
        try{
            $novoDepartamento = new Departamento();
            $novoDepartamento->empresa_id = $request->empresa['id'];
            $novoDepartamento->nome = $request->nome;
            $novoDepartamento->save();

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Departamento cadastrado com sucesso.',
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
    public function update(DepartamentoCreateRequest $request, $id)
    {
        try{
            $departamentoEditado = Departamento::findOrFail($id);
            $departamentoEditado->nome = $request->nome;
            $departamentoEditado->save();

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Departamento alterado com sucesso.',
                'departamento' => $departamentoEditado,
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
            $departamento = Departamento::findOrFail($id);

            if($departamento)
                $departamento->delete();
            else
                throw new \Exception('Departamento não encontrado! O mesmo já pode ter sido excluído por outro usuário.');

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Departamento excluido com sucesso. Foram excluídos também os cargos e equipes associados a ele!',
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
            $departamento = Departamento::withTrashed()->with('empresa')->findOrFail($id);

            if(!$departamento->empresa->ativo)
                throw new \Exception('Não é possível reativar esse departamento pois a empresa encontra-se excluída.');

            if($departamento)
                $departamento->restore();
            else
                throw new \Exception('Esse departamento não existe na base de dados!');

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Departamento reativado com sucesso! Esse processo não reativa automaticamente os cadastros associados ao departamento, como (equipes, cargos e funcionários). Se fazendo necessário reativar cada cadastro um por um. ',
            ], 201);
        }catch (\Exception $ex){
            return response()->json([
                'status_resposta' => 'error',
                'msg'    => $ex->getMessage(),
            ], 422);
        }
    }
}
