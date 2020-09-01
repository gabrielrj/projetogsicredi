<?php

use App\Models\CargoCliente;
use App\Models\DadosBancariosCliente;
use App\Models\EmailCliente;
use App\Models\EnderecoCliente;
use App\Models\TelefoneCliente;
use Illuminate\Database\Seeder;
use App\Models\Cliente;
use Faker\Factory as Faker;

class ClienteFakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fake = Faker::create('pt_BR');

        foreach (range(1, 15000) as $i) {
            $cliente = Cliente::create([
                'nome' => $fake->name,
                'cpf' => $fake->regexify('[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}'),
                'mae' => $fake->name,
                'data_nascimento' => $fake->dateTimeBetween('-70 years', '-20 years'),
            ]);

            $qEmail = rand(0, 2);
            $qTel = rand(1, 4);
            $qEnd = rand(1, 3);

            $arrayCargos = ['SERVIDOR PÚBLICO', 'MILITAR'];
            $arraySituacao = ['ATIVO', 'INATIVO', 'PENSIONISTA'];
            $arrayOrgao = ['EXERCITO', 'AERONAUTICA', 'MARINHA', 'FEDERAL', 'PREFEITURA', 'ESTADO'];

            CargoCliente::create([
                'clientes_id' => $cliente->id,
                'nome' => $arrayCargos[array_rand($arrayCargos)],
                'classe' => $arrayOrgao[array_rand($arrayOrgao)],
                'esfera' => $arrayOrgao[array_rand($arrayOrgao)],
                'situacao' => $arraySituacao[array_rand($arraySituacao)],
            ]);

            $valorBruto = $fake->randomFloat(2, 0, 40000.00);
            $valorDesconto = $fake->randomFloat(2,0, $valorBruto);
            $valorLiquido = $valorBruto - $valorDesconto;
            $valorMargem = ($valorDesconto * 0.3);

            DadosBancariosCliente::create([
                'clientes_id' => $cliente->id,
                'banco' => $fake->company,
                'conta' => $fake->regexify('[0-9]{4} [0-9]{9}'),
                'tipo' => 'CORRENTE',
                'valor_bruto' => $valorBruto,
                'valor_desconto' => $valorDesconto,
                'valor_liquido' => $valorLiquido,
                'valor_margem' => $valorMargem,
            ]);

            if($qEmail != 0) {
                foreach (range(1, $qEmail) as $e){
                    EmailCliente::create([
                        'clientes_id' => $cliente->id,
                        'email' => $fake->email,
                    ]);
                }
            }

            foreach (range(1, $qTel) as $t){
                TelefoneCliente::create([
                    'clientes_id' => $cliente->id,
                    'numero' => $fake->phoneNumber,
                ]);
            }

            $arrayTipoLog = ['AV', 'R'];
            foreach (range(1, $qEnd) as $en){
                EnderecoCliente::create([
                    'clientes_id' => $cliente->id,
                    'cep' => $fake->postcode,
                    'tipo_logradouro' => $arrayTipoLog[array_rand($arrayTipoLog)],
                    'logradouro' => $fake->streetAddress,
                    'numero' => $fake->buildingNumber,
                    'complemento' => null,
                    'cidade' => $fake->citySuffix,
                    'ufs_id' => rand(1, 27),
                ]);
            }
        }

    }
}
