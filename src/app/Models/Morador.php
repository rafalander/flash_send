<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Morador extends Model
{
    public function apartamento() {
        return $this->belongsTo(Apartamento::class);
    }

    public function encomendas() {
        return $this->hasMany(Encomenda::class);
    }
}
