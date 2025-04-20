<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // ID do usuário
            $table->string('name'); // Nome do usuário
            $table->string('email')->unique(); // Email único
            $table->string('usertype')->default('user'); // Tipo de usuário (padrão: user)
            $table->string('rua')->nullable(); // Rua (opcional)
            $table->string('bairro')->nullable(); // Bairro (opcional)
            $table->string('cep')->nullable(); // CEP (opcional)
            $table->string('estado', 2)->nullable(); // Estado (opcional, 2 caracteres)
            $table->string('phone_number', 15)->nullable(); // Telefone (opcional, máx. 15 caracteres)
            $table->string('cpf', 14)->unique()->nullable(); // CPF único (opcional, máx. 14 caracteres)
            $table->date('data_nascimento')->nullable(); // Data de nascimento (opcional)
            $table->string('avatar')->nullable(); // Foto de perfil (opcional)
            $table->timestamp('email_verified_at')->nullable(); // Verificação de email
            $table->string('password'); // Senha
            $table->rememberToken(); // Token de "lembrar-me"
            $table->timestamps(); // Campos de auditoria (created_at, updated_at)
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Email como chave primária
            $table->string('token'); // Token de redefinição de senha
            $table->timestamp('created_at')->nullable(); // Data de criação do token
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // ID da sessão como chave primária
            $table->foreignId('user_id')->nullable()->index(); // Relacionamento com a tabela de usuários
            $table->string('ip_address', 45)->nullable(); // Endereço IP
            $table->text('user_agent')->nullable(); // Agente do usuário (navegador, etc.)
            $table->longText('payload'); // Dados da sessão
            $table->integer('last_activity')->index(); // Última atividade
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users'); // Remove a tabela de usuários
        Schema::dropIfExists('password_reset_tokens'); // Remove a tabela de tokens de redefinição de senha
        Schema::dropIfExists('sessions'); // Remove a tabela de sessões
    }
};
