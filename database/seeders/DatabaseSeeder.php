<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bebes;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $bebe = [
            'nombre'=>'Coquillo',
            'apellido'=>'Barrientos',
            'sexo'=>'F',
            'fecha_nacimiento'=>'2024-03-02',
            'edad'=>1,
            'peso'=>4,
            'id_estado'=>1,
            'id_incubadora'=>1
        ];
        Bebes::create($bebe);

    }
}
