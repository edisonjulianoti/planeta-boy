<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriberCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSubscriberCategoryController extends Controller
{
    public function index()
    {
        $categories = SubscriberCategory::all();
        return view('admin.subscriber-categories', compact('categories'));
    }

    public function create()
    {
        return view('admin.subscriber-categories-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        SubscriberCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'active' => $request->has('active'),
        ]);

        return redirect()->route('admin.subscriber-categories')->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(SubscriberCategory $category)
    {
        return view('admin.subscriber-categories-edit', compact('category'));
    }

    public function update(Request $request, SubscriberCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'active' => $request->has('active'),
        ]);

        return redirect()->route('admin.subscriber-categories')->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(SubscriberCategory $category)
    {
        $category->delete();

        return redirect()->route('admin.subscriber-categories')->with('success', 'Categoria deletada com sucesso!');
    }
}
