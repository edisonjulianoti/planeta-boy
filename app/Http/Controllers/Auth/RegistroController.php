<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        $request->validate([
            'nome'             => 'required|string|min:2|max:255',
            'email'            => 'required|email|unique:users,email',
            'whatsapp'         => 'nullable|string|min:14|max:15',
            'senha'            => 'required|string|min:6|confirmed',
            'cpf'              => 'required|string|max:14|unique:users,cpf',
            'data_nascimento'  => 'required|date|before:today|after:-100 years',
        ], [
            'nome.required'             => 'O nome é obrigatório.',
            'nome.min'                  => 'O nome deve ter no mínimo 2 caracteres.',
            'email.required'            => 'O e-mail é obrigatório.',
            'email.email'               => 'Informe um e-mail válido.',
            'email.unique'              => 'Este e-mail já está cadastrado.',
            'senha.required'            => 'A senha é obrigatória.',
            'senha.min'                 => 'A senha deve ter no mínimo 6 caracteres.',
            'senha.confirmed'           => 'As senhas não coincidem.',
            'cpf.required'              => 'O CPF é obrigatório.',
            'cpf.max'                   => 'CPF inválido.',
            'cpf.unique'                => 'Este CPF já está cadastrado.',
            'data_nascimento.required'  => 'A data de nascimento é obrigatória.',
            'data_nascimento.date'      => 'Data de nascimento inválida.',
            'data_nascimento.before'    => 'A data de nascimento deve ser anterior a hoje.',
            'data_nascimento.after'     => 'A data de nascimento deve ser posterior a 100 anos.',
        ]);

        $usuario = User::create([
            'name'            => $request->nome,
            'email'           => $request->email,
            'password'        => Hash::make($request->senha),
            'phone'           => $request->whatsapp,
            'cpf'             => $request->cpf,
            'data_nascimento' => $request->data_nascimento,
            'plan'            => 'free',
        ]);

        Auth::login($usuario);

        return redirect()->route('perfil')->with('status', 'Conta criada com sucesso! Bem-vindo(a)!');
    }
}
