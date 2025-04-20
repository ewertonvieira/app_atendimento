<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class CustomUserRegistrationController extends Controller
{
    /**
     * Exibe o formulário de registro personalizado.
     */
    public function create()
    {
        return view('auth.custom-register');
    }

    /**
     * Lida com a solicitação de registro personalizado.
     */
    public function store(Request $request)
    {
        // Validação dos campos
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'rua' => ['nullable', 'string', 'max:255'],
            'bairro' => ['nullable', 'string', 'max:255'],
            'cep' => ['nullable', 'string', 'max:10'],
            'estado' => ['nullable', 'string', 'max:2'],
            'phone_number' => ['nullable', 'string', 'max:15'],
            'cpf' => ['nullable', 'string', 'max:14', 'unique:users'],
            'data_nascimento' => ['nullable', 'date'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        // Criar o usuário com as informações adicionais
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => 'user', // Define o tipo de usuário como "user"
            'rua' => $request->rua,
            'bairro' => $request->bairro,
            'cep' => $request->cep,
            'estado' => $request->estado,
            'phone_number' => $request->phone_number,
            'cpf' => $request->cpf,
            'data_nascimento' => $request->data_nascimento,
            'avatar' => $request->hasFile('avatar') ? $request->file('avatar')->store('avatars', 'public') : null,
        ]);

        // Dispara o evento de registro
        event(new Registered($user));

        // Redireciona para o dashboard ou outra página
        return redirect()->route('dashboard')->with('success', 'Usuário registrado com sucesso!');
    }
}