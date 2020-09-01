<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
Use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //public function __construct()
    //{
        //$this->middleware('guest')->except('logout');
    //}

    public function showLoginForm()
    {
        if(!Auth::check())
            return view('welcome');

    }

    /**
     * Handle an authentication attempt.
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Exception
     */
    public function login(Request $request)
    {
        $erroCampo = '';

        try{
            if($request->login == '' || $request->login == null){
                $erroCampo = 'login';
                throw new \Exception('Digite o login corretamente!');
            }

            if($request->password == '' || $request->password == null){
                $erroCampo = 'password';
                throw new \Exception('Digite a senha corretamente!');
            }

            $usuario = User::with(
                'funcionario',
                'funcionario.equipe',
                'perfil',
                'perfil.funcoesDeAcesso',
                'perfil.funcoesDeAcesso.menu',
                'perfil.funcoesDeAcesso.submenu',
                'perfil.funcoesDeAcesso.submenu.menu')
                ->where('login', trim($request->login))
                ->orWhere('login_username', trim($request->login));

            if($request->empresa_id != '' && $request->empresa_id != null){
                $empresa = Empresa::withTrashed()->find(trim($request->empresa_id));

                if(!$empresa)
                    throw new \Exception('Essa empresa não existe na base de dados do GSI Credi.');
                elseif(!$empresa->ativo)
                    throw new \Exception('Essa empresa encontra-se inativa.');

                $usuario = $usuario->whereHas('funcionario', function (\Illuminate\Database\Eloquent\Builder $qFuncionario) use ($empresa) {
                    $qFuncionario->where('empresa_id', $empresa->id);
                })->first();
            }else{
                $usuario = $usuario->whereHas('perfil', function (\Illuminate\Database\Eloquent\Builder $qPerfil) {
                    $qPerfil->where('super_user', '=', 1);
                })->first();

                if(!$usuario){
                    $erroCampo = 'empresa';
                    throw new \Exception('Digite o código da empresa corretamente!');
                }
            }

            if(!$usuario){
                $erroCampo = 'login';
                throw new \Exception('Login não encontrado!');
            }elseif(!$usuario->perfil->ativo)
                throw new \Exception('Usuário com perfil de acesso excluído!');

            if(Hash::check($request->password, $usuario->password)){
                $resposta = $this->usuarioEstaAtivo($usuario);
                if($resposta === true)
                    Auth::loginUsingId($usuario->id);
                else
                    throw new \Exception($resposta);
            }
            else{
                $erroCampo = 'password';
                throw new \Exception('Senha incorreta!');
            }

            return response()->json([
                'status_resposta' => 'success',
                'msg'    => 'Login efetuado com sucesso.',
                'userConfig' => $usuario
            ], 201);
        }catch (\Exception $e){
            if($erroCampo != 'login' && $erroCampo != 'password' && $erroCampo != 'empresa')
                $erroCampo = 'other';

            return response()->json([
                'status_resposta' => 'error',
                'campo' => $erroCampo,
                'msg'    => $e->getMessage(),
            ], 422);
        }

    }

    public function logout(Request $request) {
        //Session::forget('menus_submenus');
        //Session::flush();
        Auth::logout();
        return view('welcome');
    }

    public function usuarioEstaAtivo(User $usuario){
        if(!$usuario->active)
            return 'O usuário está inativo/bloqueado permanentemente!';
        elseif($usuario->data_bloqueio != null && Carbon::createFromFormat('Y-m-d H:i:s', $usuario->data_bloqueio)->diffInMinutes(Carbon::now(), false) < 5) //5 minutos de bloqueio devido a tentativa de duplo acesso.
            return 'O usuário está bloqueado por 5 minutos pois foi identificado tentativa de acesso simultâneo!';
        else
            return true;
    }
}
