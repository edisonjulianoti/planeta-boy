@extends('layouts.app')

@section('title', 'Criar Conta - PLANETA BOYS')

@section('content')
<div class="min-h-screen bg-black flex flex-col justify-center py-12">
    <x-ui.container size="lg">
        <div class="sm:mx-auto sm:w-full sm:max-w-lg">
            <div class="flex justify-center">
                <span class="text-2xl font-black uppercase italic tracking-tight text-white"><span class="text-primary">PLANETA</span><span class="text-foreground">BOYS</span></span>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-white">
                Cadastro de Usuário (Cliente)
            </h2>
            <p class="mt-2 text-center text-sm text-zinc-400">
                Já tem uma conta? <a href="{{ route('login') }}" class="font-medium text-primary hover:brightness-125 transition-colors cursor-pointer">Faça login</a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
            <div class="bg-zinc-900/50 backdrop-blur-xl py-8 px-4 shadow-2xl sm:rounded-xl sm:px-10 border border-zinc-800">

            {{-- Mensagem ECA Digital --}}
            <div class="mb-6 p-4 bg-primary/10 border border-primary/20 rounded-lg">
                <p class="text-sm text-zinc-300">
                    <strong class="text-primary">Atenção:</strong> Esta é uma conta de usuário do tipo cliente. Em cumprimento à Lei (ECA Digital) nº 15.211/2025, o fornecimento do seu CPF e Data de Nascimento é obrigatório para garantir a verificação de maioridade e restringir o acesso de menores a conteúdos +18, condição indispensável para a liberação sem restrições dos perfis. Se você deseja anunciar seus serviços como acompanhante, utilize o <a href="#" class="text-primary font-bold hover:underline">Cadastro de Acompanhante</a>.
                </p>
            </div>

            @if($errors->any())
                <x-alerts.alert type="error" :message="$errors->first()" />
            @endif

            <form class="space-y-5" action="{{ route('registro') }}" method="POST">
                @csrf

                <x-forms.input
                    name="nome"
                    type="text"
                    label="Nome"
                    placeholder="Seu nome completo"
                    :value="old('nome')"
                    autocomplete="name"
                    required
                    icon="<path d='M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2'/><circle cx='12' cy='7' r='4'/>"
                />

                <x-forms.input
                    name="email"
                    type="email"
                    label="Email"
                    placeholder="seu@email.com"
                    :value="old('email')"
                    autocomplete="email"
                    required
                    icon="<path d='M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z'/><polyline points='22,6 12,13 2,6'/>"
                />

                <x-forms.input
                    name="whatsapp"
                    type="tel"
                    label="WhatsApp (Opcional)"
                    placeholder="(00) 00000-0000"
                    :value="old('whatsapp')"
                    autocomplete="tel"
                    id="whatsapp"
                    icon="<path d='M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z'/>"
                />

                <x-forms.input 
                    name="senha" 
                    type="password" 
                    label="Senha" 
                    placeholder="Sua senha" 
                    autocomplete="new-password" 
                    required
                    icon="<rect x='3' y='11' width='18' height='11' rx='2' ry='2'/><path d='M7 11V7a5 5 0 0 1 10 0v4'/>" 
                />

                <x-forms.input 
                    name="senha_confirmation" 
                    type="password" 
                    label="Confirmar Senha" 
                    placeholder="Repita sua senha" 
                    autocomplete="new-password" 
                    required
                    icon="<rect x='3' y='11' width='18' height='11' rx='2' ry='2'/><path d='M7 11V7a5 5 0 0 1 10 0v4'/>" 
                />

                <x-forms.input
                    name="cpf"
                    type="text"
                    label="CPF"
                    placeholder="000.000.000-00"
                    :value="old('cpf')"
                    maxlength="14"
                    id="cpf"
                    required
                    icon="<rect x='2' y='2' width='20' height='20' rx='2'/><path d='M7 12h2v2H7z'/><path d='M11 12h2v2h-2z'/><path d='M15 12h2v2h-2z'/>"
                />

                <x-forms.input
                    name="data_nascimento"
                    type="text"
                    label="Data de Nascimento"
                    placeholder="dd/mm/aaaa"
                    :value="old('data_nascimento')"
                    id="data_nascimento"
                    required
                    icon="<rect x='3' y='4' width='18' height='18' rx='2' ry='2'/><line x1='16' y1='2' x2='16' y2='6'/><line x1='8' y1='2' x2='8' y2='6'/><line x1='3' y1='10' x2='21' y2='10'/>"
                />

                <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 rounded-lg text-sm font-bold text-primary-foreground bg-primary hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-black transition-all duration-200 uppercase tracking-wider cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="8.5" cy="7" r="4"/>
                        <line x1="20" y1="8" x2="20" y2="14"/>
                        <line x1="23" y1="11" x2="17" y2="11"/>
                    </svg>
                    Cadastrar
                </button>
            </form>

            <p class="mt-6 text-xs text-zinc-500 text-center">
                Ao criar uma conta, você concorda com nossos
                <a href="{{ route('termos') }}" class="text-primary hover:brightness-125 cursor-pointer">Termos de Serviço</a>
                e <a href="{{ route('privacidade') }}" class="text-primary hover:brightness-125 cursor-pointer">Política de Privacidade</a>
            </p>
        </div>
    </div>
    </x-ui.container>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara CPF
    const cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = value;
        });
    }

    // Máscara Data de Nascimento
    const dataInput = document.getElementById('data_nascimento');
    if (dataInput) {
        dataInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 8) value = value.slice(0, 8);
            value = value.replace(/(\d{2})(\d)/, '$1/$2');
            value = value.replace(/(\d{2})(\d)/, '$1/$2');
            e.target.value = value;
        });
    }

    // Máscara WhatsApp
    const whatsappInput = document.getElementById('whatsapp');
    if (whatsappInput) {
        whatsappInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });
    }
});
</script>
@endsection
