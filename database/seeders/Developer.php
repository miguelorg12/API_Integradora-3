<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Developer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $developer = [
            'name' => 'Smart',
            'last_name' => 'Nest',
            'email' => 'smartnestorg@gmail.com',
            'password' => Hash::make('Admin1234'),
            'id_rol' => 1,
            'id_hospital' => 1,
            'is_active' => true,
            'activated_at' => now(),
        ];

        User::create($developer);
    }
}
