<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('category')->orderBy('name')->get();
        return view('admin.services', compact('services'));
    }

    public function create()
    {
        return view('admin.services-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'active' => 'nullable|boolean',
        ]);

        $slug = Str::slug($request->name);

        Service::create([
            'name' => $request->name,
            'slug' => $slug,
            'category' => $request->category,
            'active' => $request->has('active'),
        ]);

        return redirect()->route('admin.services')->with('success', 'Serviço criado com sucesso!');
    }

    public function edit(Service $service)
    {
        return view('admin.services-edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'active' => 'nullable|boolean',
        ]);

        $slug = Str::slug($request->name);

        $service->update([
            'name' => $request->name,
            'slug' => $slug,
            'category' => $request->category,
            'active' => $request->has('active'),
        ]);

        return redirect()->route('admin.services')->with('success', 'Serviço atualizado com sucesso!');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services')->with('success', 'Serviço deletado com sucesso!');
    }
}
