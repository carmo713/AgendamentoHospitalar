<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use Illuminate\Http\Request;

class MedicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicos = Medico::all();
        return view('medicos.index', compact('medicos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('medicos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'especialidade' => 'required|string|max:255',
            'email' => 'required|email|unique:medicos,email',
        ]);

        Medico::create($request->all());

        return redirect()->route('medicos.index')->with('success', 'Médico criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Medico $medico)
    {
        return view('medicos.show', compact('medico'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medico $medico)
    {
        return view('medicos.edit', compact('medico'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medico $medico)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'especialidade' => 'required|string|max:255',
            'email' => 'required|email|unique:medicos,email,' . $medico->id,
        ]);

        $medico->update($request->all());

        return redirect()->route('medicos.index')->with('success', 'Médico atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medico $medico)
    {
        $medico->delete();

        return redirect()->route('medicos.index')->with('success', 'Médico deletado com sucesso.');
    }

    public function agendamentos()
{
    $medico = Medico::where('user_id', auth()->id())->first();
    $agendamentos = Agendamento::where('medico_id', $medico->id)
                            ->orderBy('data', 'asc')
                            ->orderBy('hora', 'asc')
                            ->get();
    return view('medico.agendamentos', compact('agendamentos'));
}
}