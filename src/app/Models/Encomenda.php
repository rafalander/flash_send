<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Encomenda extends Model
{
    protected $fillable = [
        'descricao',
        'data_recebimento',
        'origem',
        'codigo_rastreamento',
        'morador_id',
        'retirada',
    ];

    protected $casts = [
        'data_recebimento' => 'date',
        'retirada' => 'boolean',
    ];

    public function morador() {
        return $this->belongsTo(Morador::class);
    }
}
