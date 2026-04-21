@extends('admin.layout')

@section('title', 'Editar Usuário')

@section('content')

@if(session('success'))
    <x-alerts.alert type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-alerts.alert type="error" :message="session('error')" />
@endif

<x-admin.card title="Editar Usuário" padding="p-8" class="max-w-2xl">

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <x-forms.input name="name" label="Nome" :value="$user->name" required />

            <x-forms.input name="email" type="email" label="E-mail" :value="$user->email" required />

            <x-forms.input name="phone" label="Telefone" :value="$user->phone" />

            <div>
                <label class="text-zinc-400 text-xs uppercase tracking-widest font-bold block mb-2">Bio</label>
                <textarea name="bio" rows="4"
                    class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary resize-none">{{ $user->bio }}</textarea>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="px-6 py-3 bg-primary hover:brightness-110 text-primary-foreground font-black uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                    Salvar
                </button>
                <a href="{{ route('admin.users') }}" class="px-6 py-3 bg-zinc-800 hover:bg-zinc-700 text-white font-black uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                    Cancelar
                </a>
            </div>
        </div>
    </form>
</x-admin.card>

@endsection
