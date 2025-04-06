<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Paciente;
use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agendamentos = Agendamento::all();
        return view('agendamentos.index', compact('agendamentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $medicos = Medico::all();
        return view('agendamentos.create', compact('medicos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'medico_id' => 'required|exists:medicos,id',
            'data' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'descricao' => 'nullable|string',
        ]);

        $paciente = Paciente::where('user_id', auth()->id())->first();
        
        // Verificar se o paciente existe
        if (!$paciente) {
            return redirect()->back()->with('error', 'Perfil de paciente não encontrado.');
        }

        Agendamento::create([
            'medico_id' => $request->medico_id,
            'paciente_id' => $paciente->id,
            'data' => $request->data,
            'hora' => $request->hora,
            'descricao' => $request->descricao,
        ]);

        return redirect()->route('agendamentos.meus')->with('success', 'Agendamento realizado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agendamento $agendamento)
    {
        // Verificar se o usuário é o dono do agendamento ou o médico associado
        $this->verificarPermissao($agendamento);
        
        return view('agendamentos.show', compact('agendamento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agendamento $agendamento)
    {
        // Verificar se o usuário é o dono do agendamento
        $this->verificarPermissao($agendamento);
        
        $medicos = Medico::all();
        return view('agendamentos.edit', compact('agendamento', 'medicos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agendamento $agendamento)
    {
        // Verificar se o usuário é o dono do agendamento
        $this->verificarPermissao($agendamento);
        
        $request->validate([
            'medico_id' => 'required|exists:medicos,id',
            'data' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
            'descricao' => 'nullable|string',
        ]);

        $agendamento->update($request->all());

        // Redirecionar para a rota apropriada dependendo do usuário
        $paciente = Auth::user()->patient()->first();
        if ($paciente) {
            return redirect()->route('agendamentos.meus')->with('success', 'Agendamento atualizado com sucesso.');
        } else {
            return redirect()->route('medico.agendamentos')->with('success', 'Agendamento atualizado com sucesso.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agendamento $agendamento)
    {
        // Verificar se o usuário é o dono do agendamento
        $this->verificarPermissao($agendamento);
        
        $agendamento->delete();

        // Redirecionar para a rota apropriada dependendo do usuário
        $paciente = Auth::user()->patient()->first();
        if ($paciente) {
            return redirect()->route('agendamentos.meus')->with('success', 'Agendamento cancelado com sucesso.');
        } else {
            return redirect()->route('medico.agendamentos')->with('success', 'Agendamento cancelado com sucesso.');
        }
    }

    /**
     * Display the logged-in user's appointments (for patients).
     */
    public function meusAgendamentos()
    {
        $paciente = Paciente::where('user_id', auth()->id())->first();
        
        // Verificar se o paciente existe
        if (!$paciente) {
            return redirect()->route('dashboard')->with('error', 'Perfil de paciente não encontrado.');
        }
        
        $agendamentos = Agendamento::where('paciente_id', $paciente->id)
                                ->orderBy('data', 'asc')
                                ->orderBy('hora', 'asc')
                                ->get();
                                
        return view('agendamentos.meus', compact('agendamentos'));
    }
    
    /**
     * Display the logged-in doctor's appointments (for doctors).
     */
    public function agendamentosMedico()
    {
        $medico = Medico::where('user_id', auth()->id())->first();
        
        // Verificar se o médico existe
        if (!$medico) {
            return redirect()->route('dashboard')->with('error', 'Perfil de médico não encontrado.');
        }
        
        $agendamentos = Agendamento::where('medico_id', $medico->id)
                                ->orderBy('data', 'asc')
                                ->orderBy('hora', 'asc')
                                ->get();
                                
        return view('medico.agendamentos', compact('agendamentos'));
    }

    /**
     * Verifica se o usuário tem permissão para acessar o agendamento.
     */
    private function verificarPermissao(Agendamento $agendamento)
    {
        $user = Auth::user();
        $paciente = $user->patient()->first();
        $medico = $user->doctor()->first();
        
        $isPacienteDoAgendamento = $paciente && $agendamento->paciente_id == $paciente->id;
        $isMedicoDoAgendamento = $medico && $agendamento->medico_id == $medico->id;
        
        if (!$isPacienteDoAgendamento && !$isMedicoDoAgendamento) {
            abort(403, 'Você não tem permissão para acessar este agendamento.');
        }
    }
}