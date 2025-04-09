@extends('layouts.app')

@section('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
.fc-event {
    cursor: pointer;
}
.disponivel {
    background-color: #10b981 !important;
    border-color: #10b981 !important;
}
.indisponivel {
    background-color: #ef4444 !important;
    border-color: #ef4444 !important;
}
</style>
@endsection

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3">Gerenciar Disponibilidade</h1>
            <p>Selecione no calendário para adicionar novos horários disponíveis.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">Adicionar Disponibilidade</div>
                <div class="card-body">
                    <form id="disponibilidade-form" method="POST" action="{{ route('medico.disponibilidade.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="data" class="form-label">Data</label>
                            <input type="date" id="data" name="data" class="form-control" required min="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="hora_inicio" class="form-label">Hora Início</label>
                            <input type="time" id="hora_inicio" name="hora_inicio" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="hora_fim" class="form-label">Hora Fim</label>
                            <input type="time" id="hora_fim" name="hora_fim" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="recorrente" name="recorrente">
                                <label class="form-check-label" for="recorrente">
                                    Repetir semanalmente
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3 recorrente-options" style="display: none;">
                            <label for="semanas" class="form-label">Número de semanas</label>
                            <input type="number" id="semanas" name="semanas" class="form-control" value="4" min="1" max="12">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Adicionar Horário</button>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">Legenda</div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="me-2" style="width: 20px; height: 20px; background-color: #10b981;"></div>
                        <span>Disponível</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="me-2" style="width: 20px; height: 20px; background-color: #ef4444;"></div>
                        <span>Agendado</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div id="calendar-disponibilidade"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmação de exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Deseja realmente excluir este horário de disponibilidade?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Excluir</button>
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
    // Toggle campos para horários recorrentes
    document.getElementById('recorrente').addEventListener('change', function() {
        const recorrenteOptions = document.querySelector('.recorrente-options');
        recorrenteOptions.style.display = this.checked ? 'block' : 'none';
    });
    
    const calendarEl = document.getElementById('calendar-disponibilidade');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            locale: 'pt-br',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            slotMinTime: '07:00:00',
            slotMaxTime: '20:00:00',
            selectable: true,
            select: function(info) {
                // Preencher formulário com data/hora selecionada
                document.getElementById('data').value = info.startStr.split('T')[0];
                document.getElementById('hora_inicio').value = info.startStr.split('T')[1].substr(0, 5);
                document.getElementById('hora_fim').value = info.endStr.split('T')[1].substr(0, 5);
            },
            events: '{{ route("api.medico.horarios") }}',
            eventClick: function(info) {
                if (info.event.extendedProps.disponivel) {
                    // Mostrar modal de confirmação
                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                    deleteModal.show();
                    
                    // Configurar botão de confirmação
                    document.getElementById('confirmDelete').onclick = function() {
                        fetch(`/medico/disponibilidade/${info.event.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remover evento do calendário
                                info.event.remove();
                                deleteModal.hide();
                            }
                        });
                    };
                }
            },
            eventClassNames: function(arg) {
                return arg.event.extendedProps.disponivel ? ['disponivel'] : ['indisponivel'];
            }
        });
        calendar.render();
    }
});
</script>
@endsection