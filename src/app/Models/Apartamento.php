<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apartamento extends Model
{
    protected $table = 'apartamentos';

    protected $fillable = [
        'numero',
        'torre_id',
    ];

    public function torre() {
        return $this->belongsTo(Torre::class);
    }

    public function moradores() {
        return $this->hasMany(Morador::class);
    }
}
