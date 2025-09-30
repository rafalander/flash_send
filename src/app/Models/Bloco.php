<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bloco extends Model
{
    public function torres() {
        return $this->hasMany(Torre::class);
    }
}
