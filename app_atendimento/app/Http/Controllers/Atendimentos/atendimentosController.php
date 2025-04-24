<?php

namespace App\Http\Controllers\Atendimentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Atendimentos;
use App\Models\User;

class AtendimentosController extends Controller
{
    // List all atendimentos
    public function index()
    {
        $atendimentos = Atendimentos::with(['cliente', 'tecnico'])->get();
        $clientes = User::where('usertype', 'user')->get(); // Carregar clientes
        $tecnicos = User::where('usertype', 'tecnico')->get(); // Carregar técnicos

        // Atualize o caminho da view para refletir o nome correto
        return view('admin.atendimentos.index-atendimentos', compact('atendimentos', 'clientes', 'tecnicos'));
    }

    // Show a single atendimento
    public function show($id)
    {
        $atendimento = Atendimentos::with(['cliente', 'tecnico'])->find($id);

        if (!$atendimento) {
            return redirect()->route('admin.atendimentos.index')->with('error', 'Atendimento não encontrado.');
        }

        return view('admin.atendimentos.show', compact('atendimento'));
    }

    // Create a new atendimento
    public function create()
    {
        $clientes = User::where('usertype', 'user')->get();
        $tecnicos = User::where('usertype', 'tecnico')->get();
        return view('admin.atendimentos.create', compact('clientes', 'tecnicos'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:users,id',
            'tecnico_id' => 'required|exists:users,id',
            'descricao' => 'nullable|string',
            'data_disponivel' => 'required|date',
            'hora_disponivel' => 'required',
            'status' => 'required|string',
            'data_agendada' => 'nullable|date',
            'valor_comissao' => 'nullable|numeric',
            'foto' => 'nullable|image',
            'prioridade' => 'nullable|string',
            'feedback_cliente' => 'nullable|string',
            'tempo_estimado' => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            $validatedData['foto'] = $request->file('foto')->store('atendimentos', 'public');
        }

        Atendimentos::create($validatedData);

        return redirect()->route('admin.atendimentos.index')->with('success', 'Atendimento criado com sucesso.');
    }

    // Update an existing atendimento
    public function edit($id)
    {
        $atendimento = Atendimentos::find($id);

        if (!$atendimento) {
            return redirect()->route('admin.atendimentos.index')->with('error', 'Atendimento não encontrado.');
        }

        $clientes = User::where('usertype', 'cliente')->get();
        $tecnicos = User::where('usertype', 'tecnico')->get();

        return view('admin.atendimentos.edit', compact('atendimento', 'clientes', 'tecnicos'));
    }

    public function update(Request $request, $id)
    {
        $atendimento = Atendimentos::find($id);

        if (!$atendimento) {
            return redirect()->route('admin.atendimentos.index')->with('error', 'Atendimento não encontrado.');
        }

        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:users,id',
            'tecnico_id' => 'required|exists:users,id',
            'descricao' => 'nullable|string',
            'data_disponivel' => 'required|date',
            'hora_disponivel' => 'required',
            'status' => 'required|string',
            'data_agendada' => 'nullable|date',
            'valor_comissao' => 'nullable|numeric',
            'foto' => 'nullable|image',
            'prioridade' => 'nullable|string',
            'feedback_cliente' => 'nullable|string',
            'tempo_estimado' => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            $validatedData['foto'] = $request->file('foto')->store('atendimentos', 'public');
        }

        $atendimento->update($validatedData);

        return redirect()->route('admin.atendimentos.index')->with('success', 'Atendimento atualizado com sucesso.');
    }

    // Delete an atendimento
    public function destroy(Request $request)
    {
        $ids = $request->input('ids'); // Recebe os IDs dos atendimentos a serem excluídos

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'Nenhum atendimento selecionado para exclusão.']);
        }

        try {
            Atendimentos::whereIn('id', $ids)->delete(); // Exclui os registros com os IDs fornecidos
            return response()->json(['success' => true, 'message' => 'Atendimentos excluídos com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir atendimentos.']);
        }
    }
}