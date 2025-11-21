<?php

namespace Database\Seeders;

use App\Models\Tipo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tipo::create([
            'nome' => 'morador',
            'descricao' => 'Morador do condomínio',
        ]);
        Tipo::create([
            'nome' => 'admin',
            'descricao' => 'Admin do sistema',
        ]);
        Tipo::create([
            'nome' => 'portaria',
            'descricao' => 'Portaria do condomínio',
        ]);
        Tipo::create([
            'nome' => 'sindico',
            'descricao' => 'Sindicancia do condomínio',
        ]);
    }
}
