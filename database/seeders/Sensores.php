<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sensor;

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
            ['nombre' => 'Sensor de Humedad', 'unidad' => '%'],
            ['nombre' => 'Sensor de Humedad'],
            ['nombre' => 'Sensor de Luz'],
            ['nombre' => 'Sensor de Movimiento'],
            ['nombre' => 'Sensor de Sonido'],
        ];
    }
}
