<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\FuncionarioPostOrUpdateRequestValidation;
use App\Models\FuncaoAcesso;
use App\Models\Funcionario;
use App\Models\SituacaoFuncionario;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Util\Util;

class FuncionarioController extends Controller
{
    /**
     * Lista todos os funcionários cadastrados com filtro
     *
     * @param array $relacionamentos
     * @param array $ordernacao
     * @param bool $somenteAtivos
     * @return Funcionario|Builder
     *
     */
    public static function listaTodosOsFuncionariosCadastrados(array $relacionamentos = [], array $ordernacao = [], $somenteAtivos = false){
        $funcionariosCadastrados = Funcionario::withTrashed()->with($relacionamentos);

        if($somenteAtivos){
            $funcionariosCadastrados = $funcionariosCadastrados->whereNull('deleted_at')
                ->whereHas('usuario', function(Builder $usuario) {
                    $usuario->whereNull('deleted_at')
                        ->where('active', '=', 1);
                });
        }

        if(count($ordernacao) > 0){
            $campo = $ordernacao[0];
            $tipoOrdenacao = null;

            if(count($ordernacao) == 2){
                $campo = $ordernacao[0];
                $tipoOrdenacao = $ordernacao[1];
            }

            $funcionariosCadastrados = $funcionariosCadastrados->orderBy($campo, $tipoOrdenacao == null ? 'asc' : $tipoOrdenacao);
        }

        return $funcionariosCadastrados;
    }

    /**
     * Retorna funcionários de uma equipe
     *
     * @param int $equipeId
     * @param array $relacionamentos
     * @param array $ordernacao
     * @param bool $somenteAtivos
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     *
     */
    public static function listaTodosOsFuncionariosDeUmaEquipe(int $equipeId = 0, array $relacionamentos = [], array $ordernacao = [], $somenteAtivos = false){
        if($equipeId == 0 || $equipeId == null)
            throw new \Exception('Erro! Selecione uma equipe válida.');

        return self::listaTodosOsFuncionariosCadastrados($relacionamentos, $ordernacao, $somenteAtivos)
            ->where('equipes_id', '=', $equipeId)->get();
    }

    /**
     * Retorna funcionários de uma empresa
     *
     * @param int $empresaId
     * @param array $relacionamentos
     * @param array $ordernacao
     * @param bool $somenteAtivos
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     *
     */
    public static function listaTodosOsFuncionariosDeUmaEmpresa(int $empresaId = 0, array $relacionamentos = [], array $ordernacao = [], $somenteAtivos = false){
        if($empresaId === 0 || $empresaId == null)
            throw new \Exception('Erro! Selecione uma empresa válida.');

        return self::listaTodosOsFuncionariosCadastrados($relacionamentos, $ordernacao, $somenteAtivos)
            ->where('empresa_id', '=', $empresaId)->get();
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function listaFuncionariosPorEmpresaJson(Request $request){
        try {
            $empresaId = $request->has('empresaId') ? $request['empresaId'] : null;
            $relacionamentos = $request->has('relacionamentos') ? $request['relacionamentos'] : [];
            $ordenacao = $request->has('ordenacao') ? $request['ordenacao'] : [];
            $somenteAtivos = $request->has('somenteAtivos') ? $request['somenteAtivos'] : false;

            if($empresaId === '0' || $empresaId === 0 || $empresaId == null)
                throw new \Exception('Selecione uma empresa válida.');

            $funcionarios = self::listaTodosOsFuncionariosCadastrados($relacionamentos, $ordenacao, $somenteAtivos)
                ->where('empresa_id', '=', $empresaId)
                ->get()
                ->toArray();

            return $funcionarios;
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
        try {
            $usuarioLogado = UsuarioController::retornaUsuarioLogado();

            if(!$usuarioLogado->perfil->possuiPermissaoDeAcesso(FuncaoAcesso::where('rota','/funcionarios')->get()->implode('id')))
                throw new \Exception('Acesso Negado! O usuário não tem permissão para acessar essa funcionalidade.');

            if ($usuarioLogado->perfil->super_user) {
                $empresasOuEmpresa = EmpresaController::listaTodasAsEmpresasCadastradas(['departamentos.equipes', 'departamentos.cargos', 'perfis'])->get();
            } else {
                $empresasOuEmpresa = EmpresaController::listaTodasAsEmpresasCadastradas(['departamentos.equipes', 'departamentos.cargos', 'perfis'])->findOrFail($usuarioLogado->funcionario->empresa->id);
            }

            return view('configuracao.funcionarios.index', compact('empresasOuEmpresa', 'usuarioLogado'));
        }catch (\Exception $ex){
            return redirect()->back()->with('info', $ex->getMessage());
        }
    }

    public function retornaFuncionariosCadastrados(){
        try {
            $usuarioLogado = UsuarioController::retornaUsuarioLogado();

            if(!$usuarioLogado->perfil->possuiPermissaoDeAcesso(FuncaoAcesso::where('rota','/funcionarios')->get()->implode('id')))
                throw new \Exception('Acesso Negado! O usuário não tem permissão para acessar essa funcionalidade.');

            if($usuarioLogado->perfil->super_user){
                $funcionarios = self::listaTodosOsFuncionariosCadastrados(['situacao',
                    'departamento',
                    'equipe',
                    'cargo',
                    'usuario',
                    'usuario.perfil'])->get()->toArray();
            }elseif($usuarioLogado->funcionario->empresa){
                $funcionarios = self::listaTodosOsFuncionariosDeUmaEmpresa($usuarioLogado->funcionario->empresa->id, ['situacao',
                    'departamento',
                    'equipe',
                    'cargo',
                    'usuario',
                    'usuario.perfil'])->toArray();
            }else{
                throw new \Exception('Acesso Negado! Para acessar essa funcionalidade você precisa estar associado a uma Empresa.');
            }
        }catch (\Exception $ex){
            return response()->json([
                'status_resposta' => 'error',
                'msg'    => $ex->getMessage(),
            ], 422);
        }
    }

    private function salvaFuncionario($request, $id = null){
        try {
            $funcionario = null;

            if($id == null){
                $funcionario = new Funcionario();
            }else{
                $funcionario = Funcionario::findOrFail($id);
            }

            $funcionario->nome = $request->nome;
            $funcionario->cpf = $request->cpf;
            $funcionario->rg = $request->rg;
            $funcionario->telefone_fixo = $request->telefone_fixo;
            $funcionario->telefone_celular = $request->telefone_celular;
            $funcionario->situacao_funcionarios_id = $request->situacao_funcionarios_id;
            $funcionario->departamentos_id = $request->departamentos_id == '0' || $request->departamentos_id == null ? null : $request->departamentos_id;
            $funcionario->equipes_id = $request->equipes_id == '0' || $request->equipes_id == null ? null : $request->equipes_id;
            $funcionario->cargos_id = $request->cargos_id == '0' || $request->cargos_id == null ? null : $request->cargos_id;
            $funcionario->empresa_id = $request->empresa_id;
            $funcionario->save();

            $login = Util::retiraPontosETracosCPFCNPJ($funcionario->cpf);
            $login_username = strtoupper($request->usuario['login_username']);

            if($id == null){
                if(!RegisterController::cadastraUsuario($funcionario->nome,
                    $request->usuario['email'],
                    $login,
                    $funcionario->id,
                    $request->usuario['perfil_id'],
                    $request->usuario['active'],
                    $request->usuario['pendente_trocar_senha'],
                    $login_username,
                    $request->usuario['password']))
                    throw new \Exception('Ocorreu um erro ao cadastrar o usuário.');
            }else{
                if(!RegisterController::alteraUsuario($funcionario->usuario->id,
                    $funcionario->nome,
                    $request->usuario['email'],
                    $login, $request->usuario['perfil_id'],
                    $request->usuario['active'],
                    $request->usuario['pendente_trocar_senha'],
                    $login_username,
                    $request->usuario['password']))
                    throw new \Exception('Ocorreu um erro ao alterar os dados do usuário.');
            }

        }catch (\Exception $ex){
            throw $ex;
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FuncionarioPostOrUpdateRequestValidation $request)
    {
        DB::beginTransaction();

        try{
            $usuarioLogado = UsuarioController::retornaUsuarioLogado();

            if(!$usuarioLogado->perfil->possuiPermissaoDeAcesso(FuncaoAcesso::where('rota','/funcionarios')->get()->implode('id')))
                throw new \Exception('Acesso Negado! O usuário não tem permissão para acessar essa funcionalidade.');

            $this->salvaFuncionario($request);

            DB::commit();

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Funcionário cadastrado com sucesso.',
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
    public function update(FuncionarioPostOrUpdateRequestValidation $request, $id)
    {
        DB::beginTransaction();

        try{
            $usuarioLogado = UsuarioController::retornaUsuarioLogado();

            if(!$usuarioLogado->perfil->possuiPermissaoDeAcesso(FuncaoAcesso::where('rota','/funcionarios')->get()->implode('id')))
                throw new \Exception('Acesso Negado! O usuário não tem permissão para acessar essa funcionalidade.');


            $this->salvaFuncionario($request, $id);

            DB::commit();

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Funcionário alterado com sucesso.',
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
            $usuarioLogado = UsuarioController::retornaUsuarioLogado();

            if(!$usuarioLogado->perfil->possuiPermissaoDeAcesso(FuncaoAcesso::where('rota','/funcionarios')->get()->implode('id')))
                throw new \Exception('Acesso Negado! O usuário não tem permissão para acessar essa funcionalidade.');

            $funcionarioParaExclusao = self::listaTodosOsFuncionariosCadastrados (['usuario'])->findOrFail($id);

            if($funcionarioParaExclusao){
                $funcionarioParaExclusao->delete();
            }
            else
                throw new \Exception('');

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Funcionário excluido com sucesso.',
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
            $funcionario = Funcionario::withTrashed()->with(['usuario', 'empresa'])->findOrFail($id);

            if(!$funcionario->empresa->ativo)
                throw new \Exception('Não é possível reativar esse funcionário pois a empresa encontra-se excluída.');

            if(!$funcionario->empresa->licencas_restantes > 0)
                throw new \Exception('Não é possível reativar esse funcionário pois a empresa não possui mais licenças de usuário.');

            $funcionarioDiferenteComMesmoLogin = self::listaTodosOsFuncionariosCadastrados(['usuario', 'empresa'], [], true)
                ->whereHas('usuario', function (Builder $usuario) use($funcionario){
                    $usuario->where('login_username', '=', $funcionario->usuario->login_username);
                })
                ->where('id', '<>', $funcionario->id)
                ->get();

            if($funcionarioDiferenteComMesmoLogin != null && $funcionarioDiferenteComMesmoLogin->count() > 0)
                throw new \Exception('Não é possível reativar esse funcionário pois já existe um outro usuário nessa empresa utilizando o mesmo login de usuário.');

            if($funcionario)
                $funcionario->restore();
            else
                throw new \Exception('Esse funcionário não existe na base de dados! Já deve ter sido excluído');

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Funcionário reativado com sucesso! Esse processo não reativa automaticamente os cadastros associados ao funcionário, como (carteiras e filtros). Se fazendo necessário reativar cada cadastro um por um. ',
            ], 201);
        }catch (\Exception $ex){
            return response()->json([
                'status_resposta' => 'error',
                'msg'    => $ex->getMessage(),
            ], 422);
        }
    }
}
