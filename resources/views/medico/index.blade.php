@extends('layouts.app')

@section('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
@endsection

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Painel do Médico</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('medico.disponibilidade') }}" class="btn btn-primary">Gerenciar Disponibilidade</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Informações Pessoais</div>
                <div class="card-body">
                    <h5>Dr(a). {{ auth()->user()->name }}</h5>
                    <p><strong>Especialidade:</strong> {{ $medico->especialidade->nome }}</p>
                    <p><strong>CRM:</strong> {{ $medico->crm }}</p>
                    <p><strong>Email:</strong> {{ $medico->email }}</p>
                    <a href="{{ route('medico.perfil.edit') }}" class="btn btn-sm btn-secondary">Editar Perfil</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Meus Próximos Atendimentos</span>
                        <a href="{{ route('medico.agendamentos') }}" class="btn btn-sm btn-link">Ver Todos</a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/pt-br.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            locale: 'pt-br',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            slotMinTime: '08:00:00',
            slotMaxTime: '19:00:00',
            events: '{{ route("api.medico.agendamentos") }}',
            eventClick: function(info) {
                window.location.href = `/agendamentos/${info.event.id}`;
            }
        });
        calendar.render();
    }
});
</script>
@endsection