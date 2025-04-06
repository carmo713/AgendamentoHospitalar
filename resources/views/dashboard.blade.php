<!-- filepath: /home/carmo/Documentos/agendamento/agendamento/resources/views/dashboard.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Você está logado!") }}
                    
                    @php
                        $user = auth()->user();
                        $isDoctor = $user->doctor()->exists();
                        $isPatient = $user->patient()->exists();
                    @endphp

                    @if($isDoctor)
                        <div class="mt-4">
                            <a href="{{ route('medico.agendamentos') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Ver Meus Agendamentos
                            </a>
                        </div>
                    @endif

                    @if($isPatient)
                        <div class="mt-4 space-x-4">
                            <a href="{{ route('agendamentos.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Agendar Consulta
                            </a>
                            <a href="{{ route('agendamentos.meus') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Meus Agendamentos
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>