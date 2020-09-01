<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SituacaoFuncionariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('situacao_funcionarios')->insert([
            [
                'descricao' => 'ATIVO',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'descricao' => 'INATIVO',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
