<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(): View
    {
        $plans = Plan::orderBy('price')->get();

        return view('admin.plans', compact('plans'));
    }

    public function edit(Plan $plan): View
    {
        return view('admin.plans-edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'features'    => 'nullable|string',
            'active'      => 'boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_image' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($plan->image) {
                Storage::disk('public')->delete($plan->image);
            }

            // Store in plans/{plan_id}/ directory
            $plan->image = $request->file('image')->store('plans/' . $plan->id, 'public');
        }

        // Handle image removal (checkbox)
        if ($request->boolean('remove_image') && $plan->image) {
            Storage::disk('public')->delete($plan->image);
            $plan->image = null;
        }

        $features = collect(explode("\n", $request->features ?? ''))
            ->map(fn($f) => trim($f))
            ->filter()
            ->values()
            ->all();

        $plan->update([
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'features'    => $features,
            'active'      => $request->boolean('active'),
            'image'       => $plan->image,
        ]);

        return redirect()->route('admin.plans')
            ->with('success', 'Plano atualizado com sucesso.');
    }
}
