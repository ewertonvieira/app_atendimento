<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Atendimentos;
use App\Models\User;

class AtendimentosUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user(); // Obtém o usuário autenticado

        // Obter os atendimentos diretamente pelo relacionamento
        $atendimentos = $user->atendimentosComoCliente;

        return view('user.index-atendimentos', compact('atendimentos'));
    }

    /**
     * Exibe o formulário de criação de atendimento.
     */
    public function create()
    {
        $clientes = User::where('usertype', 'cliente')->get(); // Filtra apenas clientes
        return view('user.create-atendimentos', compact('clientes'));
    }

    /**
     * Salva um novo atendimento no banco de dados.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Verificar se os campos obrigatórios do usuário estão preenchidos
        if (!$user->rua || !$user->bairro || !$user->cep || !$user->estado || !$user->phone_number || !$user->cpf) {
            return redirect()->back()->withErrors([
                'user_incomplete' => 'Por favor, preencha todos os campos do seu perfil antes de criar um atendimento.',
            ]);
        }

        // Validação dos campos do atendimento
        $request->validate([
            'data_disponivel' => 'required|date',
            'hora_disponivel' => 'required',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Criar o atendimento
        Atendimentos::create([
            'cliente_id' => $user->id,
            'data_disponivel' => $request->input('data_disponivel'),
            'hora_disponivel' => $request->input('hora_disponivel'),
            'foto' => $request->hasFile('foto') ? $request->file('foto')->store('atendimentos', 'public') : null,
        ]);

        return redirect()->route('atendimentos.create')->with('success', 'Atendimento criado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Código para exibir um atendimento específico
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Código para exibir o formulário de edição
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validação dos dados
        $request->validate([
            'descricao' => 'required|string|max:255',
            'data_disponivel' => 'required|date',
            'hora_disponivel' => 'required',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Encontrar o atendimento pelo ID
        $atendimento = Atendimentos::findOrFail($id);

        // Verificar se o atendimento pertence ao usuário autenticado
        if ($atendimento->cliente_id !== Auth::id()) {
            abort(403, 'Acesso negado.');
        }

        // Atualizar os dados do atendimento
        $data = $request->only('descricao', 'data_disponivel', 'hora_disponivel');

        // Atualizar a foto, se fornecida
        if ($request->hasFile('foto')) {
            // Remover a foto antiga, se existir
            if ($atendimento->foto && \Storage::exists('public/' . $atendimento->foto)) {
                \Storage::delete('public/' . $atendimento->foto);
            }

            // Salvar a nova foto
            $data['foto'] = $request->file('foto')->store('atendimentos', 'public');
        }

        $atendimento->update($data);

        return redirect()->route('atendimentos.index')->with('success', 'Atendimento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Código para excluir um atendimento
    }
}