<?php

namespace App\Http\Controllers\Pagamentos;

use App\Http\Controllers\Controller;
use App\Models\Pagamentos;
use Illuminate\Http\Request;

class PagamentosController extends Controller
{
    /**
     * Exibir todos os pagamentos.
     */
    public function index()
    {
        $pagamentos = Pagamentos::with('atendimento')->get();

        return view('pagamentos.index', compact('pagamentos'));
    }

    /**
     * Exibir detalhes de um pagamento específico.
     */
    public function show($id)
    {
        $pagamento = Pagamentos::with('atendimento')->findOrFail($id);

        return view('pagamentos.show', compact('pagamento'));
    }

    /**
     * Atualizar o status de um pagamento.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pendente,pago,cancelado',
        ]);

        $pagamento = Pagamentos::findOrFail($id);
        $pagamento->status = $request->status;
        $pagamento->save();

        return redirect()->route('pagamentos.index')->with('success', 'Pagamento atualizado com sucesso!');
    }
}