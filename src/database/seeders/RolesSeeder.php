<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cria os papéis básicos
        $roles = ['morador', 'porteiro', 'sindico', 'admin'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Cria um usuário admin inicial
        $admin = User::firstOrCreate(
            ['email' => 'admin@exemplo.com'],
            ['name' => 'Admin', 'password' => bcrypt('senha123')]
        );

        $admin->assignRole('admin');
    }
}