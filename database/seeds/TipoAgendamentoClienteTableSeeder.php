<?php

use Illuminate\Database\Seeder;

class TipoAgendamentoClienteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_agendamento_clientes')->insert([
            [
                'descricao' => 'Visita',
            ],
            [
                'descricao' => 'Chamada',
            ],
        ]);
    }
}
