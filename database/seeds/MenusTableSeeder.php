<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            [
                'titulo' => 'Painel do Consultor',
                'ordem' => 1,
                'view' => null,
                'controller' => null,
                'rota' => null,
                'nome_rota' => null,
                'funcao' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Configuração',
                'ordem' => 2,
                'view' => null,
                'controller' => null,
                'rota' => null,
                'nome_rota' => null,
                'funcao' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Painel do Gestor',
                'ordem' => 3,
                'view' => null,
                'controller' => null,
                'rota' => null,
                'nome_rota' => null,
                'funcao' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
