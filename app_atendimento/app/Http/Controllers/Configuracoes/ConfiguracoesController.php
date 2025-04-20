<?php

namespace App\Http\Controllers\Configuracoes;

use App\Http\Controllers\Controller;
use App\Models\Configuracoes;
use Illuminate\Http\Request;

class ConfiguracoesController extends Controller
{
    /**
     * Exibir todas as configurações.
     */
    public function index()
    {
        $configuracoes = Configuracoes::all();

        return view('configuracoes.index', compact('configuracoes'));
    }

    /**
     * Atualizar uma configuração.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'valor' => 'required|string',
        ]);

        $configuracao = Configuracoes::findOrFail($id);
        $configuracao->valor = $request->valor;
        $configuracao->save();

        return redirect()->route('configuracoes.index')->with('success', 'Configuração atualizada com sucesso!');
    }
}