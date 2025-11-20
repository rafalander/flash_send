<?php

namespace Database\Seeders;

use App\Models\Origem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrigemSeeder extends Seeder
{
    public function run(): void
    {
        $origens = [
            'Amazon',
            'Mercado Livre',
            'Americanas',
            'Magalu',
            'Casas Bahia',
            'Ponto Frio',
            'Walmart',
            'Shopee',
            'Correios',
            'Tiktok Shop',
            'Shein',
            'Aliexpress',
            'Temu',
            'Kabum',
            'Outro'
        ];
        
        if (Origem::count() == 0) {
            foreach ($origens as $origem) {
                Origem::create([
                    'nome_origem' => $origem,
                    'ativo' => true,
                ]);
            }
        } else {
            echo "Origens já existem\n";
        }
    }
}
