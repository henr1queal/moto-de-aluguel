<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    protected $bikes = [
        'brands' => [
            [
                'name' => 'Honda',
                'models' => [
                    'CG 160 Titan',
                    'CG 160 Fan',
                    'XRE 190',
                    'XRE 300',
                    'Pop 100',
                ]
            ],
            [
                'name' => 'Yamaha',
                'models' => [
                    'Factor 150',
                    'FZ 15',
                    'FZ 25',
                ]
            ],
            [
                'name' => 'Bajaj',
                'models' => [
                    'Dominar 200',
                    'Dominar 400'
                ]
            ],
            [
                'name' => 'Shineray',
                'models' => [
                    'Jet 50',
                    'Urban 150'
                ]
            ]
        ]
    ];

    protected $actual_brand_index = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->actual_brand_index = rand(0, count($this->bikes['brands']) - 1);
        $brand = $this->bikes['brands'][$this->actual_brand_index];

        // Seleciona um modelo aleatório baseado na marca escolhida
        $model = $brand['models'][array_rand($brand['models'])];

        return [
            'brand' => $brand['name'], // Marca selecionada
            'model' => $model, // Modelo da marca selecionada
            'year' => $this->faker->numberBetween(2017, 2025), // Ano entre 2000 e 2023
            'license_plate' => strtoupper($this->faker->unique()->bothify('???-####')), // Formato de placa
            'renavam' => $this->faker->unique()->numerify('###########'), // Renavam com 11 dígitos
            'actual_km' => $this->faker->numberBetween(0, 100000), // KM atual, entre 0 e 300.000
            'revision_period' => $this->faker->numberBetween(1000, 5000), // Período de revisão (exemplo: 10.000 km)
            'oil_period' => $this->faker->numberBetween(500, 3000), // Período de troca de óleo (exemplo: 5.000 km)
            'protection_value' => $this->faker->randomFloat(2, 500, 5000), // Valor da proteção (exemplo: R$500 a R$5000)
            'user_id' => '1919dfb2-3815-48cd-9f10-bd5cb150dd00', // Relacionamento com usuário
        ];
    }
}
