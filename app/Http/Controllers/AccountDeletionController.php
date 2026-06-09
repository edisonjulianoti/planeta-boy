<?php

namespace App\Http\Controllers;

use App\Models\ProfileComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountDeletionController extends Controller
{
    public function form()
    {
        return view('excluir-conta');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = auth()->user();

        // Verificar senha
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'A senha informada está incorreta.']);
        }

        // Anonimizar comentários feitos por este usuário
        ProfileComment::where('user_id', $user->id)->update([
            'user_id' => null,
            'comment' => '[comentário removido]',
        ]);

        // Remover perfil de acompanhante se existir
        if ($user->profile) {
            $profile = $user->profile;

            // Remover arquivos de imagem
            foreach ($profile->images as $image) {
                Storage::disk('public')->delete($image->url);
            }
            $profile->images()->delete();
            $profile->videos()->delete();
            $profile->physicalAttributes()->delete();
            $profile->pricing()->delete();
            $profile->availability()->delete();
            $profile->services()->detach();
            $profile->comments()->delete();
            $profile->reports()->delete();
            $profile->delete();
        }

        // Remover solicitações de assinatura
        $user->subscriptionRequests()->delete();

        // Forçar logout antes de deletar
        auth()->logout();

        // Salvar email para mensagem de confirmação (já que o user será deletado)
        $user->delete();

        // Invalidar sessão
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Sua conta foi excluída permanentemente.');
    }
}
