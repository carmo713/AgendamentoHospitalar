<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agendar Consulta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('agendamentos.store') }}">
                        @csrf

                        <!-- Médico -->
                        <div class="mb-4">
                            <x-input-label for="medico_id" :value="__('Médico')" />
                            <select id="medico_id" name="medico_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Selecione um médico</option>
                                @foreach($medicos as $medico)
                                    <option value="{{ $medico->id }}">Dr(a). {{ $medico->user->name }} - {{ $medico->especialidade }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Data -->
                        <div class="mb-4">
                            <x-input-label for="data" :value="__('Data')" />
                            <x-text-input id="data" class="block mt-1 w-full" type="date" name="data" :value="old('data')" required />
                        </div>

                        <!-- Hora -->
                        <div class="mb-4">
                            <x-input-label for="hora" :value="__('Hora')" />
                            <x-text-input id="hora" class="block mt-1 w-full" type="time" name="hora" :value="old('hora')" required />
                        </div>

                        <!-- Descrição -->
                        <div class="mb-4">
                            <x-input-label for="descricao" :value="__('Descrição do problema')" />
                            <textarea id="descricao" name="descricao" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('descricao') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('agendamentos.meus') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cancelar
                            </a>
                            <x-primary-button class="ml-3">
                                {{ __('Agendar Consulta') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>