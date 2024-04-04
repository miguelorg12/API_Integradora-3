<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EstadoIncubadora;

class EstadoIncubadoras extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estados = [
            ['estado' => 'Optimo'],
            ['estado' => 'En mantenimiento'],
            ['estado' => 'Necesita reparacion'],
        ];

        foreach ($estados as $estado) {
            EstadoIncubadora::create($estado);
        }
    }
}
