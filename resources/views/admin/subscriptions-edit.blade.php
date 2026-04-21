@extends('admin.layout')

@section('title', 'Editar Assinatura')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.subscriptions') }}" class="text-zinc-400 hover:text-white text-sm font-bold uppercase tracking-wider flex items-center gap-2 cursor-pointer">
            ← Voltar para assinaturas
        </a>
    </div>

    <x-admin.card title="Editar Assinatura" padding="p-8">

        <form action="{{ route('admin.subscriptions.update', $subscription) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Informações do usuário --}}
            <div class="bg-zinc-800/50 rounded-xl p-4 border border-zinc-700">
                <p class="text-zinc-400 text-xs uppercase tracking-wider font-bold mb-2">Usuário</p>
                <p class="text-white font-bold">{{ $subscription->user->name }}</p>
                <p class="text-zinc-500 text-sm">{{ $subscription->user->email }}</p>
            </div>

            {{-- Status --}}
            <x-forms.input type="select" name="status" label="Status">
                <option value="pending" {{ $subscription->status === 'pending' ? 'selected' : '' }}>Pendente</option>
                <option value="approved" {{ $subscription->status === 'approved' ? 'selected' : '' }}>Aprovado</option>
                <option value="rejected" {{ $subscription->status === 'rejected' ? 'selected' : '' }}>Rejeitado</option>
            </x-forms.input>

            {{-- Plano --}}
            <div>
                <label class="block text-zinc-400 text-xs uppercase tracking-wider font-bold mb-2">Plano</label>
                <x-forms.input type="select" name="plan_slug">
                    @foreach($plans as $plan)
                        <option value="{{ $plan->slug }}" {{ $subscription->plan_slug === $plan->slug ? 'selected' : '' }}>
                            {{ $plan->name }} - R$ {{ number_format($plan->price, 2, ',', '.') }}
                        </option>
                    @endforeach
                </x-forms.input>
            </div>

            {{-- Data de expiração --}}
            <x-forms.input type="date" name="expires_at" label="Válido até" :value="$subscription->expires_at ? \Carbon\Carbon::parse($subscription->expires_at)->format('Y-m-d') : ''" :min="\Carbon\Carbon::tomorrow()->format('Y-m-d')" />
            <p class="text-zinc-500 text-xs mt-1">Deixe vazio para plano free</p>

            {{-- Observações --}}
            <div>
                <label class="block text-zinc-400 text-xs uppercase tracking-wider font-bold mb-2">Observações do administrador</label>
                <textarea name="admin_notes" rows="3"
                    class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary resize-none"
                    placeholder="Adicione observações sobre esta assinatura...">{{ $subscription->admin_notes ?? '' }}</textarea>
            </div>

            {{-- Botões --}}
            <div class="flex gap-4 pt-4">
                <a href="{{ route('admin.subscriptions') }}"
                    class="px-6 py-3 bg-zinc-800 hover:bg-zinc-700 text-white font-bold uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-primary hover:bg-primary/80 text-white font-bold uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </x-admin.card>
</div>
@endsection
