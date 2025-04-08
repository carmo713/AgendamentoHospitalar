<?php

namespace App\Http\Controllers;

use App\Models\Especialidade;
use App\Models\Medico;
use App\Models\Unidade;
use App\Models\Horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function index(Request $request)
    {
        // Dados para os filtros
        $especialidades = Especialidade::all();
        $medicos = Medico::all();
        $unidades = Unidade::all();
        
        // Construir a query base
        $query = Horario::where('disponivel', true)
                         ->where('inicio', '>', now())
                         ->with(['medico', 'medico.especialidade', 'medico.unidade']);
        
        // Aplicar filtros se fornecidos
        if ($request->has('especialidade_id')) {
            $query->whereHas('medico', function($q) use ($request) {
                $q->where('especialidade_id', $request->especialidade_id);
            });
        }
        
        if ($request->has('medico_id')) {
            $query->where('medico_id', $request->medico_id);
        }
        
        if ($request->has('unidade_id')) {
            $query->whereHas('medico', function($q) use ($request) {
                $q->where('unidade_id', $request->unidade_id);
            });
        }
        
        // Ordenar e buscar resultados
        $horarios = $query->orderBy('inicio')->get();
        
        return view('horarios.index', compact(
            'horarios', 
            'especialidades', 
            'medicos', 
            'unidades'
        ));
    }
}