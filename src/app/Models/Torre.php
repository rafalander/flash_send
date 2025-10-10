<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Torre extends Model
{   
    use HasFactory;

    protected $table = 'torres';

    protected $fillable = [
        'nome',
        'bloco_id'
    ];

    public function torres()
    {
        return $this->hasMany(Torre::class);
    }

    public function bloco() {
        return $this->belongsTo(Bloco::class);
    }

    public function apartamentos() {
        return $this->hasMany(Apartamento::class);
    }
}
