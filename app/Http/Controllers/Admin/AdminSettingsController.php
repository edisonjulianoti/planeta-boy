<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSettingsController extends Controller
{
    public function index(): View
    {
        $notificationEmails = Setting::getValue('notification_emails', 'master@planetaboy.com.br');
        return view('admin.settings', compact('notificationEmails'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'notification_emails' => 'nullable|string|max:1000',
        ]);

        Setting::setValue('notification_emails', $request->input('notification_emails', ''));

        return redirect()->route('admin.settings')
            ->with('success', 'Configuracoes atualizadas com sucesso!');
    }
}
