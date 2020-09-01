<?php

use Illuminate\Database\Seeder;

class UfTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ufs')->insert([
            [
                'sigla' => 'AC',
            ],
            [
                'sigla' => 'AL',
            ],
            [
                'sigla' => 'AM',
            ],
            [
                'sigla' => 'AP',
            ],
            [
                'sigla' => 'BA',
            ],
            [
                'sigla' => 'CE',
            ],
            [
                'sigla' => 'DF',
            ],
            [
                'sigla' => 'ES',
            ],
            [
                'sigla' => 'GO',
            ],
            [
                'sigla' => 'MA',
            ],
            [
                'sigla' => 'MG',
            ],
            [
                'sigla' => 'MS',
            ],
            [
                'sigla' => 'MT',
            ],
            [
                'sigla' => 'PA',
            ],
            [
                'sigla' => 'PB',
            ],
            [
                'sigla' => 'PE',
            ],
            [
                'sigla' => 'PI',
            ],
            [
                'sigla' => 'PR',
            ],
            [
                'sigla' => 'RJ',
            ],
            [
                'sigla' => 'RN',
            ],
            [
                'sigla' => 'RO',
            ],
            [
                'sigla' => 'RR',
            ],
            [
                'sigla' => 'RS',
            ],
            [
                'sigla' => 'SC',
            ],
            [
                'sigla' => 'SE',
            ],
            [
                'sigla' => 'SP',
            ],
            [
                'sigla' => 'TO',
            ],
        ]);
    }
}
