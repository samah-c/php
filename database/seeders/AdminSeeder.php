<?php

namespace Database\Seeders;

use App\Models\Admin as AdminModel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'admin@example.com';
        $password = 'admin123';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Administrator',
                'password' => Hash::make($password),
            ]
        );

        AdminModel::firstOrCreate(
            ['email' => $email],
            [
                'nom' => 'Admin',
                'prenom' => 'System',
                'login' => 'admin',
                'password' => Hash::make($password),
                'telephone' => null,
            ]
        );
    }
}
