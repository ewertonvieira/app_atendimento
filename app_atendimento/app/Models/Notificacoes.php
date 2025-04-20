<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacoes extends Model
{
    protected $fillable = [
        'user_id',
        'titulo',
        'mensagem',
        'lida',
    ];

    /**
     * Relacionamento: Uma notificação pertence a um usuário.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}