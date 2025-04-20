<?php

namespace App\Http\Controllers\Tecnico;

use App\Http\Controllers\Controller;
use App\Models\Atendimentos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AtendimentosTecnicoController extends Controller
{
    /**
     * Lista os atendimentos disponíveis para o técnico.
     */
    public function index()
    {
        // Obter atendimentos com status "pendente"
        $atendimentos = Atendimentos::where('status', 'pendente')->with('cliente')->get();

        // Certifique-se de que o nome da view está correto
        return view('tecnico.index-atendimentos', compact('atendimentos'));
    }

    /**
     * Aceitar um atendimento.
     */
    public function aceitarAtendimento($id)
    {
        $tecnico = Auth::user(); // Técnico autenticado

        // Encontrar o atendimento
        $atendimento = Atendimentos::findOrFail($id);

        // Atualizar o status e associar o técnico
        $atendimento->update([
            'status' => 'agendado',
            'tecnico_id' => $tecnico->id,
        ]);

        return redirect()->route('tecnico.atendimentos.index')->with('success', 'Atendimento aceito com sucesso!');
    }

    /**
     * Lista os atendimentos aceitos pelo técnico.
     */
    public function atendimentosAceitos()
    {
        // Obter atendimentos aceitos pelo técnico autenticado
        $atendimentos = Atendimentos::where('tecnico_id', Auth::id())
            ->whereIn('status', ['agendado'])
            ->with('cliente')
            ->get();

        return view('tecnico.index-aten-pego', compact('atendimentos'));
    }

    /**
     * Cancelar um atendimento.
     */
    public function cancelarAtendimento($id)
    {
        $atendimento = Atendimentos::findOrFail($id);

        // Verificar se o técnico autenticado é responsável pelo atendimento
        if ($atendimento->tecnico_id !== Auth::id()) {
            abort(403, 'Ação não autorizada.');
        }

        // Atualizar o status do atendimento para "pendente" e remover o técnico associado
        $atendimento->update([
            'status' => 'pendente',
            'tecnico_id' => null,
        ]);

        return redirect()->route('tecnico.atendimentos.aceitos')->with('success', 'Atendimento devolvido para a lista de pendentes com sucesso!');
    }

    /**
     * Concluir um atendimento.
     */
    public function concluirAtendimento(Request $request, $id)
    {
        $atendimento = Atendimentos::findOrFail($id);

        // Verificar se o técnico autenticado é responsável pelo atendimento
        if ($atendimento->tecnico_id !== Auth::id()) {
            abort(403, 'Ação não autorizada.');
        }

        // Atualizar o status do atendimento para "concluído"
        $atendimento->update(['status' => 'concluido']);

        // Redirecionar para a página de criação de pagamento
        return redirect()->route('tecnico.create-pagamento', ['id' => $atendimento->id]);
    }

    /**
     * Registrar um pagamento.
     */
    public function storePagamento(Request $request, $id)
    {
        $atendimento = Atendimentos::findOrFail($id);

        // Verificar se o técnico autenticado é responsável pelo atendimento
        if ($atendimento->tecnico_id !== Auth::id()) {
            abort(403, 'Ação não autorizada.');
        }

        // Validar o valor do pagamento
        $request->validate([
            'valor' => 'required|string',
        ]);

        // Converter o valor para o formato americano
        $valor = str_replace(['R$', '.', ','], ['', '', '.'], $request->valor);

        // Criar o registro de pagamento
        $atendimento->pagamentos()->create([
            'valor' => $valor,
            'status' => 'pendente',
            'data_pagamento' => null, // Cliente definirá posteriormente
            'metodo_pagamento' => null, // Cliente definirá posteriormente
        ]);

        return redirect()->route('tecnico.dashboard')->with('success', 'Pagamento registrado com sucesso!');
    }
}