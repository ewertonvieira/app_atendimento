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
        Schema::create('atendimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('users')->onDelete('cascade');
            $table->text('descricao')->nullable();
            $table->date('data_disponivel');
            $table->time('hora_disponivel');
            $table->enum('status', ['pendente', 'agendado', 'concluido', 'cancelado'])->default('pendente');
            $table->foreignId('tecnico_id')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('data_agendada')->nullable();
            $table->decimal('valor_comissao', 10, 2)->nullable();
            $table->string('foto')->nullable();
            $table->enum('prioridade', ['baixa', 'media', 'alta'])->default('media'); // Prioridade do atendimento
            $table->text('feedback_cliente')->nullable(); // Feedback do cliente após o atendimento
            $table->integer('tempo_estimado')->nullable(); // Tempo estimado em minutos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atendimentos');
    }
};
