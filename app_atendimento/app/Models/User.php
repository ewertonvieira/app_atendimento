<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'usertype',
        'rua',
        'bairro',
        'cep',
        'estado',
        'phone_number',
        'cpf', // Novo campo
        'data_nascimento', // Novo campo
        'avatar', // Novo campo
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'data_nascimento' => 'date',
    ];

    /**
     * Relacionamento: Um cliente pode ter vários atendimentos.
     */
    public function atendimentosComoCliente(): HasMany
    {
        return $this->hasMany(Atendimentos::class, 'cliente_id');
    }

    /**
     * Relacionamento: Um técnico pode ter vários atendimentos.
     */
    public function atendimentosComoTecnico(): HasMany
    {
        return $this->hasMany(Atendimentos::class, 'tecnico_id');
    }

    /**
     * Relacionamento: Um usuário pode ter várias notificações.
     */
    public function notificacoes(): HasMany
    {
        return $this->hasMany(Notificacoes::class, 'user_id');
    }
}
