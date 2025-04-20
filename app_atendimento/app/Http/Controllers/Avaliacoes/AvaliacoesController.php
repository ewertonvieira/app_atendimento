<?php

namespace App\Http\Controllers\Avaliacoes;

use App\Http\Controllers\Controller;
use App\Models\Avaliacoes;
use Illuminate\Http\Request;

class AvaliacoesController extends Controller
{
    /**
     * Exibir todas as avaliações.
     */
    public function index()
    {
        $avaliacoes = Avaliacoes::with(['atendimento', 'cliente'])->get();

        return view('avaliacoes.index', compact('avaliacoes'));
    }

    /**
     * Exibir detalhes de uma avaliação específica.
     */
    public function show($id)
    {
        $avaliacao = Avaliacoes::with(['atendimento', 'cliente'])->findOrFail($id);

        return view('avaliacoes.show', compact('avaliacao'));
    }

    /**
     * Criar uma nova avaliação.
     */
    public function store(Request $request)
    {
        $request->validate([
            'atendimento_id' => 'required|exists:atendimentos,id',
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string',
        ]);

        Avaliacoes::create($request->all());

        return redirect()->route('avaliacoes.index')->with('success', 'Avaliação criada com sucesso!');
    }
}