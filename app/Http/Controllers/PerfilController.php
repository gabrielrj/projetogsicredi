<?php

namespace App\Http\Controllers;

use App\Models\FuncaoAcesso;
use App\Models\Menu;
use App\Models\Perfil;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerfilController extends Controller
{
    /**
     * Retorna todos os perfis cadastrados
     *
     * @param array $relacionamentos
     * @param array $ordernacao
     * @param bool $somenteAtivos
     * @return Departamento|Builder|\Illuminate\Database\Query\Builder
     *
     */
    public static function listaTodosOsPerfisCadastrados(array $relacionamentos = [], array $ordernacao = [], $somenteAtivos = false){
        $dadosCadastrados = Perfil::withTrashed()->with($relacionamentos)
            ->where('super_user', '=', 0);

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
     * Lista perfis de uma empresa
     *
     * @param int $empresaId
     * @param array $relacionamentos
     * @param array $ordernacao
     * @param bool $somenteAtivos
     * @return Builder|\Illuminate\Database\Query\Builder
     * @throws \Exception
     *
     */
    public static function listaPerfisPorEmpresa(int $empresaId = 0, array $relacionamentos = [], array $ordernacao = [], $somenteAtivos = false){
        if($empresaId === 0 || $empresaId == null)
            throw new \Exception('Erro! Selecione uma empresa válida.');

        return self::listaTodosOsPerfisCadastrados($relacionamentos, $ordernacao, $somenteAtivos)->where('empresa_id', '=', $empresaId)->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarioLogado = UsuarioController::retornaUsuarioLogado();

        if(!$usuarioLogado->perfil->possuiPermissaoDeAcesso(FuncaoAcesso::where('rota','/perfis')->get()->implode('id')))
            return redirect()->back()->with('info', 'O usuário não tem permissão para acessar essa funcionalidade.');

        if ($usuarioLogado->perfil->super_user) {
            $empresasOuEmpresa = EmpresaController::listaTodasAsEmpresasCadastradas(['perfis.empresa'])->get();
        } else {
            $empresasOuEmpresa = EmpresaController::listaTodasAsEmpresasCadastradas(['perfis.empresa'])->findOrFail($usuarioLogado->funcionario->empresa->id);
        }

        $funcoesDeAcesso = Menu::with('submenus', 'submenus.funcoesDeAcesso', 'funcoesDeAcesso')->get();

        return view('configuracao.perfis.index', compact('empresasOuEmpresa', 'funcoesDeAcesso', 'usuarioLogado'));
    }

    public static function listaTodosPerfisDeUsuario(){
        return Perfil::all();
    }

    public static function listaPerfisDeUsuarioQueOcupamUmaEquipe($equipeId){
        $perfis = DB::table('perfils')
            ->join('users', 'perfils.id', '=', 'users.perfil_id')
            ->join('funcionarios', 'users.funcionarios_id', '=', 'funcionarios.id')
            ->join('equipes', 'funcionarios.equipes_id', '=', 'equipes.id')
            ->whereRaw('equipes.id = ' . $equipeId)
            ->selectRaw('distinct perfils.id, perfils.nome')
            ->orderByRaw('perfils.nome asc')
            ->get();

        return collect($perfis);
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function listaPerfisPorEmpresaJson(Request $request){
        try {
            $empresaId = $request->has('empresaId') ? $request['empresaId'] : null;
            $relacionamentos = $request->has('relacionamentos') ? $request['relacionamentos'] : [];
            $ordenacao = $request->has('ordenacao') ? $request['ordenacao'] : [];
            $somenteAtivos = $request->has('somenteAtivos') ? $request['somenteAtivos'] : false;

            if($empresaId === '0' || $empresaId === 0 || $empresaId == null)
                throw new \Exception('Selecione uma empresa válida.');

            return self::listaTodosOsPerfisCadastrados($relacionamentos, $ordenacao, $somenteAtivos)
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try{
            $usuarioLogado = UsuarioController::retornaUsuarioLogado();

            if(!$usuarioLogado->perfil->possuiPermissaoDeAcesso(FuncaoAcesso::where('rota','/perfis')->get()->implode('id')))
                throw new \Exception('O usuário não tem permissão para acessar essa funcionalidade.');

            $novoPerfil = new Perfil();
            $novoPerfil->empresa_id = $request->empresa_id;
            $novoPerfil->nome = $request->nome;
            $novoPerfil->save();

            $novoPerfil->funcoesDeAcesso()->sync($request->permissoes);

            DB::commit();

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Perfil cadastrado com sucesso.',
            ], 201);

        }catch (\Exception $e){
            DB::rollBack();

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
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try{
            $usuarioLogado = UsuarioController::retornaUsuarioLogado();

            if(!$usuarioLogado->perfil->possuiPermissaoDeAcesso(FuncaoAcesso::where('rota','/perfis')->get()->implode('id')))
                throw new \Exception('O usuário não tem permissão para acessar essa funcionalidade.');

            $perfil = Perfil::findOrFail($id);
            $perfil->nome = $request->nome;
            $perfil->save();

            $perfil->funcoesDeAcesso()->sync($request->permissoes);

            DB::commit();

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Perfil atualizado com sucesso.',
            ], 201);

        }catch (\Exception $e){
            DB::rollBack();

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
            $perfil = Perfil::findOrFail($id);

            if($perfil)
                $perfil->delete();
            else
                throw new \Exception('Perfil não encontrado, já pode ter sido excluído por outro usuário!');

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Perfil excluido com sucesso.',
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
            $perfil = Perfil::with('empresa')->withTrashed()->findOrFail($id);

            if(!$perfil->empresa->ativo)
                throw new \Exception('Não é possível reativar esse perfil pois a empresa encontra-se excluída.');

            if($perfil)
                $perfil->restore();
            else
                throw new \Exception('Esse perfil não existe na base de dados!');

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Perfil reativado com sucesso!',
            ], 201);
        }catch (\Exception $ex){
            return response()->json([
                'status_resposta' => 'error',
                'msg'    => $ex->getMessage(),
            ], 422);
        }
    }
}
