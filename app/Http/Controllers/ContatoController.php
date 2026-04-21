<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ContatoController extends Controller
{
    /**
     * Exibe a página de contato
     */
    public function index(): View
    {
        return view('contato');
    }

    /**
     * Processa o formulário de contato
     */
    public function store(Request $request)
    {
        // Validação
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // TODO: Implementar envio de e-mail
        // Por enquanto, apenas retorna mensagem de sucesso
        return redirect()->route('contato')->with('success', 'Mensagem enviada com sucesso! Entraremos em contato em breve.');
    }
}
