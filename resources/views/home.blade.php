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
    var reservasColumn = document.getElementById('reservas-column'); // Columna de reservas

    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        initialView: 'dayGridMonth', // Comenzamos con la vista mensual
        events: '/reservas/calendario', // Cargar eventos de la base de datos

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        height: 'auto',
        slotMinTime: '09:00:00', // Hora mínima (9 AM)
        slotMaxTime: '21:00:00', // Hora máxima (9 PM)
        
        // Cuando se hace clic en una fecha (solo en la vista de mes)
        dateClick: function(info) {
            var selectedDate = info.dateStr;
            
            // Petición AJAX para obtener las reservas del día seleccionado
            fetch(`/reservas/por-dia/${selectedDate}`)
                .then(response => response.json())
                .then(data => {
                    // Limpiar el contenido anterior
                    eventDetailsEl.innerHTML = '';

                    if (data.length > 0) {
                        // Mostrar la cantidad y detalles de reservas
                        var cantidadReservas = data.length;
                        var divCantidad = document.createElement('div');
                        divCantidad.innerHTML = `<strong>Cantidad de Reservas:</strong> ${cantidadReservas}`;
                        eventDetailsEl.appendChild(divCantidad);

                        // Mostrar los detalles de cada reserva
                        data.forEach(function(reserva) {
                            var divReserva = document.createElement('div');
                            divReserva.innerHTML = `
                                <p><strong>Cliente:</strong> ${reserva.title}</p>
                                <p><strong>Hora:</strong> ${reserva.time}</p>
                                <p><strong>Teléfono:</strong> ${reserva.description}</p>
                                <hr>`;
                            eventDetailsEl.appendChild(divReserva);
                        });
                    } else {
                        eventDetailsEl.innerHTML = '<p>No hay reservas para este día.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error al cargar las reservas:', error);
                    eventDetailsEl.innerHTML = '<p>Error al cargar las reservas.</p>';
                });
        },

        // Cuando se hace clic en un evento (reserva)
        eventClick: function(info) {
            var reservaId = info.event.id;

            // Petición AJAX para obtener los detalles de la reserva seleccionada
            fetch(`/reservas/detalles/${reservaId}`)
                .then(response => response.json())
                .then(data => {
                    // Limpiar el contenido anterior
                    eventDetailsEl.innerHTML = '';

                    if (data) {
                        // Mostrar los detalles de la reserva seleccionada
                        var divReserva = document.createElement('div');
                        divReserva.innerHTML = `
                            <p><strong>Cliente:</strong> ${data.title}</p>
                            <p><strong>Hora:</strong> ${data.time}</p>
                            <p><strong>Teléfono:</strong> ${data.description}</p>
                            <p><strong>Servicio:</strong> ${data.servicio}</p>
                            <hr>`;
                        eventDetailsEl.appendChild(divReserva);
                    } else {
                        eventDetailsEl.innerHTML = '<p>No hay detalles disponibles para esta reserva.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error al cargar los detalles de la reserva:', error);
                    eventDetailsEl.innerHTML = '<p>Error al cargar los detalles de la reserva.</p>';
                });
        }
    });

    calendar.render();
});

    </script>
@endsection
