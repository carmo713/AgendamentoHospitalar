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

                    <form method="POST" action="{{ route('agendamentos.store') }}" id="agendamento-form">
                        @csrf

                        <!-- Médico -->
                        <div class="mb-4">
                            <x-input-label for="medico_id" :value="__('Médico')" />
                            <select id="medico_id" name="medico_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Selecione um médico</option>
                                @foreach($medicos as $medico)
                                    <option value="{{ $medico->id }}" {{ old('medico_id') == $medico->id ? 'selected' : '' }}>
                                        Dr(a). {{ $medico->user->name }} - {{ $medico->especialidade->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Data -->
                        <div class="mb-4">
                            <x-input-label for="data" :value="__('Data')" />
                            <x-text-input id="data" class="block mt-1 w-full" type="date" name="data" :value="old('data')" min="{{ date('Y-m-d') }}" required />
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
            
            <!-- Horários Disponíveis -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">Horários Disponíveis</h3>
                    
                    <!-- Filtros para horários -->
                    <div class="mb-4">
                        <form action="{{ route('agendamentos.create') }}" method="GET" class="flex flex-wrap gap-3">
                            <div>
                                <x-input-label for="filter_especialidade" :value="__('Especialidade')" />
                                <select name="especialidade_id" id="filter_especialidade" class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Todas</option>
                                    @foreach($especialidades as $especialidade)
                                        <option value="{{ $especialidade->id }}" {{ request('especialidade_id') == $especialidade->id ? 'selected' : '' }}>
                                            {{ $especialidade->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <x-input-label for="filter_unidade" :value="__('Unidade')" />
                                <select name="unidade_id" id="filter_unidade" class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Todas</option>
                                    @foreach($unidades as $unidade)
                                        <option value="{{ $unidade->id }}" {{ request('unidade_id') == $unidade->id ? 'selected' : '' }}>
                                            {{ $unidade->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="self-end">
                                <x-primary-button type="submit" class="h-10">
                                    {{ __('Filtrar') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Tabela de horários -->
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data/Hora</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médico</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialidade</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unidade</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ação</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($horarios as $horario)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($horario->inicio)->format('d/m/Y H:i') }} - 
                                        {{ \Carbon\Carbon::parse($horario->fim)->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Dr(a). {{ $horario->medico->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $horario->medico->especialidade->nome }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $horario->medico->unidade->nome ?? 'Não definida' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button type="button" 
                                                onclick="selecionarHorario('{{ $horario->medico->id }}', '{{ \Carbon\Carbon::parse($horario->inicio)->format('Y-m-d') }}', '{{ \Carbon\Carbon::parse($horario->inicio)->format('H:i') }}')" 
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                            Selecionar
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Nenhum horário disponível para os filtros selecionados.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginação -->
                    @if(method_exists($horarios, 'hasPages') && $horarios->hasPages())
                    <div class="mt-4">
                        {{ $horarios->appends(request()->query())->links() }}
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function selecionarHorario(medicoId, data, hora) {
            document.getElementById('medico_id').value = medicoId;
            document.getElementById('data').value = data;
            document.getElementById('hora').value = hora;
            
            // Scroll até o formulário
            document.querySelector('#agendamento-form').scrollIntoView({ behavior: 'smooth' });
        }
        
        // Validação da data
        document.addEventListener('DOMContentLoaded', function() {
            const dataInput = document.getElementById('data');
            const horaInput = document.getElementById('hora');
            
            dataInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate < today) {
                    alert('Não é possível agendar consultas para datas passadas.');
                    this.value = '';
                }
            });
        });
    </script>
</x-app-layout>