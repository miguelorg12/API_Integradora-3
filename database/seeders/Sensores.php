<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sensores as Sensor;
use App\Models\Mongo\Sensores as MongoSensores;

class Sensores extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sensores = [
            ['nombre' => 'Sensor de Temperatura', 'unidad' => '°C'],
            ['nombre' => 'Sensor de Pulso', 'unidad' => 'BPM'],
            ['nombre' => 'Sensor de Calidad de Aire', 'unidad' => 'ppm'],
            ['nombre' => 'Sensor de Sonido', 'unidad' => 'dB'],
            ['nombre' => 'Microfono', 'unidad' => 'dB'],
            ['nombre' => 'Buzzer', 'Unidad' => 'db']
        ];

        foreach ($sensores as $sensor) {
            Sensor::create($sensor);
            MongoSensores::create($sensor);
        }
    }
}
