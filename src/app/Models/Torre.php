<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Torre extends Model
{
    public function bloco() {
        return $this->belongsTo(Bloco::class);
    }

    public function apartamentos() {
        return $this->hasMany(Apartamento::class);
    }
}
