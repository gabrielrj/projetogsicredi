<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FuncaoAcessosPerfilTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('funcao_acessos')->insert([
            //Perfis
            [
                'titulo' => 'Cadastrar Perfis',
                'controller' => 'PerfilController',
                'rota' => '/perfis',
                'nome_rota' => 'perfis.index',
                'funcao' => 'index',
                'metodo' => 'get',
                'submenus_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            /*
            [
                'titulo' => 'Listar Perfis',
                'controller' => 'PerfilController',
                'rota' => '/perfis',
                'nome_rota' => 'perfis.index',
                'funcao' => 'index',
                'metodo' => 'get',
                'submenus_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Cadastrar Perfil',
                'controller' => 'PerfilController',
                'rota' => '/perfis',
                'nome_rota' => 'perfis.store',
                'funcao' => 'store',
                'metodo' => 'post',
                'submenus_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Alterar Perfil',
                'controller' => 'PerfilController',
                'rota' => '/perfis/{perfil}',
                'nome_rota' => 'perfis.update',
                'funcao' => 'update',
                'metodo' => 'put',
                'submenus_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Excluir Perfil',
                'controller' => 'PerfilController',
                'rota' => '/perfis/{perfil}',
                'nome_rota' => 'perfis.destroy',
                'funcao' => 'destroy',
                'metodo' => 'delete',
                'submenus_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            */
        ]);
    }
}
