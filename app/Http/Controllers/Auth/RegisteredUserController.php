<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Especialidade;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:medico,paciente'],
            'especialidade' => ['nullable', 'string', 'max:255', 'required_if:role,medico'],
            'crm' => ['nullable', 'string', 'max:255', 'required_if:role,medico'],
            'cpf' => ['nullable', 'string', 'max:255', 'required_if:role,paciente'],
            'telefone' => ['nullable', 'string', 'max:15', 'required_if:role,paciente'],
            'data_nascimento' => ['nullable', 'date', 'required_if:role,paciente'],
            'endereco' => ['nullable', 'string', 'max:255', 'required_if:role,paciente'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->role === 'medico') {
                $especialidade = Especialidade::where('nome', $request->especialidade)->first();
            
            if (!$especialidade) {
                $especialidade = Especialidade::create([
                    'nome' => $request->especialidade
                ]);
            }
            
            Medico::create([
                'user_id' => $user->id,
                'especialidade_id' => $especialidade->id, // Modificado: agora usando o ID da especialidade
                'email' => $request->email,
                'crm' => $request->crm,
            ]);
        } elseif ($request->role === 'paciente') {
            Paciente::create([
                'user_id' => $user->id,
                'nome' => $request->name,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'data_nascimento' => $request->data_nascimento,
                'endereco' => $request->endereco,
                'cpf' => $request->cpf,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard'));
    }
}