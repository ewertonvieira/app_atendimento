<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirecionar com base no tipo de usuário
        switch (Auth::user()->usertype) {
            case 'admin':
                return redirect(route('admin.dashboard')); // Rota para admin
            case 'tecnico':
                return redirect(route('tecnico.dashboard')); // Rota para técnico
            case 'user':
                return redirect(route('user.dashboard')); // Rota para usuário
            default:
                // Caso o tipo de usuário não seja reconhecido
                Auth::logout();
                return redirect('/')->withErrors(['error' => 'Tipo de usuário inválido.']);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
