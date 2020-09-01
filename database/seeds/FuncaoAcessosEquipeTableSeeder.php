<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FuncaoAcessosEquipeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('funcao_acessos')->insert([
            //Equipes
            [
                'titulo' => 'Cadastrar Equipes',
                'controller' => 'EquipeController',
                'rota' => '/equipes',
                'nome_rota' => 'equipes.index',
                'funcao' => 'index',
                'metodo' => 'get',
                'submenus_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            /*
            [
                'titulo' => 'Listar Equipes',
                'controller' => 'EquipeController',
                'rota' => '/equipes',
                'nome_rota' => 'equipes.index',
                'funcao' => 'index',
                'metodo' => 'get',
                'submenus_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Cadastrar Equipe',
                'controller' => 'EquipeController',
                'rota' => '/equipes',
                'nome_rota' => 'equipes.store',
                'funcao' => 'store',
                'metodo' => 'post',
                'submenus_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Alterar Equipe',
                'controller' => 'EquipeController',
                'rota' => '/equipes/{equipe}',
                'nome_rota' => 'equipes.update',
                'funcao' => 'update',
                'metodo' => 'put',
                'submenus_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Excluir Equipe',
                'controller' => 'EquipeController',
                'rota' => '/equipes/{equipe}',
                'nome_rota' => 'equipes.destroy',
                'funcao' => 'destroy',
                'metodo' => 'delete',
                'submenus_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            */
        ]);
    }
}
