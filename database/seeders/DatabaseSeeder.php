<?php

namespace Database\Seeders;

use App\Models\Frais;
use App\Models\Trajet;
use App\Models\User;
use App\Models\Wallet;
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

        // admin => type =3
        // super-admin => type =3
        Frais::create([
            'montant' => 150,
            'raison' => 'revenus',
        ]);


        User::create([
            'name' => 'landry',
            'email' => 'foguengcyrille@gmail.com',
            'password' => Hash::make('Arthemix@1234'),
            'type' => 3,
            'verified' => true,
        ]);


        User::create([
            'name' => 'hitech',
            'email' => 'hitech@gmail.com',
            'password' => Hash::make('hitech@1234'),
            'type' => 4,
            'verified' => true,
        ]);

        User::create([
            'name' => 'caissiere',
            'email' => 'caissiere@gmail.com',
            'password' => Hash::make('caissiere@1234'),
            'type' => 2,
            'verified' => true,
        ]);

        Wallet::create(['user_id' => 1, 'montant' => 0]);
        Wallet::create(['user_id' => 2, 'montant' => 0]);
        Wallet::create(['user_id' => 3, 'montant' => 0]);

        Trajet::create([
            'ville_depart' => 'douala',
            'point_rencontre' => 'tradex borne 10, village',
            'ville_destination' => 'yaounde',
            'point_destination' => "mvan",
            'date_depart' => '2024-07-05',
            'heure_depart' => '09:00',
            'prix' => 2000,
            'Nombre_de_place' => 4,
            'nombre_de_place_disponible' => 3,
            'user_id' => 1,
            'etat' => 'actif',
        ]);
        Trajet::create([
            'ville_depart' => 'yaounde',
            'point_rencontre' => 'mvan',
            'ville_destination' => 'douala',
            'point_destination' => "tradex borne 10, village",
            'date_depart' => '2024-07-06',
            'heure_depart' => '08:00',
            'prix' => 2500,
            'Nombre_de_place' => 3,
            'nombre_de_place_disponible' => 3,
            'user_id' => 1,
            'etat' => 'actif',
        ]);

        Trajet::create([
            'ville_depart' => 'yaounde',
            'point_rencontre' => 'mvan',
            'ville_destination' => 'douala',
            'point_destination' => "tradex borne 10, village",
            'date_depart' => '2024-07-06',
            'heure_depart' => '08:00',
            'prix' => 2500,
            'Nombre_de_place' => 3,
            'nombre_de_place_disponible' => 3,
            'user_id' => 1,
            'etat' => 'actif',
        ]);
    }
}
