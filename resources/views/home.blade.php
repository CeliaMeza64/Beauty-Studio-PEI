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
        <div class="col-md-4" id="reservas-column">
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
<link href="{{ asset('assets/css/main.min.css') }}" rel="stylesheet">
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
        /* Nueva clase para ocultar la columna visualmente pero mantener la estructura */
        .hidden-column {
            visibility: hidden;
            height: 0;
            padding: 0;
        }
    </style>
@endsection

@section('js')
<script src="{{ asset('assets/js/main.min.js') }}"></script>
<script src="{{ asset('assets/js/es.js') }}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var eventDetailsEl = document.getElementById('event-details');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        initialView: 'dayGridMonth', // Comenzamos con la vista mensual
        events: '/reservas/calendario', // Cargar eventos de la base de datos
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay',
        },
        height: 'auto',
        slotMinTime: '09:00:00', // Hora mínima (9 AM)
        slotMaxTime: '21:00:00', // Hora máxima (9 PM)

        // Renderizar eventos de forma condicional según la vista
        eventContent: function(arg) {
            // Vista mensual: solo mostrar la cantidad de reservas
            if (arg.view.type === 'dayGridMonth') {
                return { html: `<span>${arg.event.title}</span>` };
            }

            // Vista semanal y diaria: mostrar detalles completos
            if (arg.view.type === 'timeGridWeek' || arg.view.type === 'timeGridDay') {
                return {
                    html: `
                        <div>
                            <strong>${arg.event.title}</strong><br>
                            <span>${arg.event.extendedProps.time || ''}</span><br>
                            <small>${arg.event.extendedProps.description || ''}</small>
                        </div>
                    `,
                };
            }
        },

        // Manejar clics en la fecha
        dateClick: function(info) {
            var selectedDate = info.dateStr;

            fetch(`/reservas/por-dia/${selectedDate}`)
                .then(response => response.json())
                .then(data => {
                    eventDetailsEl.innerHTML = '';

                    if (data.length > 0) {
                        // Mostrar las reservas agrupadas por hora
                        var reservasPorHora = {};
                        data.forEach(function(reserva) {
                            var hora = reserva.time;
                            if (!reservasPorHora[hora]) {
                                reservasPorHora[hora] = [];
                            }
                            reservasPorHora[hora].push(reserva);
                        });

                        for (const hora in reservasPorHora) {
                            var divHora = document.createElement('div');
                            divHora.innerHTML = `<h5>Hora: ${hora}</h5>`;
                            eventDetailsEl.appendChild(divHora);

                            reservasPorHora[hora].forEach(function(reserva) {
                                var divReserva = document.createElement('div');
                                divReserva.innerHTML = `
                                    <p><strong>Cliente:</strong> ${reserva.title}</p>
                                    <p><strong>Teléfono:</strong> ${reserva.description}</p>
                                    <p><strong>Servicios:</strong> ${reserva.servicios}</p>
                                    <hr>`;
                                eventDetailsEl.appendChild(divReserva);
                            });
                        }
                    } else {
                        eventDetailsEl.innerHTML = '<p>No hay reservas para este día.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error al cargar las reservas:', error);
                    eventDetailsEl.innerHTML = '<p>Error al cargar las reservas.</p>';
                });
        },

        // Manejar clics en eventos (reservas)
        eventClick: function(info) {
            var reservaId = info.event.id;

            fetch(`/reservas/detalles/${reservaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        eventDetailsEl.innerHTML = `
                            <p><strong>Cliente:</strong> ${data.title}</p>
                            <p><strong>Hora:</strong> ${data.time}</p>
                            <p><strong>Teléfono:</strong> ${data.description}</p>
                            <p><strong>Servicios:</strong> ${data.servicios}</p>
                            <hr>`;
                    } else {
                        eventDetailsEl.innerHTML = '<p>No hay detalles disponibles para esta reserva.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error al cargar los detalles de la reserva:', error);
                    eventDetailsEl.innerHTML = '<p>Error al cargar los detalles de la reserva.</p>';
                });
        },
    });

    calendar.render();
});


    

    </script>
@endsection
