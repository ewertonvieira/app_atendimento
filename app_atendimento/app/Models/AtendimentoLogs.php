<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AtendimentoLogs extends Model
{
    protected $fillable = [
        'atendimento_id',
        'user_id',
        'acao',
        'descricao',
    ];

    /**
     * Relacionamento: Um log pertence a um atendimento.
     */
    public function atendimento(): BelongsTo
    {
        return $this->belongsTo(Atendimentos::class, 'atendimento_id');
    }

    /**
     * Relacionamento: Um log pode ser criado por um usuário.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}