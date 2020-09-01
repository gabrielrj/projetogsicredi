<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FuncaoAcessosPainelAtendimentoTableSeeder extends Seeder
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
                'titulo' => 'Acessar painel do painelconsultor',
                'controller' => 'PainelAtendimentoController',
                'rota' => '/painel',
                'nome_rota' => 'painel.index',
                'funcao' => 'index',
                'metodo' => 'get',
                'menus_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
