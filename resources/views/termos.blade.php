@extends('layouts.app')

@section('title', 'Termos de Uso - PLANETA BOYS')

@section('content')
<div class="min-h-screen bg-black">
    <x-ui.container size="lg" class="py-16">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-white mb-8">Termos de Uso</h1>
            <div class="bg-zinc-900 border border-zinc-800 rounded-lg p-8 space-y-8">
                @foreach([
                    ['titulo' => '1. Aceitação dos Termos', 'texto' => 'Ao acessar e usar a plataforma PLANETA BOYS, você concorda com estes termos de uso.'],
                    ['titulo' => '2. Idade Mínima', 'texto' => 'Esta plataforma é destinada exclusivamente a maiores de 18 anos. Ao usar nossos serviços, você confirma ter idade legal para acessar conteúdo adulto.'],
                    ['titulo' => '3. Conteúdo e Conduta', 'texto' => 'Você é responsável pelo conteúdo que publica. Proibimos conteúdo ilegal, discriminatório, ou que viole direitos de terceiros.'],
                    ['titulo' => '4. Pagamentos e Assinaturas', 'texto' => 'Os planos de assinatura são cobrados mensalmente. Você pode cancelar a qualquer momento. Não oferecemos reembolsos parciais para períodos já pagos.'],
                    ['titulo' => '5. Privacidade', 'texto' => 'Sua privacidade é importante. Consulte nossa Política de Privacidade para entender como coletamos e usamos suas informações.'],
                    ['titulo' => '6. Propriedade Intelectual', 'texto' => 'O conteúdo da plataforma, incluindo design, texto e imagens, é protegido por direitos autorais e não pode ser reproduzido sem autorização.'],
                    ['titulo' => '7. Limitação de Responsabilidade', 'texto' => 'A PLANETA BOYS não se responsabiliza por danos diretos ou indiretos resultantes do uso da plataforma.'],
                    ['titulo' => '8. Alterações nos Termos', 'texto' => 'Reservamo-nos o direito de alterar estes termos a qualquer momento. Usuários serão notificados sobre mudanças significativas.'],
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
