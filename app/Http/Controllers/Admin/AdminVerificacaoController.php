<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\ProfileVerificationDocument;
use App\Notifications\VerificationNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminVerificacaoController extends Controller
{
    public function index(Request $request): View
    {
        $query = ProfileVerificationDocument::with(['profile.user']);

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'pending');
        }

        $documents = $query->latest('submitted_at')->paginate(20);

        $stats = [
            'pending'  => ProfileVerificationDocument::where('status', 'pending')->count(),
            'approved' => ProfileVerificationDocument::where('status', 'approved')->count(),
            'rejected' => ProfileVerificationDocument::where('status', 'rejected')->count(),
        ];

        return view('admin.verificacoes', compact('documents', 'stats'));
    }

    public function show(int $id): View
    {
        $document = ProfileVerificationDocument::with(['profile.user', 'reviewer'])->findOrFail($id);

        return view('admin.verificacao-revisar', compact('document'));
    }

    public function approve(Request $request, int $id): RedirectResponse
    {
        $document = ProfileVerificationDocument::with('profile')->findOrFail($id);

        if (!$document->isPending()) {
            return back()->with('error', 'Este documento já foi processado.');
        }

        $document->update([
            'status'      => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $profile = $document->profile;

        // Se todos os documentos estão aprovados, marca o perfil
        $pendingDocs = $profile->verificationDocuments()->where('status', 'pending')->count();
        if ($pendingDocs === 0) {
            $profile->update([
                'verification_status'   => 'approved',
                'documents_verified'    => true,
                // Só concede verified se outros requisitos também OK
            ]);
        }

        $profile->user->notify(new VerificationNotification('approved', $profile));

        return redirect()->route('admin.verificacoes')
            ->with('success', "Documento de {$profile->name} aprovado!");
    }

    public function reject(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:2000',
        ]);

        $document = ProfileVerificationDocument::with('profile')->findOrFail($id);

        if (!$document->isPending()) {
            return back()->with('error', 'Este documento já foi processado.');
        }

        $document->update([
            'status'           => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'reviewed_by'      => auth()->id(),
            'reviewed_at'      => now(),
        ]);

        $profile = $document->profile;
        $profile->update(['verification_status' => 'rejected']);

        $profile->user->notify(new VerificationNotification('rejected', $profile, $validated['rejection_reason']));

        return redirect()->route('admin.verificacoes')
            ->with('success', "Documento de {$profile->name} rejeitado.");
    }

    public function showPhoto(int $id)
    {
        $document = ProfileVerificationDocument::findOrFail($id);

        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($document->file_path));
    }

    public function profiles(): View
    {
        $profiles = Profile::whereIn('verification_status', ['pending', 'approved', 'rejected'])
            ->with('user', 'verificationDocuments')
            ->latest()
            ->paginate(20);

        return view('admin.verificacao-perfis', compact('profiles'));
    }
}
