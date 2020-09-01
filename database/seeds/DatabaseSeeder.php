<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(UfTableSeeder::class);
        $this->call(StatusFichaClienteTableSeeder::class);
        $this->call(TipoAgendamentoClienteTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(SubmenusTableSeeder::class);
        $this->call(FuncaoAcessosDepartamentoTableSeeder::class);
        $this->call(FuncaoAcessosCargoTableSeeder::class);
        $this->call(FuncaoAcessosEquipeTableSeeder::class);
        $this->call(FuncaoAcessosPerfilTableSeeder::class);
        $this->call(FuncaoAcessosFuncionarioTableSeeder::class);
        $this->call(FuncaoAcessosPainelAtendimentoTableSeeder::class);
        $this->call(FuncaoAcessosPainelGestorFiltrosTableSeeder::class);
        $this->call(SituacaoFuncionariosTableSeeder::class);
        $this->call(PerfilsTableSeeder::class);
        $this->call(FuncionariosTableSeeder::class);
        //Usuários do sistema
        $this->call(inserir_usuarios_padrao::class);
    }
}
