<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\Cpf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegistroController extends Controller
{
    public function form(): View
    {
        return view('auth.registro');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nome'             => 'required|string|min:2|max:255',
            'email'            => 'required|email|unique:users,email',
            'whatsapp'         => 'nullable|string|min:14|max:15',
            'senha'            => 'required|string|min:6|confirmed',
            'cpf'              => ['required', 'string', 'max:14', 'unique:users,cpf', new Cpf],
            'data_nascimento'  => 'required|date_format:d/m/Y|before:today|after:-100 years',
            'lgpd_consent'     => 'accepted',
        ], [
            'nome.required'             => 'O nome e obrigatorio.',
            'nome.min'                  => 'O nome deve ter no minimo 2 caracteres.',
            'email.required'            => 'O e-mail e obrigatorio.',
            'email.email'               => 'Informe um e-mail valido.',
            'email.unique'              => 'Este e-mail ja esta cadastrado.',
            'senha.required'            => 'A senha e obrigatoria.',
            'senha.min'                 => 'A senha deve ter no minimo 6 caracteres.',
            'senha.confirmed'           => 'As senhas nao coincidem.',
            'cpf.required'              => 'O CPF e obrigatorio.',
            'cpf.max'                   => 'CPF invalido.',
            'cpf.unique'                => 'Este CPF ja esta cadastrado.',
            'data_nascimento.required'  => 'A data de nascimento e obrigatoria.',
            'data_nascimento.date_format' => 'Data de nascimento invalida. Use o formato dd/mm/aaaa.',
            'data_nascimento.before'    => 'A data de nascimento deve ser anterior a hoje.',
            'data_nascimento.after'     => 'Data de nascimento invalida.',
            'lgpd_consent.accepted'     => 'Voce precisa aceitar a Politica de Privacidade e autorizar o tratamento dos seus dados pessoais (LGPD).',
        ]);

        $dataNascimento = Carbon::createFromFormat('d/m/Y', $validated['data_nascimento'])->format('Y-m-d');

        $usuario = User::create([
            'name'            => $validated['nome'],
            'email'           => $validated['email'],
            'password'        => Hash::make($validated['senha']),
            'phone'           => $validated['whatsapp'] ?? null,
            'cpf'             => $validated['cpf'],
            'data_nascimento' => $dataNascimento,
            'plan'            => 'free',
        ]);

        // Envia e-mail de verificacao
        $usuario->sendEmailVerificationNotification();

        Auth::login($usuario);

        return redirect()->route('verification.notice')
            ->with('status', 'Conta criada! Verifique seu e-mail para ativar sua conta.');
    }
}
