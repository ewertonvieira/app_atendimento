<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Obter os tickets do usuário autenticado
        $tickets = \App\Models\Atendimentos::where('cliente_id', auth()->id())->get();

        // Contar os tickets com status "pendente"
        $ticketsAbertos = \App\Models\Atendimentos::where('cliente_id', auth()->id())
            ->where('status', 'pendente')
            ->count();

        // Retornar a view com os tickets e a contagem de tickets abertos
        return view('user.dashboard', compact('tickets', 'ticketsAbertos'));
    }

    public function editProfile()
    {
        $user = auth()->user(); // Obtém o usuário autenticado
        return view('user.edit-profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        // Validação dos campos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'rua' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'cep' => 'nullable|string|max:10',
            'estado' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:15',
        ]);

        // Atualizar o usuário autenticado
        $user = auth()->user();
        $user->update($request->only('name', 'email', 'rua', 'bairro', 'cep', 'estado', 'phone_number'));

        // Redirecionar com mensagem de sucesso
        return redirect()->route('user.edit-profile')->with('success', 'Perfil atualizado com sucesso!');
    }
}
