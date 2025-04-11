<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\HorarioController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::resource('medicos', MedicoController::class);
    Route::resource('pacientes', PacienteController::class);
    

    Route::get('agendamentos/criar', [AgendamentoController::class, 'create'])->name('agendamentos.create');
    Route::post('agendamentos', [AgendamentoController::class, 'store'])->name('agendamentos.store');
    Route::get('meus-agendamentos', [AgendamentoController::class, 'meusAgendamentos'])->name('agendamentos.meus');
    Route::get('medico/agendamentos', [AgendamentoController::class, 'agendamentosMedico'])->name('medico.agendamentos');
    

    Route::resource('agendamentos', AgendamentoController::class)->except(['create', 'store']);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/horarios', [HorarioController::class, 'index'])->name('horarios.index');


// Rotas para médicos
Route::middleware(['auth'])->prefix('medico')->group(function () {
    Route::get('/', [MedicoController::class, 'index'])->name('medico.index');
    Route::get('/disponibilidade', [MedicoController::class, 'disponibilidade'])->name('medico.disponibilidade');
    Route::post('/disponibilidade', [MedicoController::class, 'storeDisponibilidade'])->name('medico.disponibilidade.store');
    Route::delete('/disponibilidade/{horario}', [MedicoController::class, 'destroyDisponibilidade'])->name('medico.disponibilidade.destroy');
    Route::get('/agendamentos', [MedicoController::class, 'agendamentos'])->name('medico.agendamentos');
    Route::get('/perfil/edit', [MedicoController::class, 'editPerfil'])->name('medico.perfil.edit');
    Route::put('/perfil', [MedicoController::class, 'updatePerfil'])->name('medico.perfil.update');
});

// API Routes
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/medico/horarios', [MedicoController::class, 'getHorarios'])->name('api.medico.horarios');
    Route::get('/medico/agendamentos', [MedicoController::class, 'getAgendamentos'])->name('api.medico.agendamentos');
    Route::get('/horarios-disponiveis', [AgendamentoController::class, 'getHorariosDisponiveis'])->name('api.horarios-disponiveis');
});

require __DIR__.'/auth.php';