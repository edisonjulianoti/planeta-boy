@extends('layouts.app')

@section('title', 'Política de Privacidade - PLANETA BOYS')

@section('content')
<div class="min-h-screen bg-black">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-white mb-8">Política de Privacidade</h1>
            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-8 space-y-8">
                @foreach([
                    ['titulo' => '1. Informações Coletadas', 'texto' => 'Coletamos informações que você nos fornece diretamente, como nome, e-mail, e informações de perfil quando cria uma conta em nossa plataforma.'],
                    ['titulo' => '2. Uso das Informações', 'texto' => 'Utilizamos suas informações para fornecer, manter e melhorar nossos serviços, processar pagamentos e comunicar sobre sua conta.'],
                    ['titulo' => '3. Compartilhamento', 'texto' => 'Não vendemos ou compartilhamos suas informações pessoais com terceiros, exceto quando necessário para operar nossa plataforma ou conforme exigido por lei.'],
                    ['titulo' => '4. Segurança', 'texto' => 'Implementamos medidas de segurança apropriadas para proteger suas informações contra acesso não autorizado ou perda.'],
                    ['titulo' => '5. Seus Direitos', 'texto' => 'Você tem direito a acessar, corrigir ou excluir suas informações pessoais a qualquer momento.'],
                ] as $secao)
                <section>
                    <h2 class="text-2xl font-semibold text-white mb-4">{{ $secao['titulo'] }}</h2>
                    <p class="text-zinc-400 leading-relaxed">{{ $secao['texto'] }}</p>
                </section>
                @endforeach
                <div class="pt-8 border-t border-zinc-800">
                    <p class="text-zinc-500 text-sm">Última atualização: {{ now()->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </x-ui.container>
</div>
@endsection
