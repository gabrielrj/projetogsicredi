<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'login' => ['required', 'string', 'min:11', 'max:11'],
            'login_username' => ['required', 'string', 'min:2', 'max:250'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'login' => $data['login'],
            'login_username' => $data['login_username'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public static function cadastraUsuario($nome, $email, $login, $funcionarios_id, $perfil_id, $ativo, $pendente_trocar_senha, $login_username, $senha = null){
        return User::create([
            'name' => $nome,
            'email' => $email,
            'login' => $login,
            'login_username' => $login_username,
            'password' => $senha != null ? Hash::make($senha) : Hash::make($login),
            'funcionarios_id' => $funcionarios_id,
            'perfil_id' => $perfil_id,
            'active' => $ativo,
            'pendente_trocar_senha' => $pendente_trocar_senha
        ]);
    }

    public static function alteraUsuario($id, $nome, $email, $login, $perfil_id, $ativo, $pendente_trocar_senha, $login_username, $senha = null)
    {
        $usuario = User::with('perfil')->findOrFail($id);

        if ($senha != null && $senha != '') {
            $usuario->update(['password' => Hash::make($senha)]);
        }

        return $usuario->update([
            'name' => $nome,
            'email' => $email,
            'login' => $login,
            'login_username' => $login_username,
            'perfil_id' => $perfil_id,
            'active' => $ativo,
            'pendente_trocar_senha' => $pendente_trocar_senha]);

    }
}
