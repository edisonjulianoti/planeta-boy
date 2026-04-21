<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\SubscriptionRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(): View
    {
        $pending = SubscriptionRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $history = SubscriptionRequest::with('user')
            ->whereIn('status', ['approved', 'rejected'])
            ->latest()
            ->paginate(20);

        return view('admin.subscriptions', compact('pending', 'history'));
    }

    public function approve(Request $request, SubscriptionRequest $subscription): RedirectResponse
    {
        $request->validate([
            'expires_at'   => 'required|date|after:today',
            'admin_notes'  => 'nullable|string|max:500',
        ]);

        $subscription->update([
            'status'      => 'approved',
            'expires_at'  => $request->expires_at,
            'admin_notes' => $request->admin_notes,
        ]);

        $subscription->user->update([
            'plan'            => $subscription->plan_slug,
            'plan_expires_at' => $request->expires_at,
        ]);

        return back()->with('success', "Solicitação de {$subscription->user->name} aprovada.");
    }

    public function reject(Request $request, SubscriptionRequest $subscription): RedirectResponse
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $subscription->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', "Solicitação de {$subscription->user->name} rejeitada.");
    }

    public function updateUserPlan(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'plan'            => 'required|string|exists:plans,slug',
            'plan_expires_at' => 'nullable|date',
        ]);

        $user->update([
            'plan'            => $request->plan,
            'plan_expires_at' => $request->plan === 'free' ? null : $request->plan_expires_at,
        ]);

        return back()->with('success', "Plano de {$user->name} atualizado.");
    }

    public function edit(SubscriptionRequest $subscription): View
    {
        $plans = Plan::all();
        return view('admin.subscriptions-edit', compact('subscription', 'plans'));
    }

    public function update(Request $request, SubscriptionRequest $subscription): RedirectResponse
    {
        $request->validate([
            'status'      => 'required|in:pending,approved,rejected',
            'plan_slug'   => 'required|string|exists:plans,slug',
            'expires_at'  => 'nullable|date',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $subscription->status;
        $newStatus = $request->status;

        $subscription->update([
            'status'      => $newStatus,
            'plan_slug'   => $request->plan_slug,
            'expires_at'  => $request->expires_at,
            'admin_notes' => $request->admin_notes,
        ]);

        // Se mudou para approved, atualizar plano do usuário
        if ($newStatus === 'approved' && $oldStatus !== 'approved') {
            $subscription->user->update([
                'plan'            => $request->plan_slug,
                'plan_expires_at' => $request->expires_at,
            ]);
        }

        return redirect()->route('admin.subscriptions')->with('success', 'Assinatura atualizada com sucesso.');
    }
}
