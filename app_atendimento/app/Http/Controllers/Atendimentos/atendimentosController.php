<?php

namespace App\Http\Controllers\Atendimentos;

use App\Http\Controllers\Controller;
use App\Models\Atendimentos;
use App\Models\AtendimentoLogs;
use App\Models\Pagamentos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AtendimentosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Listar todos os atendimentos com cliente e técnico associados
        $atendimentos = Atendimentos::with(['cliente', 'tecnico'])->get();

        return view('user.index-atendimentos', compact('atendimentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obter clientes (usuários do tipo 'user')
        $clientes = User::where('usertype', 'user')->get();

        return view('user.create-atendimentos', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'nullable|string',
            'data_disponivel' => 'required|date',
            'hora_disponivel' => 'required|date_format:H:i',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $horaDisponivel = date('H:i', strtotime($request->hora_disponivel));

        // Processar e salvar a foto
        $caminhoFoto = $request->file('foto') ? $request->file('foto')->store('atendimentos/fotos', 'public') : null;

        // Criar o atendimento
        $atendimento = Atendimentos::create([
            'cliente_id' => Auth::id(),
            'descricao' => $request->descricao,
            'data_disponivel' => $request->data_disponivel,
            'hora_disponivel' => $horaDisponivel,
            'status' => 'pendente',
            'foto' => $caminhoFoto,
        ]);

        // Registrar log de criação
        AtendimentoLogs::create([
            'atendimento_id' => $atendimento->id,
            'user_id' => Auth::id(),
            'acao' => 'criado',
            'descricao' => 'Atendimento criado pelo cliente.',
        ]);

        return redirect()->route('atendimentos.index')->with('success', 'Atendimento criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Exibir detalhes de um atendimento com cliente e técnico associados
        $atendimento = Atendimentos::with(['cliente', 'tecnico', 'logs', 'pagamentos'])->findOrFail($id);

        return view('atendimentos.show', compact('atendimento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Obter o atendimento para edição
        $atendimento = Atendimentos::findOrFail($id);
        $clientes = User::where('usertype', 'user')->get();

        return view('user.edit-atendimento', compact('atendimento', 'clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'descricao' => 'nullable|string',
            'data_disponivel' => 'required|date',
            'hora_disponivel' => 'required|date_format:H:i',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $atendimento = Atendimentos::findOrFail($id);

        if ($request->hasFile('foto')) {
            $caminhoFoto = $request->file('foto')->store('atendimentos/fotos', 'public');
            $atendimento->foto = $caminhoFoto;
        }

        $atendimento->descricao = $request->descricao;
        $atendimento->data_disponivel = $request->data_disponivel;
        $atendimento->hora_disponivel = $request->hora_disponivel;
        $atendimento->save();

        // Registrar log de atualização
        AtendimentoLogs::create([
            'atendimento_id' => $atendimento->id,
            'user_id' => Auth::id(),
            'acao' => 'atualizado',
            'descricao' => 'Atendimento atualizado pelo cliente.',
        ]);

        return redirect()->route('atendimentos.index')->with('success', 'Atendimento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $atendimento = Atendimentos::findOrFail($id);

        // Registrar log de exclusão
        AtendimentoLogs::create([
            'atendimento_id' => $atendimento->id,
            'user_id' => Auth::id(),
            'acao' => 'excluído',
            'descricao' => 'Atendimento excluído pelo cliente.',
        ]);

        $atendimento->delete();

        return redirect()->route('atendimentos.index')->with('success', 'Atendimento excluído com sucesso!');
    }

    /**
     * Técnico pega um atendimento.
     */
    public function pegarAtendimento($id)
    {
        $atendimento = Atendimentos::findOrFail($id);
        $user = Auth::user();

        if ($user->usertype !== 'tecnico') {
            return redirect()->back()->withErrors(['error' => 'Apenas técnicos podem pegar atendimentos.']);
        }

        if ($atendimento->tecnico_id) {
            return redirect()->back()->withErrors(['error' => 'Este atendimento já foi atribuído a outro técnico.']);
        }

        $atendimento->tecnico_id = $user->id;
        $atendimento->status = 'agendado';
        $atendimento->save();

        // Registrar log de atribuição
        AtendimentoLogs::create([
            'atendimento_id' => $atendimento->id,
            'user_id' => $user->id,
            'acao' => 'atribuído',
            'descricao' => 'Atendimento atribuído ao técnico.',
        ]);

        return redirect()->route('tecnico.dashboard')->with('success', 'Atendimento atribuído com sucesso!');
    }

    /**
     * Técnico confirma um atendimento.
     */
    public function confirmarAtendimento(Request $request, $id)
    {
        $atendimento = Atendimentos::findOrFail($id);
        $user = Auth::user();

        if ($user->usertype !== 'tecnico') {
            return redirect()->back()->withErrors(['error' => 'Apenas técnicos podem confirmar atendimentos.']);
        }

        $request->validate([
            'status' => 'required|in:agendado,concluido,cancelado',
        ]);

        $atendimento->status = $request->status;
        $atendimento->tecnico_id = $user->id;
        $atendimento->data_agendada = now();
        $atendimento->save();

        // Registrar log de confirmação
        AtendimentoLogs::create([
            'atendimento_id' => $atendimento->id,
            'user_id' => $user->id,
            'acao' => 'confirmado',
            'descricao' => 'Atendimento confirmado pelo técnico.',
        ]);

        return redirect()->route('atendimentos.index')->with('success', 'Atendimento confirmado com sucesso!');
    }
}