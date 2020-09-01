<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(\Illuminate\Support\Facades\Auth::check())
        return redirect()->route('home');
    else
        return view('welcome');
});

Auth::routes(['register' => false]);


//Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth', 'auth.unique.active.user']], function(){
    //Route::get('login', '\App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

    Route::get('/home', 'HomeController@index')->name('home');

    //Config - Tabelas
    //Departamentos
    Route::resource('departamentos', 'DepartamentoController');
    Route::post('departamentos/lista/por/empresa/json', 'DepartamentoController@listaDepartamentosPorEmpresaJson');
    Route::get('departamentos/reativa/{id}', 'DepartamentoController@restore');

    //Equipes
    Route::resource('equipes', 'EquipeController');
    Route::post('equipes/lista/por/empresa/json', 'EquipeController@listaEquipesPorEmpresaJson');
    Route::get('equipes/reativa/{id}', 'EquipeController@restore');

    //Cargos
    Route::resource('cargos', 'CargoController');
    Route::post('cargos/lista/por/empresa/json', 'CargoController@listaCargosPorEmpresaJson');
    Route::get('cargos/reativa/{id}', 'CargoController@restore');

    //Funcionários
    Route::resource('funcionarios', 'FuncionarioController');
    Route::post('funcionarios/lista/por/empresa/json', 'FuncionarioController@listaFuncionariosPorEmpresaJson');
    Route::get('funcionarios/reativa/{id}', 'FuncionarioController@restore');

    //Perfis
    Route::resource('perfis', 'PerfilController');
    Route::post('perfis/lista/por/empresa/json', 'PerfilController@listaPerfisPorEmpresaJson');
    Route::get('perfis/reativa/{id}', 'PerfilController@restore');

    //Empresas
    Route::resource('empresas', 'EmpresaController');
    Route::put('empresas/altera/status/{id}', 'EmpresaController@alteraStatusEmpresa');
    Route::get('empresas/lista/completa', 'EmpresaController@retornaEmpresasCadastradas');
    Route::get('empresas/reativa/{id}', 'EmpresaController@restore');

    //Painel do Consultor
    Route::resource('painel', 'PainelAtendimentoController');

    Route::resource('carteira', 'CarteiraFichaClienteController');
    Route::get('/carteira/retorna/carteira/ativa', 'CarteiraFichaClienteController@retornaCarteiraAtivaDoConsultor');

    Route::resource('fichas', 'FichaClienteController');
    Route::get('/painel/fichas/{id}', 'FichaClienteController@show');
    Route::post('/fichas/ignora', 'FichaClienteController@ignoraCliente');
    Route::get('/painel/fichas/registra/ultimo/acesso/{id}', 'FichaClienteController@registraUltimoAcesso');

    Route::resource('agendamentos', 'AgendamentoFichaClienteController');
    Route::get('agendamentos/deleta/{id}/{motivo}', 'AgendamentoFichaClienteController@deletaAgendamento');

    Route::resource('clientes', 'ClienteController');
    Route::get('clientes/busca/cpf/{cpf}', 'ClienteController@buscaClienteCPF');

    //Painel do Gestor
    Route::resource('painelgestor', 'PainelGestorController');
    //Filtros
    Route::resource('configuracaofiltros', 'ConfiguracaoFiltroController');
    Route::post('configuracaofiltros/lista/por/empresa/json', 'ConfiguracaoFiltroController@listaFiltrosPorEmpresaJson');


});
