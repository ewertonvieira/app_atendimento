<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
            'request' => $request,
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
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:15',
            'rua' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'cep' => 'nullable|string|max:10',
            'estado' => 'nullable|string|max:2',
            'cpf' => 'nullable|string|max:14',
            'data_nascimento' => 'nullable|date', // Validação para Data de Nascimento
            'avatar' => 'nullable|image|max:2048', // Validação para upload de imagem
        ]);

        \Log::info('Dados validados recebidos:', $validatedData);

        // Atualizar os dados do usuário
        $data = $request->only(
            'name',
            'email',
            'phone_number',
            'rua',
            'bairro',
            'cep',
            'estado',
            'cpf'
        );

        // Converter a data de nascimento para o formato correto
        if ($request->filled('data_nascimento')) {
            $data['data_nascimento'] = Carbon::createFromFormat('Y-m-d', $request->data_nascimento)->format('Y-m-d');
        }

        // Verificar se há upload de avatar
        if ($request->hasFile('avatar')) {
            // Excluir avatar antigo, se existir
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Salvar novo avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path; // Salvar o caminho no banco de dados
        }

        // Atualizar o usuário
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
    public function showCreateProfileForm()
    {
        return view('user.create-profile'); // Exibe o formulário de criação de perfil
    }

    /**
     * Cria o perfil do usuário.
     */
    public function createProfile(Request $request)
    {
        $user = Auth::user();

        // Validação dos campos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rua' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'cep' => 'required|string|max:10',
            'estado' => 'required|string|max:2',
            'phone_number' => 'required|string|max:15',
            'cpf' => 'required|string|max:14',
            'data_nascimento' => 'nullable|date',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Atualizar os dados do usuário
        $data = $request->only(
            'name',
            'email',
            'rua',
            'bairro',
            'cep',
            'estado',
            'phone_number',
            'cpf',
            'data_nascimento'
        );

        // Verificar se há upload de avatar
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return redirect()->route('user.dashboard')->with('success', 'Perfil criado com sucesso!');
    }

    /**
     * Armazena o perfil do usuário.
     */
    public function storeProfile(Request $request)
    {
        // Validação dos campos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // Validação para senha
            'rua' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'cep' => 'required|string|max:10',
            'estado' => 'required|string|max:2',
            'phone_number' => 'required|string|max:15',
            'cpf' => 'required|string|max:14|unique:users,cpf',
            'data_nascimento' => 'nullable|date',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Criar o novo usuário
        $data = $request->only(
            'name',
            'email',
            'rua',
            'bairro',
            'cep',
            'estado',
            'phone_number',
            'cpf',
            'data_nascimento'
        );

        // Adicionar o tipo de usuário
        $data['usertype'] = 'user';

        // Criptografar a senha
        $data['password'] = bcrypt($request->password);

        // Verificar se há upload de avatar
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        // Salvar o usuário no banco de dados
        User::create($data);

        return redirect()->route('user.create-profile')->with('success', 'Usuário criado com sucesso!');
    }
}
