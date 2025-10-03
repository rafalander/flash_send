<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bloco extends Model
{
    use HasFactory;
    protected $table = 'blocos'; 

    protected $fillable = [
        'nome'
    ];

    public function torres()
    {
        return $this->hasMany(Torre::class);
    }
}