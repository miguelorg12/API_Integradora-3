<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EstadoDelBebe;
use App\Models\Mongo\EstadoDelBebe as MongoEstadoDelBebe;

class EstadoBebe extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estados = [
            ['estado' => 'Recuperacion'],
            ['estado' => 'Alta'],
            ['estado' => 'Fallecido'],
        ];

        foreach ($estados as $estado) {
            EstadoDelBebe::create($estado);
            MongoEstadoDelBebe::create($estado);
        }
    }
}
