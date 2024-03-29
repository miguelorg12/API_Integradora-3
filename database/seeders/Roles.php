<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['nombre' => 'Desarrollador'],
            ['nombre' => 'Administrador'],
            ['nombre' => 'Doctor'],
            ['nombre' => 'Enfermera'],
            ['nombre' => 'Invitado'],
        ];

        foreach ($roles as $rol) {
            Rol::create($rol);
        }
    }
}
