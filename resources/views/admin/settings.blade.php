@extends('admin.layout')

@section('title', 'Configuracoes - Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-heading-3 font-heading text-white">Configuracoes</h1>
            <p class="text-zinc-400 text-sm mt-1">Gerencie as configuracoes gerais do sistema</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-xl px-6 py-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-zinc-900 rounded-2xl p-8">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                <label for="notification_emails" class="block text-white text-sm font-medium">
                    Emails para notificacao
                </label>
                <p class="text-zinc-500 text-xs">Emails que receberao notificacoes de novos comentarios e assinaturas. Separe por virgula.</p>
                <input 
                    type="text" 
                    name="notification_emails" 
                    id="notification_emails"
                    value="{{ old('notification_emails', $notificationEmails) }}"
                    placeholder="admin@email.com, master@planetaboy.com.br"
                    class="w-full bg-zinc-800 border border-zinc-700 rounded-xl px-4 py-3 text-white placeholder-zinc-500 focus:outline-none focus:border-primary transition-colors"
                >
                @error('notification_emails')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-8 py-3 bg-primary hover:bg-primary/90 text-black font-bold tracking-wider rounded-xl transition-all cursor-pointer">
                    Salvar Configuracoes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
