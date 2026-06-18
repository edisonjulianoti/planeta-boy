<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\ProfileComment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'total_users'    => User::count(),
            'total_admins'   => User::where('is_admin', true)->count(),
            'total_profiles' => Profile::count(),
            'active_profiles' => Profile::where('active', true)->count(),
            'verified_profiles' => Profile::where('verified', true)->count(),
            'premium_users'  => User::where('plan', 'premium')->count(),
            'blocked_users'  => User::where('blocked', true)->count(),
        ];

        $recent_users = User::latest()->limit(5)->get();
        $recent_profiles = Profile::with('user')->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_profiles'));
    }

    public function users(Request $request): View
    {
        $query = User::query();

        // Busca por nome ou email
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filtro por plano
        if ($request->plan) {
            $query->where('plan', $request->plan);
        }

        // Filtro por admin
        if ($request->is_admin !== null) {
            $query->where('is_admin', $request->is_admin);
        }

        // Filtro por bloqueio
        if ($request->blocked !== null) {
            $query->where('blocked', $request->blocked);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function toggleAdmin(User $user): RedirectResponse
    {
        if ($error = $this->ensureNotLastAdmin($user, 'remover')) {
            return $error;
        }

        $user->update(['is_admin' => !$user->is_admin]);

        return back()->with('success', $user->is_admin ? 'Usuário promovido a administrador.' : 'Administrador rebaixado a usuário comum.');
    }

    public function toggleBlocked(User $user): RedirectResponse
    {
        if ($error = $this->ensureNotSelf($user, 'bloquear')) {
            return $error;
        }

        if ($error = $this->ensureNotLastAdmin($user, 'bloquear')) {
            return $error;
        }

        $user->update(['blocked' => !$user->blocked]);

        return back()->with('success', $user->blocked ? 'Usuário bloqueado.' : 'Usuário desbloqueado.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($error = $this->ensureNotSelf($user, 'deletar')) {
            return $error;
        }

        if ($error = $this->ensureNotLastAdmin($user, 'deletar')) {
            return $error;
        }

        $user->delete();

        return back()->with('success', 'Usuário deletado com sucesso.');
    }

    public function show(User $user): View
    {
        $user->load('profile', 'subscriptionRequests');
        return view('admin.users-show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('admin.users-edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'bio' => $request->bio,
        ]);

        return redirect()->route('admin.users')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function profiles(): View
    {
        $profiles = Profile::with('user')->latest()->paginate(20);

        return view('admin.profiles', compact('profiles'));
    }

    public function editProfile(Profile $profile): View
    {
        if ($error = $this->ensureNotAdminProfile($profile)) {
            return $error;
        }

        return view('admin.profiles-edit', compact('profile'));
    }

    public function updateProfile(Request $request, Profile $profile): RedirectResponse
    {
        if ($error = $this->ensureNotAdminProfile($profile)) {
            return $error;
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'age'         => 'required|integer|min:18|max:100',
            'city'        => 'required|string|max:255',
            'state'       => 'required|string|max:2',
            'description' => 'nullable|string|max:2000',
            'verified'    => 'boolean',
            'active'      => 'boolean',
            'height'      => 'nullable|integer|min:100|max:250',
            'weight'      => 'nullable|integer|min:30|max:300',
            'hair_color'  => 'nullable|string|max:100',
            'eye_color'   => 'nullable|string|max:100',
            'ethnicity'   => 'nullable|string|max:100',
            'body_type'   => 'nullable|string|max:100',
        ]);

        $profile->update([
            'name'        => $request->name,
            'age'         => $request->age,
            'city'        => $request->city,
            'state'       => $request->state,
            'description' => $request->description,
            'verified'    => $request->boolean('verified'),
            'verified_manually' => $request->boolean('verified'),
            'active'      => $request->boolean('active'),
        ]);

        // Sync physical attributes
        $attributeData = $request->only(['height', 'weight', 'hair_color', 'eye_color', 'ethnicity', 'body_type']);
        $hasAnyValue = false;
        foreach ($attributeData as $value) {
            if ($value !== null && $value !== '') {
                $hasAnyValue = true;
                break;
            }
        }

        if ($hasAnyValue) {
            $filtered = array_filter($attributeData, fn($v) => $v !== null && $v !== '');
            if ($profile->physicalAttributes) {
                $profile->physicalAttributes()->update($filtered);
            } else {
                $profile->physicalAttributes()->create($filtered);
            }
        } elseif ($profile->physicalAttributes) {
            $profile->physicalAttributes()->delete();
        }

        return redirect()->route('admin.profiles')->with('success', 'Perfil atualizado com sucesso.');
    }

    private function ensureNotLastAdmin(User $user, string $action): ?RedirectResponse
    {
        if ($user->is_admin && User::where('is_admin', true)->count() === 1) {
            return back()->with('error', "Não é possível {$action} o último administrador.");
        }
        return null;
    }

    private function ensureNotSelf(User $user, string $action): ?RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', "Não é possível {$action} a si mesmo.");
        }
        return null;
    }

    private function ensureNotAdminProfile(Profile $profile): ?RedirectResponse
    {
        if ($profile->user->is_admin) {
            return back()->with('error', 'Não é possível editar perfis de administradores.');
        }
        return null;
    }

    // ─── Comentários ───────────────────────────────────────

    public function comments(Request $request): View
    {
        $query = ProfileComment::with(['profile', 'user']);

        // Filtro por status
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('approved', false);
            }
        }

        // Filtro por perfil
        if ($request->filled('profile_id')) {
            $query->where('profile_id', $request->profile_id);
        }

        $comments = $query->latest()->paginate(20)->withQueryString();
        $profiles = Profile::select('id', 'name')->where('active', true)->orderBy('name')->get();

        return view('admin.comentarios', compact('comments', 'profiles'));
    }

    public function approveComment(ProfileComment $comment): RedirectResponse
    {
        $comment->update(['approved' => true]);

        return back()->with('success', 'Comentário aprovado com sucesso.');
    }

    public function deleteComment(ProfileComment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('success', 'Comentário excluído com sucesso.');
    }
}
