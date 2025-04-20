<?php

namespace App\Http\Controllers\Notificacoes;

use App\Http\Controllers\Controller;
use App\Models\Notificacoes;
use Illuminate\Http\Request;

class NotificacoesController extends Controller
{
    /**
     * Exibir todas as notificações de um usuário.
     */
    public function index()
    {
        $notificacoes = Notificacoes::where('user_id', auth()->id())->get();

        return view('notificacoes.index', compact('notificacoes'));
    }

    /**
     * Marcar uma notificação como lida.
     */
    public function markAsRead($id)
    {
        $notificacao = Notificacoes::findOrFail($id);
        $notificacao->lida = true;
        $notificacao->save();

        return redirect()->route('notificacoes.index')->with('success', 'Notificação marcada como lida!');
    }
}