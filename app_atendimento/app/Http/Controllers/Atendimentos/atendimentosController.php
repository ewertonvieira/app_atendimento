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
        return view('admin.atendimentos.index', compact('atendimentos'));
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
        $clientes = User::where('role', 'cliente')->get();
        $tecnicos = User::where('role', 'tecnico')->get();
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

        $clientes = User::where('role', 'cliente')->get();
        $tecnicos = User::where('role', 'tecnico')->get();

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
    public function destroy($id)
    {
        $atendimento = Atendimentos::find($id);

        if (!$atendimento) {
            return redirect()->route('admin.atendimentos.index')->with('error', 'Atendimento não encontrado.');
        }

        $atendimento->delete();

        return redirect()->route('admin.atendimentos.index')->with('success', 'Atendimento excluído com sucesso.');
    }
}