<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminCityController extends Controller
{
    public function index()
    {
        $cities = City::orderBy('order')->get();
        return view('admin.cities', compact('cities'));
    }

    public function create()
    {
        return view('admin.cities-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state' => 'required|string|max:2',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'order' => 'nullable|integer',
        ]);

        $baseSlug = Str::slug($request->name . '-' . $request->state);
        $slug = $baseSlug;
        $counter = 1;
        while (City::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('cities', 'public');
        }

        City::create([
            'name' => $request->name,
            'state' => strtoupper($request->state),
            'slug' => $slug,
            'image' => $imagePath,
            'active' => $request->has('active'),
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('admin.cities')->with('success', 'Cidade criada com sucesso!');
    }

    public function edit(City $city)
    {
        return view('admin.cities-edit', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state' => 'required|string|max:2',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'order' => 'nullable|integer',
        ]);

        $baseSlug = Str::slug($request->name . '-' . $request->state);
        $slug = $baseSlug;
        $counter = 1;
        while (City::where('slug', $slug)->where('id', '!=', $city->id)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        if ($request->hasFile('image')) {
            if ($city->image) {
                Storage::disk('public')->delete($city->image);
            }
            $city->image = $request->file('image')->store('cities', 'public');
        }

        $city->update([
            'name' => $request->name,
            'state' => strtoupper($request->state),
            'slug' => $slug,
            'active' => $request->has('active'),
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('admin.cities')->with('success', 'Cidade atualizada com sucesso!');
    }

    public function destroy(City $city)
    {
        if ($city->image) {
            Storage::disk('public')->delete($city->image);
        }

        $city->delete();

        return redirect()->route('admin.cities')->with('success', 'Cidade deletada com sucesso!');
    }
}
