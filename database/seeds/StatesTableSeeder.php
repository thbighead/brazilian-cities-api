<?php

use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();
        DB::table('states')->insert([
            [
                'id' => '22',
                'acronym' => 'RN',
                'name' => 'Rio Grande do Norte',
                'created_at' => $now,
            ],
            [
                'id' => '20',
                'acronym' => 'PI',
                'name' => 'Piauí',
                'created_at' => $now,
            ],
            [
                'id' => '30',
                'acronym' => 'PE',
                'name' => 'Pernambuco',
                'created_at' => $now,
            ],
            [
                'id' => '17',
                'acronym' => 'PB',
                'name' => 'Paraíba',
                'created_at' => $now,
            ],
            [
                'id' => '16',
                'acronym' => 'PA',
                'name' => 'Pará',
                'created_at' => $now,
            ],
            [
                'id' => '13',
                'acronym' => 'MA',
                'name' => 'Maranhão',
                'created_at' => $now,
            ],
            [
                'id' => '6',
                'acronym' => 'CE',
                'name' => 'Ceará',
                'created_at' => $now,
            ],
            [
                'id' => '3',
                'acronym' => 'AP',
                'name' => 'Amapá',
                'created_at' => $now,
            ],
            [
                'id' => '2',
                'acronym' => 'AL',
                'name' => 'Alagoas',
                'created_at' => $now,
            ],
            [
                'id' => '28',
                'acronym' => 'SE',
                'name' => 'Sergipe',
                'created_at' => $now,
            ],
            [
                'id' => '27',
                'acronym' => 'SP',
                'name' => 'São Paulo',
                'created_at' => $now,
            ],
            [
                'id' => '26',
                'acronym' => 'SC',
                'name' => 'Santa Catarina',
                'created_at' => $now,
            ],
            [
                'id' => '23',
                'acronym' => 'RS',
                'name' => 'Rio Grande do Sul',
                'created_at' => $now,
            ],
            [
                'id' => '21',
                'acronym' => 'RJ',
                'name' => 'Rio de Janeiro',
                'created_at' => $now,
            ],
            [
                'id' => '18',
                'acronym' => 'PR',
                'name' => 'Paraná',
                'created_at' => $now,
            ],
            [
                'id' => '15',
                'acronym' => 'MG',
                'name' => 'Minas Gerais',
                'created_at' => $now,
            ],
            [
                'id' => '11',
                'acronym' => 'MS',
                'name' => 'Mato Grosso do Sul',
                'created_at' => $now,
            ],
            [
                'id' => '14',
                'acronym' => 'MT',
                'name' => 'Mato Grosso',
                'created_at' => $now,
            ],
            [
                'id' => '29',
                'acronym' => 'GO',
                'name' => 'Goiás',
                'created_at' => $now,
            ],
            [
                'id' => '7',
                'acronym' => 'DF',
                'name' => 'Distrito Federal',
                'created_at' => $now,
            ],
            [
                'id' => '8',
                'acronym' => 'ES',
                'name' => 'Espírito Santo',
                'created_at' => $now,
            ],
            [
                'id' => '5',
                'acronym' => 'BA',
                'name' => 'Bahia',
                'created_at' => $now,
            ],
            [
                'id' => '31',
                'acronym' => 'TO',
                'name' => 'Tocantins',
                'created_at' => $now,
            ],
            [
                'id' => '25',
                'acronym' => 'RR',
                'name' => 'Roraima',
                'created_at' => $now,
            ],
            [
                'id' => '4',
                'acronym' => 'AM',
                'name' => 'Amazonas',
                'created_at' => $now,
            ],
            [
                'id' => '1',
                'acronym' => 'AC',
                'name' => 'Acre',
                'created_at' => $now,
            ],
            [
                'id' => '24',
                'acronym' => 'RO',
                'name' => 'Rondônia',
                'created_at' => $now,
            ],
        ]);
    }
}
