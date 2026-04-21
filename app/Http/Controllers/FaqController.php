<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::where('categoria', 'geral')
                   ->where('ativo', true)
                   ->orderBy('created_at', 'desc')
                   ->get();
        
        return view('faq', compact('faqs'));
    }
}
