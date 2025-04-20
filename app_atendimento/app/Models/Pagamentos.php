<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pagamentos extends Model
{
    protected $fillable = [
        'atendimento_id',
        'valor',
        'status',
        'data_pagamento',
        'metodo_pagamento',
    ];

    /**
     * Relacionamento: Um pagamento pertence a um atendimento.
     */
    public function atendimento(): BelongsTo
    {
        return $this->belongsTo(Atendimentos::class, 'atendimento_id');
    }
}