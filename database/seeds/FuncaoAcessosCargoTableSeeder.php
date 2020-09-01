<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FuncaoAcessosCargoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('funcao_acessos')->insert([
            //Cargos
            [
                'titulo' => 'Cadastrar Cargos',
                'controller' => 'CargoController',
                'rota' => '/cargos',
                'nome_rota' => 'cargos.index',
                'funcao' => 'index',
                'metodo' => 'get',
                'submenus_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            /*
            [
                'titulo' => 'Listar Cargos',
                'controller' => 'CargoController',
                'rota' => '/cargos',
                'nome_rota' => 'cargos.index',
                'funcao' => 'index',
                'metodo' => 'get',
                'submenus_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Cadastrar Cargo',
                'controller' => 'CargoController',
                'rota' => '/cargos',
                'nome_rota' => 'cargos.store',
                'funcao' => 'store',
                'metodo' => 'post',
                'submenus_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Alterar Cargo',
                'controller' => 'CargoController',
                'rota' => '/cargos/{cargo}',
                'nome_rota' => 'cargos.update',
                'funcao' => 'update',
                'metodo' => 'put',
                'submenus_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Excluir Cargo',
                'controller' => 'CargoController',
                'rota' => '/cargos/{cargo}',
                'nome_rota' => 'cargos.destroy',
                'funcao' => 'destroy',
                'metodo' => 'delete',
                'submenus_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            */
        ]);
    }
}
