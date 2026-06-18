<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\ProfileVerificationDocument;
use App\Notifications\VerificationNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PerfilVerificacaoController extends Controller
{
    public function index(): View
    {
        $perfil = auth()->user()->profile;

        if (!$perfil) {
            abort(404);
        }

        $documents = $perfil->verificationDocuments()->latest()->get();

        return view('perfil.verificacao', compact('perfil', 'documents'));
    }

    public function upload(Request $request): RedirectResponse
    {
        $perfil = auth()->user()->profile;

        if (!$perfil) {
            return back()->with('error', 'Você precisa criar um perfil primeiro.');
        }

        if (!$perfil->canRequestVerification()) {
            return back()->with('error', 'Já existe uma solicitação de verificação em andamento.');
        }

        $validated = $request->validate([
            'document_type' => 'required|in:rg,cnh,selfie,other',
            'document'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        $file = $request->file('document');
        $path = $file->store("verificacao/{$perfil->id}", 'public');

        ProfileVerificationDocument::create([
            'profile_id'    => $perfil->id,
            'document_type' => $validated['document_type'],
            'file_path'     => $path,
            'status'        => 'pending',
            'submitted_at'  => now(),
        ]);

        $perfil->update(['verification_status' => 'pending']);

        return redirect()->route('perfil.verificacao')
            ->with('success', 'Documento enviado! Sua solicitação de verificação está pendente de análise.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $perfil = auth()->user()->profile;

        if (!$perfil) {
            return back()->with('error', 'Perfil não encontrado.');
        }

        $document = $perfil->verificationDocuments()->findOrFail($id);

        if ($document->status === 'approved') {
            return back()->with('error', 'Não é possível remover um documento já aprovado.');
        }

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        // Se não houver mais docs pendentes, volta verification_status
        if ($perfil->pendingVerificationDocuments()->count() === 0) {
            $perfil->update(['verification_status' => 'none']);
        }

        return redirect()->route('perfil.verificacao')
            ->with('success', 'Documento removido.');
    }
}
