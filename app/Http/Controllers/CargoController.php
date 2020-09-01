<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Exception;
use App\Http\Requests\CargoCreateRequest;
use App\Models\Cargo;
use App\Models\Departamento;
use App\Models\FuncaoAcesso;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CargoController extends Controller
{
    /**
     * Retorna todos os cargos cadastrados
     *
     * @param array $relacionamentos
     * @param array $ordernacao
     * @param bool $somenteAtivos
     * @return Departamento|Builder|\Illuminate\Database\Query\Builder
     *
     */
    public static function listaTodosOsCargosCadastrados(array $relacionamentos = [], array $ordernacao = [], $somenteAtivos = false){
        $dadosCadastrados = Cargo::withTrashed()->with($relacionamentos);

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
     * Lista cargos de uma empresa
     *
     * @param int $empresaId
     * @param array $relacionamentos
     * @param array $ordernacao
     * @param bool $somenteAtivos
     * @return Builder|\Illuminate\Database\Query\Builder
     * @throws \Exception
     *
     */
    public static function listaCargosPorEmpresa(int $empresaId = 0, array $relacionamentos = [], array $ordernacao = [], $somenteAtivos = false){
        if($empresaId === 0 || $empresaId == null)
            throw new \Exception('Erro! Selecione uma empresa válida.');

        return self::listaTodosOsCargosCadastrados($relacionamentos, $ordernacao, $somenteAtivos)
            ->whereHas('departamento', function (Builder $qDepartamento) use($empresaId){
                $qDepartamento->where('empresa_id', '=', $empresaId);
            })
            ->get();
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function listaCargosPorEmpresaJson(Request $request){
        try {
            $empresaId = $request->has('empresaId') ? $request['empresaId'] : null;
            $relacionamentos = $request->has('relacionamentos') ? $request['relacionamentos'] : [];
            $ordenacao = $request->has('ordenacao') ? $request['ordenacao'] : [];
            $somenteAtivos = $request->has('somenteAtivos') ? $request['somenteAtivos'] : false;

            if($empresaId === '0' || $empresaId === 0 || $empresaId == null)
                throw new \Exception('Selecione uma empresa válida.');

            return self::listaTodosOsCargosCadastrados($relacionamentos, $ordenacao, $somenteAtivos)
                ->whereHas('departamento', function (Builder $qDepartamento) use($empresaId){
                    $qDepartamento->where('empresa_id', '=', $empresaId);
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
        $usuarioLogado = User::with('perfil',
            'perfil.funcoesDeAcesso',
            'funcionario.empresa')->findOrFail(Auth::user()->getAuthIdentifier());

        if (!$usuarioLogado->perfil->possuiPermissaoDeAcesso(FuncaoAcesso::where('rota', '/cargos')->get()->implode('id')))
            return redirect()->back()->with('info', 'O usuário não tem permissão para acessar essa funcionalidade.');

        if ($usuarioLogado->perfil->super_user) {
            $empresasOuEmpresa = EmpresaController::listaTodasAsEmpresasCadastradas(['departamentos.empresa', 'departamentos.cargos'])->get();
        } else {
            $empresasOuEmpresa = EmpresaController::listaTodasAsEmpresasCadastradas(['departamentos.empresa', 'departamentos.cargos'])->findOrFail($usuarioLogado->funcionario->empresa->id);
        }

        return view('configuracao.cargos.index', compact('empresasOuEmpresa', 'usuarioLogado'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CargoCreateRequest $request)
    {
        try{
            $novoCargo = new Cargo();
            $novoCargo->nome = $request->nome;
            $novoCargo->departamentos_id = $request->departamento['id'];
            $novoCargo->save();

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Cargo cadastrado com sucesso.',
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
    public function update(CargoCreateRequest $request, $id)
    {
        try{
            $cargoEditado = Cargo::findOrFail($id);
            $cargoEditado->nome = $request->nome;
            $cargoEditado->departamentos_id = $request->departamento['id'];
            $cargoEditado->save();

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Cargo alterado com sucesso.',
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
            $cargoParaExclusao = Cargo::findOrFail($id);

            if($cargoParaExclusao)
                $cargoParaExclusao->delete();
            else
                throw new \Exception('Cargo não encontrado! O mesmo já pode ter sido excluído por outro usuário.');

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Cargo excluido com sucesso.',
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
            $cargo = Cargo::with('departamento')->withTrashed()->findOrFail($id);

            if(!$cargo->departamento->ativo)
                throw new \Exception('Não é possível reativar esse cargo pois seu departamento encontra-se excluído.');

            if($cargo)
                $cargo->restore();
            else
                throw new \Exception('Esse cargo não existe na base de dados!');

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Cargo reativado com sucesso! Esse processo não reativa automaticamente os cadastros associados ao cargo, como funcionários. Se fazendo necessário reativar cada cadastro um por um. ',
            ], 201);
        }catch (\Exception $ex){
            return response()->json([
                'status_resposta' => 'error',
                'msg'    => $ex->getMessage(),
            ], 422);
        }
    }
}
