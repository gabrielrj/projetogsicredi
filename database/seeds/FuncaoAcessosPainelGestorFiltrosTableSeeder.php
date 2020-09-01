<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FuncaoAcessosPainelGestorFiltrosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('funcao_acessos')->insert([
            //Atendimentos
            [
                'titulo' => 'Configuração de filtros para o dashboard',
                'controller' => 'PainelGestorController',
                'rota' => '/gestor/filtros',
                'nome_rota' => 'gestor.filtros',
                'funcao' => 'index',
                'metodo' => 'get',
                'submenus_id' => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
