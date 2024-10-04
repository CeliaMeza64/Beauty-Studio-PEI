@extends('adminlte::page')

@section('title', 'Panel de Administración')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Calendario de Reservas</li> 
        </ol>
    </nav>
@endsection



@section('content')
    <div class="row">
        <!-- Columna para el calendario -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        <!-- Columna para mostrar los detalles de las reservas del día seleccionado -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Reservas del Día</h4>
                </div>
                <div class="card-body" id="event-details">
                    <p>Haz clic en un día para ver las reservas.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
    <style>
        /* Ajustes estéticos */
        #calendar {
            width: 100%;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: #fff;
        }
        .fc-header-toolbar {
            font-size: 14px;
        }
        .fc-daygrid-day-number {
            font-size: 12px;
        }
        .fc-event {
            font-size: 12px;
            padding: 2px 4px;
            border-radius: 4px;
        }
        .fc-today {
            background-color: #eafaf1 !important;
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/es.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var eventDetailsEl = document.getElementById('event-details');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                initialView: 'dayGridMonth',
                events: '/reservas/calendario', // Carga los eventos (reservas)
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                height: 'auto', // Hace el calendario responsivo
                dateClick: function(info) {
                    // Cuando se hace clic en una fecha
                    var selectedDate = info.dateStr;

                    // Realiza una petición AJAX para obtener las reservas del día seleccionado
                    fetch(`/reservas/por-dia/${selectedDate}`)
                        .then(response => response.json())
                        .then(data => {
                            // Limpiar el contenido anterior
                            eventDetailsEl.innerHTML = '';

                            if (data.length > 0) {
                                // Mostrar las reservas en el lado derecho
                                data.forEach(evento => {
                                    var div = document.createElement('div');
                                    div.innerHTML = `<strong>${evento.title}</strong><br>${evento.description}<br><small>${evento.time}</small>`;
                                    eventDetailsEl.appendChild(div);
                                });
                            } else {
                                eventDetailsEl.innerHTML = '<p>No hay reservas para este día.</p>';
                            }
                        })
                        .catch(error => {
                            console.error('Error al cargar las reservas:', error);
                            eventDetailsEl.innerHTML = '<p>Error al cargar las reservas.</p>';
                        });
                }
            });

            calendar.render();
        });
    </script>
@endsection
