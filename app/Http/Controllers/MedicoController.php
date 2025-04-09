<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medico;
use App\Models\Horario;
use App\Models\Agendamento;
use Carbon\Carbon;

class MedicoController extends Controller
{
    public function index()
    {
        $medico = auth()->user()->medico;
        return view('medico.index', compact('medico'));
    }
    
    public function disponibilidade()
    {
        $medico = auth()->user()->medico;
        return view('medico.disponibilidade', compact('medico'));
    }
    
    public function storeDisponibilidade(Request $request)
    {
        $request->validate([
            'data' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required',
            'hora_fim' => 'required|after:hora_inicio',
            'recorrente' => 'nullable',
            'semanas' => 'nullable|integer|min:1|max:12',
        ]);
        
        $medico = auth()->user()->medico;
        $dataInicio = Carbon::parse($request->data . ' ' . $request->hora_inicio);
        $dataFim = Carbon::parse($request->data . ' ' . $request->hora_fim);
        
        // Se é recorrente, criar múltiplos horários
        if ($request->has('recorrente')) {
            $semanas = $request->semanas ?? 4;
            $horarios = [];
            
            for ($i = 0; $i < $semanas; $i++) {
                $inicio = $dataInicio->copy()->addWeeks($i);
                $fim = $dataFim->copy()->addWeeks($i);
                
                $horarios[] = [
                    'medico_id' => $medico->id,
                    'inicio' => $inicio,
                    'fim' => $fim,
                    'disponivel' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            Horario::insert($horarios);
        } else {
            // Criar um único horário
            Horario::create([
                'medico_id' => $medico->id,
                'inicio' => $dataInicio,
                'fim' => $dataFim,
                'disponivel' => true,
            ]);
        }
        
        return redirect()->back()->with('success', 'Horário(s) adicionado(s) com sucesso!');
    }
    
    public function destroyDisponibilidade(Horario $horario)
    {
        // Verificar se o horário pertence ao médico logado
        if ($horario->medico_id !== auth()->user()->medico->id) {
            return response()->json(['success' => false], 403);
        }
        
        // Verificar se o horário está disponível
        if (!$horario->disponivel) {
            return response()->json(['success' => false, 'message' => 'Este horário já está agendado'], 400);
        }
        
        $horario->delete();
        return response()->json(['success' => true]);
    }
    
    public function getHorarios()
    {
        $medico = auth()->user()->medico;
        
        // Buscar todos os horários (disponíveis e agendados) para o médico
        $horarios = Horario::where('medico_id', $medico->id)
                      ->where('inicio', '>=', now()->subDays(30))
                      ->get();
        
        $eventos = $horarios->map(function($horario) {
            return [
                'id' => $horario->id,
                'title' => $horario->disponivel ? 'Disponível' : 'Agendado',
                'start' => $horario->inicio->format('Y-m-d\TH:i:s'),
                'end' => $horario->fim->format('Y-m-d\TH:i:s'),
                'disponivel' => $horario->disponivel,
                'paciente' => $horario->disponivel ? null : ($horario->agendamento->paciente->nome ?? 'Paciente')
            ];
        });
        
        return response()->json($eventos);
    }
    
    public function getAgendamentos()
    {
        $medico = auth()->user()->medico;
        
        $agendamentos = Agendamento::whereHas('horario', function($query) use ($medico) {
                              $query->where('medico_id', $medico->id);
                          })
                          ->with(['horario', 'paciente.user'])
                          ->where('status', 'confirmado')
                          ->whereHas('horario', function($query) {
                              $query->where('inicio', '>=', now());
                          })
                          ->get();
        
        $eventos = $agendamentos->map(function($agendamento) {
            return [
                'id' => $agendamento->id,
                'title' => $agendamento->paciente->user->name,
                'start' => $agendamento->horario->inicio->format('Y-m-d\TH:i:s'),
                'end' => $agendamento->horario->fim->format('Y-m-d\TH:i:s'),
                'backgroundColor' => '#3788d8',
                'borderColor' => '#3788d8',
            ];
        });
        
        return response()->json($eventos);
    }
    
    public function agendamentos()
    {
        $medico = auth()->user()->medico;
        
        $agendamentos = Agendamento::whereHas('horario', function($query) use ($medico) {
                              $query->where('medico_id', $medico->id);
                          })
                          ->with(['horario', 'paciente.user'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);
        
        return view('medico.agendamentos', compact('agendamentos'));
    }
    
    public function editPerfil()
    {
        $medico = auth()->user()->medico;
        return view('medico.edit-perfil', compact('medico'));
    }
    
    public function updatePerfil(Request $request)
    {
        $medico = auth()->user()->medico;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:medicos,email,'.$medico->id,
            'crm' => 'required|string|max:20|unique:medicos,crm,'.$medico->id,
        ]);
        
        $user = auth()->user();
        $user->name = $request->name;
        $user->save();
        
        $medico->email = $request->email;
        $medico->crm = $request->crm;
        $medico->save();
        
        return redirect()->route('medico.index')->with('success', 'Perfil atualizado com sucesso!');
    }
}