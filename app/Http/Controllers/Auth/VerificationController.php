<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
    public function notice(): \Illuminate\View\View
    {
        return view('auth.verify-notice');
    }

    public function verify(Request $request, $id, $hash): RedirectResponse
    {
        if (!URL::hasValidSignature($request)) {
            return redirect()->route('login')
                ->with('error', 'Link de verificacao invalido ou expirado.');
        }

        $user = \App\Models\User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')
                ->with('error', 'Link de verificacao invalido.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('perfil')
                ->with('status', 'E-mail ja verificado!');
        }

        $user->markEmailAsVerified();

        return redirect()->route('perfil')
            ->with('status', 'E-mail verificado com sucesso!');
    }

    public function resend(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('perfil');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Link de verificacao reenviado!');
    }
}
