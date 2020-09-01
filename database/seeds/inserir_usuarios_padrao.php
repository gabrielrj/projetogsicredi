<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class inserir_usuarios_padrao extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'GABRIEL ANGELO DE SOUSA SILVA',
                'email' => 'gabriel_angelo100@hotmail.com',
                'login' => '14194437713',
                'password' => Hash::make('03091994'),
                'pendente_trocar_senha' => false,
                'active' => true,
                'funcionarios_id' => 1,
                'perfil_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
