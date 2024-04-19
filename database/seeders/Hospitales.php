<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hospital;
use App\Models\Mongo\Hospital as MongoHospital;

class Hospitales extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hospitales = [
            [
                'nombre' => 'Hospital General',
                'direccion' => 'Calle 1 #123',
                'telefono' => '1234567890',
            ]
        ];

        foreach ($hospitales as $hospital) {
            Hospital::create($hospital);
            MongoHospital::create($hospital);
        }
    }
}
