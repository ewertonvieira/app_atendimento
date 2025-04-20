<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avaliacoes extends Model
{
    protected $fillable = [
        'atendimento_id',
        'cliente_id',
        'nota',
        'comentario',
    ];

    /**
     * Relacionamento: Uma avaliação pertence a um atendimento.
     */
    public function atendimento(): BelongsTo
    {
        return $this->belongsTo(Atendimentos::class, 'atendimento_id');
    }

    /**
     * Relacionamento: Uma avaliação pertence a um cliente.
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }
}