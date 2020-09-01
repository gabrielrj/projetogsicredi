<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SubmenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('submenus')->insert([
            [
                'titulo' => 'Departamentos',
                'ordem' => 1,
                'view' => null,
                'controller' => 'DepartamentoController',
                'rota' => '/departamentos',
                'nome_rota' => 'departamentos.index',
                'funcao' => null,
                'menus_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Cargos',
                'ordem' => 2,
                'view' => null,
                'controller' => 'CargoController',
                'rota' => '/cargos',
                'nome_rota' => 'cargos.index',
                'funcao' => null,
                'menus_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Equipes',
                'ordem' => 3,
                'view' => null,
                'controller' => 'EquipeController',
                'rota' => '/equipes',
                'nome_rota' => 'equipes.index',
                'funcao' => null,
                'menus_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Perfis',
                'ordem' => 4,
                'view' => null,
                'controller' => 'PerfilController',
                'rota' => '/perfis',
                'nome_rota' => 'perfis.index',
                'funcao' => null,
                'menus_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Funcionários',
                'ordem' => 5,
                'view' => null,
                'controller' => 'FuncionarioController',
                'rota' => '/funcionarios',
                'nome_rota' => 'funcionarios.index',
                'funcao' => null,
                'menus_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'titulo' => 'Filtros para o Atendimento',
                'ordem' => 1,
                'view' => null,
                'controller' => 'PainelGestorController',
                'rota' => '/gestor/filtros',
                'nome_rota' => 'gestor.filtros',
                'funcao' => null,
                'menus_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
