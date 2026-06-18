@extends('layouts.app')

@section('title', 'Verificação de Perfil - PLANETA BOYS')

@section('content')
<div class="min-h-screen bg-zinc-950">
    <div class="container mx-auto px-4 py-12 max-w-2xl">

        <div class="mb-8">
            <a href="{{ route('perfil') }}" class="text-zinc-400 hover:text-white text-sm font-bold uppercase tracking-wider flex items-center gap-2 mb-4 cursor-pointer">
                ← Voltar ao Meu Perfil
            </a>
            <h1 class="text-3xl font-black text-white uppercase italic">Verificação de <span class="text-primary">Perfil</span></h1>
            <p class="text-zinc-400 text-sm mt-2">Envie documentos para verificar sua identidade e ganhar o selo de confiança.</p>
        </div>

        {{-- Status atual --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-white font-black uppercase tracking-wider text-sm flex items-center gap-2">
                    <div class="w-1 h-4 bg-primary rounded-full"></div>Status da Verificação
                </h2>
            </div>

            @php
                $status = $perfil->verification_status;
                $statusConfig = match($status) {
                    'approved' => ['label' => 'Aprovado', 'color' => 'text-green-400', 'bg' => 'bg-green-500/10', 'border' => 'border-green-500/30'],
                    'pending' => ['label' => 'Pendente', 'color' => 'text-yellow-400', 'bg' => 'bg-yellow-500/10', 'border' => 'border-yellow-500/30'],
                    'rejected' => ['label' => 'Rejeitado', 'color' => 'text-red-400', 'bg' => 'bg-red-500/10', 'border' => 'border-red-500/30'],
                    default => ['label' => 'Não solicitado', 'color' => 'text-zinc-400', 'bg' => 'bg-zinc-800', 'border' => 'border-zinc-700'],
                };
            @endphp

            <div class="p-4 rounded-xl border {{ $statusConfig['border'] }} {{ $statusConfig['bg'] }}">
                <div class="flex items-center gap-3">
                    @if($status === 'approved')
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    @elseif($status === 'pending')
                        <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><polyline points="12 6 12 12 16 14"/></svg>
                    @elseif($status === 'rejected')
                        <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    @else
                        <svg class="w-8 h-8 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    @endif
                    <div>
                        <p class="text-white font-bold text-lg {{ $statusConfig['color'] }}">{{ $statusConfig['label'] }}</p>
                        @if($status === 'rejected')
                            <p class="text-zinc-400 text-sm mt-1">Corrija o problema e envie novos documentos.</p>
                        @elseif($status === 'pending')
                            <p class="text-zinc-400 text-sm mt-1">Sua solicitação está sendo analisada pela nossa equipe.</p>
                        @elseif($status === 'none')
                            <p class="text-zinc-400 text-sm mt-1">Envie seus documentos para solicitar a verificação.</p>
                        @elseif($status === 'approved')
                            <p class="text-zinc-400 text-sm mt-1">Sua identidade foi verificada com sucesso!</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Alertas --}}
        @if(session('success'))
            <x-alerts.alert type="success" :message="session('success')" />
        @endif
        @if(session('error'))
            <x-alerts.alert type="error" :message="session('error')" />
        @endif
        @if($errors->any())
            <x-alerts.alert type="error" :message="$errors->first()" />
        @endif

        {{-- Formulário de upload (só se pode solicitar) --}}
        @if($perfil->canRequestVerification())
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 mb-6">
            <h2 class="text-white font-black uppercase tracking-wider text-sm flex items-center gap-2 mb-4">
                <div class="w-1 h-4 bg-primary rounded-full"></div>Enviar Documento
            </h2>

            <form method="POST" action="{{ route('perfil.verificacao.upload') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-zinc-400 text-xs uppercase tracking-wider font-bold mb-2">Tipo de Documento</label>
                    <select name="document_type" required
                        class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-primary">
                        <option value="">Selecione o tipo</option>
                        <option value="rg">RG (Registro Geral)</option>
                        <option value="cnh">CNH (Carteira de Habilitação)</option>
                        <option value="selfie">Selfie segurando o documento</option>
                        <option value="other">Outro documento oficial</option>
                    </select>
                </div>

                <div>
                    <label class="block text-zinc-400 text-xs uppercase tracking-wider font-bold mb-2">Arquivo (JPG, PNG ou PDF — máx. 10MB)</label>
                    <input type="file" name="document" required accept=".jpg,.jpeg,.png,.pdf"
                        class="w-full text-white file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-primary file:text-black file:cursor-pointer hover:file:brightness-110 file:transition-all bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2">
                </div>

                <div class="bg-zinc-800/50 rounded-xl p-4 border border-zinc-700 text-xs text-zinc-400 leading-relaxed">
                    <p class="font-bold text-zinc-300 mb-1">📌 Instruções:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Envie uma foto <strong>nítida</strong> do documento (frente e verso se necessário)</li>
                        <li>A selfie deve mostrar seu <strong>rosto ao lado do documento</strong></li>
                        <li>Os dados do documento devem estar <strong>legíveis</strong></li>
                        <li>Seus dados serão protegidos conforme a LGPD</li>
                    </ul>
                </div>

                <button type="submit"
                    class="w-full py-3 bg-primary hover:brightness-110 text-black font-bold rounded-lg transition-all uppercase tracking-wider cursor-pointer">
                    Enviar para Verificação
                </button>
            </form>
        </div>
        @endif

        {{-- Documentos enviados --}}
        @if($documents->isNotEmpty())
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
            <h2 class="text-white font-black uppercase tracking-wider text-sm flex items-center gap-2 mb-4">
                <div class="w-1 h-4 bg-primary rounded-full"></div>Documentos Enviados
            </h2>

            <div class="space-y-3">
                @foreach($documents as $doc)
                <div class="flex items-center justify-between p-3 bg-zinc-800 rounded-xl border border-zinc-700">
                    <div class="flex items-center gap-3">
                        <span class="text-zinc-400 text-xs uppercase tracking-wider font-bold">{{ $doc->getDocumentTypeLabel() }}</span>
                        @php
                            $docStatus = match($doc->status) {
                                'approved' => ['label' => 'Aprovado', 'class' => 'text-green-400 bg-green-500/10 border-green-500/30'],
                                'rejected' => ['label' => 'Rejeitado', 'class' => 'text-red-400 bg-red-500/10 border-red-500/30'],
                                default => ['label' => 'Pendente', 'class' => 'text-yellow-400 bg-yellow-500/10 border-yellow-500/30'],
                            };
                        @endphp
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full border {{ $docStatus['class'] }}">
                            {{ $docStatus['label'] }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-zinc-500 text-xs">{{ $doc->submitted_at->format('d/m/Y H:i') }}</span>
                        @if($doc->isPending())
                        <form method="POST" action="{{ route('perfil.verificacao.documento.destroy', $doc) }}" onsubmit="return confirm('Remover este documento?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-bold cursor-pointer">Remover</button>
                        </form>
                        @endif
                    </div>
                </div>
                @if($doc->isRejected() && $doc->rejection_reason)
                <div class="ml-3 text-xs text-red-400 bg-red-500/5 border border-red-500/20 rounded-lg p-3">
                    <strong>Motivo da rejeição:</strong> {{ $doc->rejection_reason }}
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- Selos e requisitos --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 mt-6">
            <h2 class="text-white font-black uppercase tracking-wider text-sm flex items-center gap-2 mb-4">
                <div class="w-1 h-4 bg-primary rounded-full"></div>Requisitos para o Selo Verificado
            </h2>

            <div class="space-y-3">
                <div class="flex items-center gap-3 text-sm">
                    @if(auth()->user()->hasVerifiedEmail())
                        <svg class="w-5 h-5 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    @else
                        <svg class="w-5 h-5 text-zinc-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    @endif
                    <span class="{{ auth()->user()->hasVerifiedEmail() ? 'text-green-400' : 'text-zinc-500' }}">E-mail verificado</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    @if($perfil->documents_verified)
                        <svg class="w-5 h-5 text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    @elseif($perfil->hasPendingVerification())
                        <svg class="w-5 h-5 text-yellow-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><polyline points="12 6 12 12 16 14"/></svg>
                    @else
                        <svg class="w-5 h-5 text-zinc-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                    @endif
                    <span class="{{ $perfil->documents_verified ? 'text-green-400' : ($perfil->hasPendingVerification() ? 'text-yellow-400' : 'text-zinc-500') }}">Documentos verificados</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <svg class="w-5 h-5 text-zinc-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                    <span class="text-zinc-500">Plano ativo</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
