<?php

namespace Database\Seeders;

use App\Models\Utilisateur as utilisateurModel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UtilisateurSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'user@example.com';
        $password = 'user123';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Utilisateurstrator',
                'password' => Hash::make($password),
            ]
        );

        utilisateurModel::firstOrCreate(
            ['email' => $email],
            [
                'nom' => 'utilisateur',
                'prenom' => 'System',
                'login' => 'utilisateur',
                'password' => Hash::make($password),
                'telephone' => null,
            ]
        );
    }
}
