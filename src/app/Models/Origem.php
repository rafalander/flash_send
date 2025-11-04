<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Origem extends Model
{
    protected $table = 'origem';

    protected $fillable = [
        'nome_origem',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];
}
