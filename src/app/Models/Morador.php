<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Morador extends Model
{
    protected $table = 'moradores';

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cpf',
        'apartamento_id',
    ];

    public function apartamento() {
        return $this->belongsTo(Apartamento::class);
    }

    public function encomendas() {
        return $this->hasMany(Encomenda::class);
    }
}
