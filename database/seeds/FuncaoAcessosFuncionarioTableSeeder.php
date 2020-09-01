<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FuncaoAcessosFuncionarioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('funcao_acessos')->insert([
            //Funcionarios
            [
                'titulo' => 'Cadastrar Funcionarios',
                'controller' => 'FuncionarioController',
                'rota' => '/funcionarios',
                'nome_rota' => 'funcionarios.index',
                'funcao' => 'index',
                'metodo' => 'get',
                'submenus_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            /*
            [
                'titulo' => 'Listar Funcionarios',
                'controller' => 'FuncionarioController',
                'rota' => '/funcionarios',
                'nome_rota' => 'funcionarios.index',
                'funcao' => 'index',
                'metodo' => 'get',
                'submenus_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Cadastrar Funcionario',
                'controller' => 'FuncionarioController',
                'rota' => '/funcionarios',
                'nome_rota' => 'funcionarios.store',
                'funcao' => 'store',
                'metodo' => 'post',
                'submenus_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Alterar Funcionario',
                'controller' => 'FuncionarioController',
                'rota' => '/funcionarios/{funcionario}',
                'nome_rota' => 'funcionarios.update',
                'funcao' => 'update',
                'metodo' => 'put',
                'submenus_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Excluir Funcionario',
                'controller' => 'FuncionarioController',
                'rota' => '/funcionarios/{funcionario}',
                'nome_rota' => 'funcionarios.destroy',
                'funcao' => 'destroy',
                'metodo' => 'delete',
                'submenus_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            */
        ]);
    }
}
