<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Encomenda extends Model
{
    public function morador() {
        return $this->belongsTo(Morador::class);
    }
}
