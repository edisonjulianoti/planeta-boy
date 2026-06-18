<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use App\Models\Setting;
use App\Notifications\NewSubscriptionNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class PlanoController extends Controller
{
    public function index(): View
    {
        $this->ensureNotAdmin();

        $plans = Plan::where('active', true)->orderBy('price')->get();
        $faqs = Faq::where('categoria', 'planos')->where('ativo', true)->orderBy('created_at', 'desc')->get();

        return view('planos', compact('plans', 'faqs'));
    }

    public function contratar(Request $request): RedirectResponse
    {
        $this->ensureNotAdmin();

        $request->validate([
            'plan_slug' => 'required|string|exists:plans,slug',
        ]);

        $user = auth()->user();

        if ($user->hasPlan($request->plan_slug)) {
            return back()->with('error', 'Você já está neste plano.');
        }

        if ($user->hasPendingSubscriptionRequest()) {
            return back()->with('error', 'Você já possui uma solicitação pendente. Aguarde a aprovação.');
        }

        SubscriptionRequest::create([
            'user_id'   => $user->id,
            'plan_slug' => $request->plan_slug,
            'status'    => 'pending',
        ]);

        // Notify admin emails about new subscription request
        $notifyEmails = Setting::getValue('notification_emails', '');
        if ($notifyEmails) {
            $emails = array_map('trim', explode(',', $notifyEmails));
            foreach ($emails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Notification::route('mail', $email)
                        ->notify(new NewSubscriptionNotification($user, $request->plan_slug));
                }
            }
        }

        return redirect()->route('meu.plano')
            ->with('success', 'Solicitação enviada! Aguarde a aprovação do administrador.');
    }

    public function meuPlano(): View
    {
        $this->ensureNotAdmin();

        $user = auth()->user();
        $plans = Plan::where('active', true)->orderBy('price')->get();
        $requests = $user->subscriptionRequests()->latest()->get();
        $subscriptions = $user->subscriptions()->with(['plan', 'histories'])->latest('start_date')->get();

        return view('meu-plano', compact('user', 'plans', 'requests', 'subscriptions'));
    }

    public function cancelar(Request $request): RedirectResponse
    {
        $this->ensureNotAdmin();

        $user = auth()->user();

        if ($user->hasPlan('free')) {
            return back()->with('error', 'Você já está no plano gratuito.');
        }

        $user->update([
            'plan'            => 'free',
            'plan_expires_at' => null,
        ]);

        return back()->with('success', 'Assinatura cancelada. Seu plano foi revertido para Gratuito.');
    }

    private function ensureNotAdmin(): void
    {
        if (auth()->user()?->isAdmin()) {
            abort(403, 'Administradores não precisam de planos.');
        }
    }
}
