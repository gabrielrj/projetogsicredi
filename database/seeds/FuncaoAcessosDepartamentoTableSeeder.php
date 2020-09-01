<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FuncaoAcessosDepartamentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('funcao_acessos')->insert([
            //Departamentos
            [
                'titulo' => 'Cadastrar Departamentos',
                'controller' => 'DepartamentoController',
                'rota' => '/departamentos',
                'nome_rota' => 'departamentos.index',
                'funcao' => 'index',
                'metodo' => 'get',
                'submenus_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            /*
            [
                'titulo' => 'Listar Departamentos',
                'controller' => 'DepartamentoController',
                'rota' => '/departamentos',
                'nome_rota' => 'departamentos.index',
                'funcao' => 'index',
                'metodo' => 'get',
                'submenus_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Cadastrar Departamento',
                'controller' => 'DepartamentoController',
                'rota' => '/departamentos',
                'nome_rota' => 'departamentos.store',
                'funcao' => 'store',
                'metodo' => 'post',
                'submenus_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Alterar Departamento',
                'controller' => 'DepartamentoController',
                'rota' => '/departamentos/{departamento}',
                'nome_rota' => 'departamentos.update',
                'funcao' => 'update',
                'metodo' => 'put',
                'submenus_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Excluir Departamento',
                'controller' => 'DepartamentoController',
                'rota' => '/departamentos/{departamento}',
                'nome_rota' => 'departamentos.destroy',
                'funcao' => 'destroy',
                'metodo' => 'delete',
                'submenus_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            */
        ]);
    }
}
