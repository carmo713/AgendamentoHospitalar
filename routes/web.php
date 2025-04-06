<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\AgendamentoController;

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

require __DIR__.'/auth.php';