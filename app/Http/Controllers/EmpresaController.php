<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpresaPostOrUpdateRequestValidation;
use App\Models\Empresa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
{

    /**
     * Função responsável por listar as empresas cadastradas e possui filtros.
     *
     * @param array $relacionamentos
     * @param array $ordernacao
     * @param bool $somenteAtivos
     * @return Empresa|\Illuminate\Database\Eloquent\Builder
     *
     */
    public static function listaTodasAsEmpresasCadastradas(array $relacionamentos = [], array $ordernacao = [], $somenteAtivos = false){
        $usuarioLogado = UsuarioController::retornaUsuarioLogado();

        $empresasCadastradas = Empresa::withTrashed()->with($relacionamentos);

        if($usuarioLogado->perfil->super_user)
            $empresasCadastradas = $empresasCadastradas->where('in', '=', 0);

        if($somenteAtivos)
            $empresasCadastradas = $empresasCadastradas->whereNull('deleted_at');

        if(count($ordernacao) > 0){
            $campo = $ordernacao[0];
            $tipoOrdenacao = null;

            if(count($ordernacao) == 2){
                $campo = $ordernacao[0];
                $tipoOrdenacao = $ordernacao[1];
            }

            $empresasCadastradas = $empresasCadastradas->orderBy($campo, $tipoOrdenacao == null ? 'asc' : $tipoOrdenacao);
        }

        return $empresasCadastradas;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            $usuarioLogado = UsuarioController::retornaUsuarioLogado();
            if(!$usuarioLogado->perfil->super_user)
                throw new \Exception('Você não possui permissão para acessar este recurso.');

            $empresasCadastradas = self::listaTodasAsEmpresasCadastradas()->get();
            return view('configuracao.empresas.index', compact('empresasCadastradas'));
        }catch (\Exception $ex){
            return redirect()->back()->with('info', $ex->getMessage());
        }
    }

    public function retornaEmpresasCadastradas(){
        try {
            $usuarioLogado = UsuarioController::retornaUsuarioLogado();
            if(!$usuarioLogado->perfil->super_user)
                throw new \Exception('Você não possui permissão para acessar este recurso.');

            return self::listaTodasAsEmpresasCadastradas()->get()->toArray();

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
     * @param EmpresaPostOrUpdateRequestValidation $request
     * @return \Illuminate\HttRequestp\Response
     */
    public function store(EmpresaPostOrUpdateRequestValidation $request)
    {
        DB::beginTransaction();

        try{
            $usuarioLogado = UsuarioController::retornaUsuarioLogado();
            if(!$usuarioLogado->perfil->super_user)
                throw new \Exception('Você não possui permissão para acessar este recurso.');


            $empresa = new Empresa();
            $empresa->nome_fantasia = $request->nome_fantasia;
            $empresa->cpf_cnpj = $request->cpf_cnpj;
            $empresa->razao_social = $request->razao_social;
            $empresa->quantidade_licencas = $request->quantidade_licencas;
            $empresa->save();

            DB::commit();

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Empresa cadastrada com sucesso.',
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
     * @param EmpresaPostOrUpdateRequestValidation $request
     * @param int $id
     * @return Response
     */
    public function update(EmpresaPostOrUpdateRequestValidation $request, $id)
    {
        DB::beginTransaction();

        try{
            $usuarioLogado = UsuarioController::retornaUsuarioLogado();
            if(!$usuarioLogado->perfil->super_user)
                throw new \Exception('Você não possui permissão para acessar este recurso.');

            $empresa = Empresa::findOrFail($id);
            $empresa->nome_fantasia = $request->nome_fantasia;
            $empresa->cpf_cnpj = $request->cpf_cnpj;
            $empresa->razao_social = $request->razao_social;
            $empresa->quantidade_licencas = $request->quantidade_licencas;
            $empresa->save();

            DB::commit();

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Empresa alterada com sucesso.',
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
     * @return JsonResponse
     */
    public function destroy($id){
        try {
            $usuarioLogado = UsuarioController::retornaUsuarioLogado();
            if(!$usuarioLogado->perfil->super_user)
                throw new \Exception('Você não possui permissão para acessar este recurso.');

            $empresa = Empresa::findOrFail($id);
            if($empresa)
                $empresa->delete();
            else
                throw new \Exception('Essa empresa já tinha sido excluída!');

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Empresa excluída com sucesso! Foram excluídos também os departamentos, cargos, equipes e funcionários associados a essa empresa.',
            ], 201);
        }catch (\Exception $ex){
            return response()->json([
                'status_resposta' => 'error',
                'msg'    => $ex->getMessage(),
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
            $usuarioLogado = UsuarioController::retornaUsuarioLogado();
            if(!$usuarioLogado->perfil->super_user)
                throw new \Exception('Você não possui permissão para acessar este recurso.');

            $empresa = Empresa::withTrashed()->findOrFail($id);
            if($empresa)
                $empresa->restore();
            else
                throw new \Exception('Essa empresa não existe na base de dados!');

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Empresa reativada com sucesso! Esse processo não reativa automaticamente os cadastros associados a empresa, como (departamentos, equipes, cargos e funcionários). Se fazendo necessário reativar cada cadastro um por um. ',
            ], 201);
        }catch (\Exception $ex){
            return response()->json([
                'status_resposta' => 'error',
                'msg'    => $ex->getMessage(),
            ], 422);
        }
    }

    /*
    public function alteraStatusEmpresa($id){

        try {
            $usuarioLogado = UsuarioController::retornaUsuarioLogado();
            if(!$usuarioLogado->perfil->super_user)
                throw new \Exception('Você não possui permissão para acessar este recurso.');

            $empresa = Empresa::withTrashed()->findOrFail($id);

            if($empresa->ativo)
                $empresa->delete();
            else
                $empresa->restore();

            if($empresa->save()){
                return response()->json([
                    'status_resposta' => 'success',
                    'msg'    => 'Empresa alterada com sucesso.',
                ], 201);
            }else{
                throw new \Exception('Ocorreu um erro ao tentar realizar a alteração de status para o idioma, entre em contato com o administrador do sistema enviando os dados que foram informados no formulário.');
            }
        }catch (\Exception $ex){
            return response()->json([
                'status_resposta' => 'error',
                'msg'    => $ex->getMessage(),
            ], 422);
        }
    }
    */

}
