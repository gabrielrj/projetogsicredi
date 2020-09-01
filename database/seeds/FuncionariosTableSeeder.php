<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FuncionariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('funcionarios')->insert([
            [
                'nome' => 'GABRIEL ANGELO DE SOUSA SILVA',
                'cpf' => '141.944.377-13',
                'situacao_funcionarios_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
