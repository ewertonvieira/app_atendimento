<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Exibe o dashboard do usuário.
     */
    public function index()
    {
        $user = Auth::user();

        // Obter os atendimentos do usuário autenticado
        $atendimentos = $user->atendimentosComoCliente;

        // Contar os atendimentos com status "pendente"
        $atendimentosPendentes = $atendimentos->where('status', 'pendente')->count();

        return view('user.dashboard', compact('atendimentos', 'atendimentosPendentes'));
    }

    /**
     * Exibe o formulário de edição do perfil do usuário.
     */
    public function editProfile(Request $request)
    {
        $user = Auth::user();

        return view('user.update-profile', [
            'user' => $user,
            'request' => $request, // Passa o $request para a view
        ]);
    }

    /**
     * Atualiza o perfil do usuário autenticado.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validação dos campos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'rua' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'cep' => 'required|string|max:10',
            'estado' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'cpf' => 'required|string|max:14',
            'data_nascimento' => 'nullable|date',
            'avatar' => 'nullable|image|max:2048', // Validação para upload de imagem
        ]);

        // Atualizar os dados do usuário
        $data = $request->only('name', 'email', 'rua', 'bairro', 'cep', 'estado', 'phone_number', 'cpf', 'data_nascimento');

        // Verificar se há upload de avatar
        if ($request->hasFile('avatar')) {
            // Remover o avatar antigo, se existir
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }

            // Salvar o novo avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        return redirect()->route('user.edit-profile')->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Atualiza informações ou senha do usuário autenticado.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validação dos campos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:15',
            'rua' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'cep' => 'nullable|string|max:10',
            'estado' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Atualizar os dados do usuário
        $data = $request->only('name', 'email', 'phone_number', 'rua', 'bairro', 'cep', 'estado');

        // Verificar se há upload de avatar
        if ($request->hasFile('avatar')) {
            // Remover o avatar antigo, se existir
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }

            // Salvar o novo avatar
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('user.edit-profile')->with('success', 'Informações atualizadas com sucesso!');
    }

    /**
     * Atualiza a senha do usuário autenticado.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user.edit-profile')->with('success', 'Senha atualizada com sucesso!');
    }

    /**
     * Exibe os atendimentos relacionados ao usuário autenticado.
     */
    public function atendimentos()
    {
        $user = Auth::user();

        // Obter os atendimentos do cliente
        $atendimentos = $user->atendimentosComoCliente;

        return view('user.atendimentos', compact('atendimentos'));
    }

    /**
     * Exibe o formulário de criação do perfil do usuário.
     */
    public function createProfile()
    {
        $user = Auth::user(); // Obtém o usuário autenticado
        return view('user.create-profile', compact('user')); // Certifique-se de que o nome da view está correto
    }
}
