<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Atendimentos extends Model
{
    protected $fillable = [
        'cliente_id',
        'descricao',
        'data_disponivel',
        'hora_disponivel',
        'status',
        'tecnico_id',
        'data_agendada',
        'valor_comissao',
        'foto',
        'prioridade', // Novo campo
        'feedback_cliente', // Novo campo
        'tempo_estimado', // Novo campo
    ];

    /**
     * Relacionamento: Um atendimento pertence a um cliente.
     */
    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    /**
     * Relacionamento: Um atendimento pertence a um técnico.
     */
    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    /**
     * Relacionamento: Um atendimento pode ter vários logs.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(AtendimentoLogs::class, 'atendimento_id');
    }

    /**
     * Relacionamento: Um atendimento pode ter vários pagamentos.
     */
    public function pagamentos(): HasMany
    {
        return $this->hasMany(Pagamentos::class, 'atendimento_id');
    }

    /**
     * Relacionamento: Um atendimento pode ter várias avaliações.
     */
    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacoes::class, 'atendimento_id');
    }
}