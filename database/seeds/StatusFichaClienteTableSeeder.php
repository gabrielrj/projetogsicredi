<?php

use Illuminate\Database\Seeder;

class StatusFichaClienteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status_ficha_clientes')->insert([
            [
                'descricao' => 'Disponível',
            ],
            [
                'descricao' => 'Em atendimento',
            ],
            [
                'descricao' => 'Atendimento cancelado',
            ],
            [
                'descricao' => 'Visita agendada',
            ],
            [
                'descricao' => 'Chamada agendada',
            ],
            [
                'descricao' => 'Agendamento de visita cancelada',
            ],
            [
                'descricao' => 'Agendamento de chamada cancelada',
            ],
            [
                'descricao' => 'Não conseguiu contato',
            ],
            [
                'descricao' => 'Cliente ignorado',
            ],
        ]);
    }
}
