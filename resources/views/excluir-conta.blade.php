@extends('layouts.app')

@section('title', 'Excluir Conta - PLANETA BOYS')

@section('content')
<div class="min-h-screen bg-black flex flex-col justify-center py-12">
    <div class="container mx-auto px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <span class="text-2xl font-black uppercase italic tracking-tight text-white"><span class="text-primary">PLANETA</span><span class="text-foreground">BOYS</span></span>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-white">
                Excluir sua Conta
            </h2>
            <p class="mt-2 text-center text-sm text-zinc-400">
                Esta ação é irreversível. Leia atentamente antes de prosseguir.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-zinc-900/50 backdrop-blur-xl py-8 px-4 shadow-2xl sm:rounded-xl sm:px-10 border border-zinc-800">

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-600/20 border border-green-600/30 rounded-lg">
                        <p class="text-green-400 text-sm">{{ session('success') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-600/20 border border-red-600/30 rounded-lg">
                        <p class="text-red-400 text-sm">{{ $errors->first() }}</p>
                    </div>
                @endif

                <div class="mb-6 p-4 bg-red-600/10 border border-red-600/30 rounded-lg">
                    <p class="text-red-400 text-sm font-bold uppercase tracking-wider mb-2">⚠ Atenção</p>
                    <ul class="text-zinc-300 text-sm space-y-2">
                        <li>• Seu perfil de usuário será excluído permanentemente.</li>
                        <li>• Seu perfil de acompanhante (se houver) será removido.</li>
                        <li>• Seus comentários e avaliações serão anonimizados.</li>
                        <li>• Fotos e vídeos enviados serão removidos.</li>
                        <li>• Esta ação <strong>não pode ser desfeita</strong>.</li>
                    </ul>
                </div>

                <form action="{{ route('conta.excluir') }}" method="POST" class="space-y-5" onsubmit="return confirm('Tem certeza absoluta? Esta ação é irreversível.');">
                    @csrf
                    @method('DELETE')

                    <div>
                        <label for="password" class="block text-xs sm:text-sm font-medium text-zinc-300 mb-1">
                            Confirme sua senha para excluir <span class="text-red-400">*</span>
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            placeholder="Sua senha atual"
                            class="block w-full px-4 py-2.5 bg-zinc-800 border border-zinc-700 focus:border-primary rounded-lg text-white placeholder-zinc-500 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                        >
                    </div>

                    <label class="flex items-start gap-3 p-4 bg-zinc-800/50 rounded-lg cursor-pointer">
                        <input type="checkbox" name="consent_delete" required
                               class="mt-0.5 h-4 w-4 rounded border-zinc-700 bg-zinc-800 text-primary focus:ring-primary">
                        <span class="text-zinc-300 text-xs leading-relaxed">
                            Eu compreendo que esta ação é <strong>irreversível</strong> e desejo excluir permanentemente minha conta e todos os dados associados.
                        </span>
                    </label>

                    <button type="submit"
                            class="w-full flex justify-center py-2.5 px-4 rounded-lg text-sm font-bold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-black transition-all duration-200 uppercase tracking-wider cursor-pointer">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                        Excluir Minha Conta
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('perfil') }}" class="text-zinc-500 hover:text-primary text-sm transition-colors cursor-pointer">
                        Voltar ao meu perfil
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
