<!-- filepath: /home/carmo/Documentos/AgendamentoHospitalar-main/resources/views/horarios/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Horários Disponíveis</h2>
    
    <form action="{{ route('horarios.index') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="especialidade_id">Especialidade</label>
                    <select name="especialidade_id" id="especialidade_id" class="form-control">
                        <option value="">Todas as especialidades</option>
                        @foreach($especialidades as $especialidade)
                            <option value="{{ $especialidade->id }}" {{ request('especialidade_id') == $especialidade->id ? 'selected' : '' }}>
                                {{ $especialidade->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label for="medico_id">Médico</label>
                    <select name="medico_id" id="medico_id" class="form-control">
                        <option value="">Todos os médicos</option>
                        @foreach($medicos as $medico)
                            <option value="{{ $medico->id }}" {{ request('medico_id') == $medico->id ? 'selected' : '' }}>
                                {{ $medico->user->name }} - CRM: {{ $medico->crm }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label for="unidade_id">Unidade</label>
                    <select name="unidade_id" id="unidade_id" class="form-control">
                        <option value="">Todas as unidades</option>
                        @foreach($unidades as $unidade)
                            <option value="{{ $unidade->id }}" {{ request('unidade_id') == $unidade->id ? 'selected' : '' }}>
                                {{ $unidade->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
    </form>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Data/Hora</th>
                    <th>Médico</th>
                    <th>Especialidade</th>
                    <th>Unidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($horarios as $horario)
                <tr>
                    <td>{{ $horario->inicio->format('d/m/Y H:i') }} - {{ $horario->fim->format('H:i') }}</td>
                    <td>{{ $horario->medico->user->name }}</td>
                    <td>{{ $horario->medico->especialidade->nome }}</td>
                    <td>{{ $horario->medico->unidade->nome ?? 'Não definida' }}</td>
                    <td>
                        <a href="{{ route('agendamentos.create', ['horario_id' => $horario->id]) }}" class="btn btn-sm btn-success">
                            Agendar
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Nenhum horário disponível com os filtros selecionados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection