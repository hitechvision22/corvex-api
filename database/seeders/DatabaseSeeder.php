<?php

namespace Database\Seeders;

use App\Models\Frais;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        User::create([
            'name' => 'landry',
            'email' => 'foguengcyrille@gmail.com',
            'password' => Hash::make('Arthemix@1234'),
            'type' => 3,
            'verified' => true,
        ]);

        Frais::create([
            'montant' => 250,
            'raison' => 'revenus',
        ]);
    }
}
