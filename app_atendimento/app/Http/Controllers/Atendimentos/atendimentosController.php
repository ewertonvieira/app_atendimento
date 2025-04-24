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
        return view('admin.atendimentos.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:users,id',
            'descricao' => 'nullable|string',
            'data_disponivel' => 'required|date',
            'hora_disponivel' => 'required',
            'foto' => 'nullable|image',
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
        $atendimento = Atendimentos::with('cliente', 'tecnico')->find($id);

        if (!$atendimento) {
            return redirect()->route('admin.atendimentos.index')->with('error', 'Atendimento não encontrado.');
        }

        $tecnicos = User::where('usertype', 'tecnico')->get();

        return view('admin.atendimentos.edit', compact('atendimento', 'tecnicos'));
    }

    public function update(Request $request, $id)
    {
        $atendimento = Atendimentos::find($id);

        if (!$atendimento) {
            return redirect()->route('admin.atendimentos.index')->with('error', 'Atendimento não encontrado.');
        }

        // Validação dos dados
        $validatedData = $request->validate([
            'tecnico_id' => 'nullable|exists:users,id', // Técnico deve existir na tabela de usuários
            'descricao' => 'nullable|string',
            'data_disponivel' => 'required|date',
            'hora_disponivel' => 'required',
            'foto' => 'nullable|image',
        ]);

        // Atualizar o status para "agendado" se um técnico for selecionado
        if ($request->filled('tecnico_id')) {
            $validatedData['status'] = 'agendado';
        } else {
            $validatedData['status'] = 'pendente';
        }

        // Verificar se uma nova foto foi enviada
        if ($request->hasFile('foto')) {
            $validatedData['foto'] = $request->file('foto')->store('atendimentos', 'public');
        }

        // Atualizar o atendimento no banco de dados
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