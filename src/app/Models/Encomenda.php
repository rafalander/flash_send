<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Encomenda extends Model
{
    public function morador() {
        return $this->belongsTo(Morador::class);
    }
}
