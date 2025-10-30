<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Encomenda extends Model
{
    protected $fillable = [
        'descricao',
        'data_recebimento',
        'retirada',
        'origem',
        'codigo_rastreamento',
        'morador_id',
    ];

    public function morador() {
        return $this->belongsTo(Morador::class);
    }

}
