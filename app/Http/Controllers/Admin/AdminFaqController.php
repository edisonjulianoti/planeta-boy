<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class AdminFaqController extends Controller
{
    public function index(Request $request)
    {
        $categoria = $request->query('categoria', 'todas');
        
        $query = Faq::query();
        
        if ($categoria !== 'todas') {
            $query->where('categoria', $categoria);
        }
        
        $faqs = $query->orderBy('created_at', 'desc')->get();
        
        return view('admin.faqs', compact('faqs', 'categoria'));
    }

    public function create()
    {
        return view('admin.faqs-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pergunta' => 'required|string|max:255',
            'resposta' => 'required|string',
            'categoria' => 'required|in:geral,planos',
            'ativo' => 'boolean',
        ]);

        Faq::create([
            'pergunta' => $request->pergunta,
            'resposta' => $request->resposta,
            'categoria' => $request->categoria,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('admin.faqs')->with('success', 'FAQ criada com sucesso!');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs-edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'pergunta' => 'required|string|max:255',
            'resposta' => 'required|string',
            'categoria' => 'required|in:geral,planos',
            'ativo' => 'boolean',
        ]);

        $faq->update([
            'pergunta' => $request->pergunta,
            'resposta' => $request->resposta,
            'categoria' => $request->categoria,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('admin.faqs')->with('success', 'FAQ atualizada com sucesso!');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs')->with('success', 'FAQ deletada com sucesso!');
    }
}
