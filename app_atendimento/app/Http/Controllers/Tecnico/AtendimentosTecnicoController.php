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
        $atendimentos = Atendimentos::where('status', 'pendente')->with('cliente')->get();
        return view('tecnico.index-atendimentos', compact('atendimentos'));
    }

    /**
     * Aceitar um atendimento.
     */
    public function aceitarAtendimento($id)
    {
        $tecnico = Auth::user();
        $atendimento = Atendimentos::findOrFail($id);

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
        $atendimentos = Atendimentos::where('tecnico_id', Auth::id())
            ->where('status', 'agendado')
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

        if ($atendimento->tecnico_id !== Auth::id()) {
            abort(403, 'Ação não autorizada.');
        }

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

        if ($atendimento->tecnico_id !== Auth::id()) {
            abort(403, 'Ação não autorizada.');
        }

        $atendimento->update(['status' => 'concluido']);

        return redirect()->route('tecnico.create-pagamento', ['id' => $atendimento->id]);
    }

    /**
     * Registrar um pagamento.
     */
    public function storePagamento(Request $request, $id)
    {
        $atendimento = Atendimentos::findOrFail($id);

        if ($atendimento->tecnico_id !== Auth::id()) {
            abort(403, 'Ação não autorizada.');
        }

        $request->validate([
            'valor' => 'required|string',
        ]);

        $valor = str_replace(['R$', '.', ','], ['', '', '.'], $request->valor);

        $atendimento->pagamentos()->create([
            'valor' => $valor,
            'status' => 'pendente',
            'data_pagamento' => null,
            'metodo_pagamento' => null,
        ]);

        return redirect()->route('tecnico.dashboard')->with('success', 'Pagamento registrado com sucesso!');
    }

    /**
     * Exibir o dashboard do técnico.
     */
    public function dashboard()
    {
        $tecnicoId = Auth::id();

        $atendimentosDisponiveis = Atendimentos::where('status', 'pendente')->count();
        $atendimentosAceitos = Atendimentos::where('tecnico_id', $tecnicoId)
            ->where('status', 'agendado')
            ->count();
        $valorTotalPagamentos = Atendimentos::where('tecnico_id', $tecnicoId)
            ->where('atendimentos.status', 'concluido')
            ->whereMonth('atendimentos.updated_at', now()->month)
            ->join('pagamentos', 'atendimentos.id', '=', 'pagamentos.atendimento_id')
            ->sum('pagamentos.valor');

        return view('tecnico.dashboard', compact('atendimentosDisponiveis', 'atendimentosAceitos', 'valorTotalPagamentos'));
    }
}