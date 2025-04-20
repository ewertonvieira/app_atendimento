<?php

namespace App\Http\Controllers\AtendimentoLogs;

use App\Http\Controllers\Controller;
use App\Models\AtendimentoLogs;
use Illuminate\Http\Request;

class AtendimentoLogsController extends Controller
{
    /**
     * Exibir todos os logs de atendimentos.
     */
    public function index()
    {
        $logs = AtendimentoLogs::with(['atendimento', 'user'])->get();

        return view('logs.index', compact('logs'));
    }

    /**
     * Exibir detalhes de um log específico.
     */
    public function show($id)
    {
        $log = AtendimentoLogs::with(['atendimento', 'user'])->findOrFail($id);

        return view('logs.show', compact('log'));
    }

    /**
     * Editar um atendimento específico.
     */
    public function edit($id)
    {
        $atendimento = Atendimentos::findOrFail($id);

        return view('user.edit-atendimento', compact('atendimento'));
    }

    /**
     * Atualizar um atendimento específico.
     */
    public function update(Request $request, $id)
    {
        // Validação dos campos
        $request->validate([
            'descricao' => 'nullable|string',
            'data_disponivel' => 'required|date',
            'hora_disponivel' => 'required|date_format:H:i', // Validação para o formato correto
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validação para imagens
        ]);

        // Buscar o atendimento no banco de dados
        $atendimento = Atendimentos::findOrFail($id);

        // Atualizar a foto, se enviada
        if ($request->hasFile('foto')) {
            $caminhoFoto = $request->file('foto')->store('atendimentos/fotos', 'public');
            $atendimento->foto = $caminhoFoto;
        }

        // Converter os valores antes de salvar no banco
        $atendimento->descricao = $request->descricao;
        $atendimento->data_disponivel = date('Y-m-d', strtotime($request->data_disponivel)); // Converter para formato de data
        $atendimento->hora_disponivel = date('H:i:s', strtotime($request->hora_disponivel)); // Converter para formato de hora
        $atendimento->save();

        // Redirecionar com mensagem de sucesso
        return redirect()->route('atendimentos.index')->with('success', 'Atendimento atualizado com sucesso!');
    }
}