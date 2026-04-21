<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Profile;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ExplorarController extends Controller
{
    /** Ordenação por prioridade de plano: premium > gold > silver > free */
    private const PLAN_PRIORITY_ORDER = "CASE 
        WHEN user_plan = 'premium' THEN 1 
        WHEN user_plan = 'gold' THEN 2 
        WHEN user_plan = 'silver' THEN 3 
        ELSE 4 
    END";

    public function index(Request $request): View|JsonResponse
    {
        $query = Profile::with(['images', 'user', 'services'])
            ->where('active', true)
            ->leftJoin('users', 'profiles.user_id', '=', 'users.id')
            ->select('profiles.*', 'users.plan as user_plan');

        // Filtro de cidade (texto)
        if ($request->filled('cidade')) {
            $cidade = $request->input('cidade');
            $query->where('city', 'like', '%' . $cidade . '%');
        }

        // Filtro de serviços (vírgula separada ou array)
        if ($request->filled('servicos')) {
            $servicos = $request->input('servicos');
            // Verifica se é array (do formulário) ou string (do JavaScript)
            if (is_array($servicos)) {
                $serviceIds = array_filter(array_map('intval', $servicos));
            } else {
                $serviceIds = array_filter(array_map('intval', explode(',', $servicos)));
            }
            if (!empty($serviceIds)) {
                $query->whereHas('services', function ($q) use ($serviceIds) {
                    $q->whereIn('services.id', $serviceIds);
                });
            }
        }

        // Filtro de idade (texto - formato "18-25" ou similar)
        if ($request->filled('idade')) {
            $idade = $request->input('idade');
            if (preg_match('/(\d+)\s*[-–]\s*(\d+)/', $idade, $matches)) {
                $idadeMin = (int) $matches[1];
                $idadeMax = (int) $matches[2];
                $query->whereBetween('age', [$idadeMin, $idadeMax]);
            } elseif (preg_match('/^\d+$/', $idade)) {
                // Se for apenas um número, busca exata
                $query->where('age', (int) $idade);
            }
        }

        // Filtro de verificado
        if ($request->boolean('verificado')) {
            $query->where('verified', true);
        }

        // Filtro de características (array)
        if ($request->filled('caracteristicas')) {
            $caracteristicas = $request->input('caracteristicas');
            if (is_array($caracteristicas) && !empty($caracteristicas)) {
                // Mapeamento de características para campos do banco
                $caracteristicaMap = [
                    'loiro' => 'hair_color',
                    'moreno' => 'hair_color',
                    'ruivo' => 'hair_color',
                    'oriental' => 'ethnicity',
                ];

                foreach ($caracteristicas as $caracteristica) {
                    if (isset($caracteristicaMap[$caracteristica])) {
                        $campo = $caracteristicaMap[$caracteristica];
                        $valor = ucfirst($caracteristica);
                        $query->where($campo, $valor);
                    }
                }
            }
        }

        // Filtro de avaliação mínima
        if ($request->filled('avaliacao_min')) {
            $avaliacaoMin = (int) $request->input('avaliacao_min');
            if ($avaliacaoMin > 0) {
                $query->where('rating', '>=', $avaliacaoMin);
            }
        }

        // Ordenação padrão (sem filtro de ordem)
        $query->orderByRaw(self::PLAN_PRIORITY_ORDER)->latest();

        $perfis = $query->paginate(16);

        if ($request->wantsJson() || $request->has('scroll')) {
            \Log::info('Scroll infinito AJAX request', [
                'wantsJson' => $request->wantsJson(),
                'hasScroll' => $request->has('scroll'),
                'nextPageUrl' => $perfis->nextPageUrl(),
                'hasMorePages' => $perfis->hasMorePages(),
            ]);
            return response()->json([
                'html' => view('components.perfil-card', compact('perfis'))->render(),
                'next_page_url' => $perfis->nextPageUrl(),
                'has_more_pages' => $perfis->hasMorePages(),
            ]);
        }

        // Carregar dados para filtros apenas na requisição inicial
        $cidades = City::where('active', true)->orderBy('order')->get();
        $servicos = Service::where('active', true)->orderBy('name')->get();
        $estados = $cidades->pluck('state')->unique()->sort()->values();

        return view('explorar', compact('perfis', 'cidades', 'servicos', 'estados'));
    }
}
