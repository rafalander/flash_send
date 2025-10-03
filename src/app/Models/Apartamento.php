<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apartamento extends Model
{
    public function torre() {
        return $this->belongsTo(Torre::class);
    }

    public function moradores() {
        return $this->hasMany(Morador::class);
    }
}
