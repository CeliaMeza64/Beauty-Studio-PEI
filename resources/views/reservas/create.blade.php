@extends('layouts.app')

@section('background_image')
{{''}}
@endsection

@section('content')
<div class="container">
    <h1>Crear Reserva</h1>

    <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
        @csrf
        <div class="form-group">
            <label for="nombre_cliente">Nombre del Cliente</label>
            <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" value="{{ old('nombre_cliente') }}" maxlength="30" required>
            <span id="nombreError" style="color:red; display:none;">Completa este campo, formato en letras</span>
            @error('nombre_cliente')
                <span style="color:red;">{{ $message }}</span>
            @enderror
        </div>

        <br>
        <div class="form-group">
            <label for="telefono_cliente">Teléfono:</label>
            <input type="text" id="telefono_cliente" name="telefono_cliente" class="form-control" placeholder="3345-7865" required
                   maxlength="9" pattern="\d{4}-\d{4}" title="El teléfono debe tener el formato 3345-7865">
            <span id="telefonoError" style="display:none; color:red;">El teléfono debe tener el formato 3345-7865.</span>
            @error('telefono_cliente')
                <span style="color:red;">{{ $message }}</span>
            @enderror
        </div>

        <br>
        <div class="form-group">
    <label for="categoria">Categoría:</label>
    <select name="categoria_id" id="categoria_id" class="form-control" required>
        <option value="">Seleccione una categoría</option>
        @foreach($categorias as $categoria)
            <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                {{ $categoria->nombre }}
            </option>
        @endforeach
    </select>
    <span id="categoriaError" style="color:red; display:none;">Por favor, selecciona una categoría.</span>
    @error('categoria_id')
        <span style="color:red;">{{ $message }}</span>
    @enderror
</div>

<br>
<div class="form-group">
    <label for="servicio">Servicio:</label>
    <select name="servicio_id" id="servicio_id" class="form-control" required>
        <option value="">Seleccione un servicio</option>
        {{-- Los servicios se llenarán dinámicamente --}}
    </select>
    <span id="servicioError" style="color:red; display:none;">Por favor, selecciona un servicio.</span>
    @error('servicio_id')
        <span style="color:red;">{{ $message }}</span>
    @enderror
</div>


        <br>
        <div class="form-group">
            <label for="fecha_reservacion">Fecha de la Reserva</label>
            <input type="date" class="form-control" id="fecha_reservacion" name="fecha_reservacion" value="{{ old('fecha_reservacion') }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
            <span id="fechaError" style="color:red; display:none;">Por favor, selecciona una fecha válida.</span>
            @error('fecha_reservacion')
                <span style="color:red;">{{ $message }}</span>
            @enderror
        </div>

        <br>
        <div class="form-group">
            <label for="hora_reservacion">Hora de la Reserva</label>
            <select class="form-control" id="hora_reservacion" name="hora_reservacion" required>
                <option value="">Seleccione una hora</option>
                <option value="09:00">09:00 AM</option>
                <option value="11:00">11:00 AM</option>
                <option value="13:00">01:00 PM</option>
                <option value="15:00">03:00 PM</option>
                <option value="18:00">06:00 PM</option>
                <option value="20:00">08:00 PM</option>
            </select>
            <span id="horaError" style="color:red; display:none;">Por favor, selecciona una hora.</span>
            @error('hora_reservacion')
                <span style="color:red;">{{ $message }}</span>
            @enderror
        </div>
        <br>
        <button type="submit" class="btn btn-primary" id="guardarReservaButton">
            Guardar Reserva
        </button>
    </form>

    <!-- Contenedor para el mensaje de error de disponibilidad -->
    <div id="availabilityError" style="display: none; margin-top: 1em;" class="alert alert-danger">
        <span id="availabilityErrorText">Ya existe una reserva para esta fecha y hora. Por favor, elija otro horario.</span>
    </div>

    <!-- Contenedor para el mensaje de confirmación -->
    <div id="confirmationMessage" style="display: none; margin-top: 1em;" class="alert alert-success">
        <strong>Confirmación:</strong>
        <ul id="reservationDetails"></ul>
        <button type="button" class="btn btn-secondary" id="editButton">Editar</button>
        <button type="button" class="btn btn-primary" id="confirmButton">Confirmar Reserva</button>
    </div>

    <!-- Contenedor para la pregunta de impresión -->
    <div id="printConfirmation" style="display: none; margin-top: 1em;" class="alert alert-info">
        <p>¿Desea imprimir la reserva?</p>
        <button type="button" class="btn btn-secondary" id="cancelPrintButton">Cancelar</button>
        <button type="button" class="btn btn-primary" id="printButton">Imprimir</button>
    </div>

    <!-- Contenedor para el mensaje final -->
    <div id="finalMessage" style="display: none; margin-top: 1em;" class="alert alert-info">
        <strong>¡Gracias por su reserva!</strong> En breve se le confirmará su reserva.
    </div>

    <br>
    <br>
    <br>
    
    <div class="map">
        <h2>Encuentra Nuestra Ubicación</h2>
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3873.4854422613735!2d-86.566536!3d13.869897!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTPCsDUyJzExLjYiTiA4NsKwMzMnNTkuNSJX!5e0!3m2!1ses-419!2shn!4v1723487048417!5m2!1ses-419!2shn" 
            width="100%" 
            height="350" 
            style="border:0; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin-top: 1em;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>

    <br>
    <br>
    
    @section('styles')
    <style>
        .contact-info, .map, .testimonials, .social-media, .faq {
            margin: 2em 0;
            padding: 1em;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .contact-info h2, .map h2, .testimonials h2, .social-media h2, .faq h2 {
            margin-bottom: 1em;
        }

        .social-media a {
            margin-right: 10px;
            text-decoration: none;
            color: #007bff;
        }

        .social-media a:hover {
            text-decoration: underline;
        }

        #confirmationMessage {
            position: relative;
            padding-right: 30px;
        }

        #confirmationMessage .btn-close {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }
    </style>
    @endsection

    <!-- Modal de Confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Esta es tu reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Estás seguro de confirmar la reserva:</p>
                    <ul id="reservationDetails"></ul>
                    <p>¿Desea continuar?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">editar</button>
                    <button type="button" class="btn btn-primary" id="confirmButton">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script de Validación y Confirmación -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
    // Formatear el campo de teléfono mientras el usuario escribe
    document.getElementById('telefono_cliente').addEventListener('input', function(event) {
        var value = event.target.value.replace(/[^0-9]/g, ''); // Eliminar caracteres no numéricos
        if (value.length > 9) {
            value = value.slice(0, 9); // Limitar a 9 caracteres
        }
        if (value.length > 4) {
            value = value.slice(0, 4) + '-' + value.slice(4); // Añadir guion
        }
        event.target.value = value;
    });

    // Evitar la entrada de números en el campo de nombre
    document.getElementById('nombre_cliente').addEventListener('input', function(event) {
        var value = event.target.value.replace(/[^A-Za-z\s]/g, ''); // Eliminar caracteres no permitidos
        event.target.value = value;
    });

    // Manejo del cambio de categoría
    document.getElementById('categoria_id').addEventListener('change', function(event) {
        var categoriaId = event.target.value;

        // Limpia las opciones de servicios
        var servicioSelect = document.getElementById('servicio_id');
        servicioSelect.innerHTML = '<option value="">Seleccione un servicio</option>';

        if (categoriaId) {
            fetch('{{ route("reservas.filtrarServicios") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ categoria_id: categoriaId })
            })
            .then(response => response.json())
            .then(data => {
                data.forEach(servicio => {
                    var option = document.createElement('option');
                    option.value = servicio.id;
                    option.textContent = servicio.nombre;
                    servicioSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
        }
    });

    // Manejo del envío del formulario
    document.getElementById('reservaForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var nombre = document.getElementById('nombre_cliente').value.trim();
        var telefono = document.getElementById('telefono_cliente').value.trim();
        var categoria = document.getElementById('categoria_id').value;
        var servicio = document.getElementById('servicio_id').value;
        var fecha = document.getElementById('fecha_reservacion').value;
        var hora = document.getElementById('hora_reservacion').value;

        // Validación del nombre
        if (nombre.length === 0 || !/^[A-Za-z\s]+$/.test(nombre)) {
            document.getElementById('nombreError').style.display = 'inline';
            return;
        } else {
            document.getElementById('nombreError').style.display = 'none';
        }

        // Validación del teléfono
        if (telefono.length === 0 || !/^\d{4}-\d{4}$/.test(telefono)) {
            document.getElementById('telefonoError').style.display = 'inline';
            return;
        } else {
            document.getElementById('telefonoError').style.display = 'none';
        }

        // Validación de categoría
        if (!categoria) {
            document.getElementById('categoriaError').style.display = 'inline';
            return;
        } else {
            document.getElementById('categoriaError').style.display = 'none';
        }

        // Validación de servicio
        if (!servicio) {
            document.getElementById('servicioError').style.display = 'inline';
            return;
        } else {
            document.getElementById('servicioError').style.display = 'none';
        }

        // Validación de fecha
        if (!fecha) {
            document.getElementById('fechaError').style.display = 'inline';
            return;
        } else {
            document.getElementById('fechaError').style.display = 'none';
        }

        // Validación de hora
        if (!hora) {
            document.getElementById('horaError').style.display = 'inline';
            return;
        } else {
            document.getElementById('horaError').style.display = 'none';
        }

        // Verificar disponibilidad de la fecha y hora
        fetch('{{ route("reservas.checkAvailability") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ fecha: fecha, hora: hora })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.disponible) {
                document.getElementById('availabilityError').style.display = 'block';
                document.getElementById('confirmationMessage').style.display = 'none';
                return;
            }

            // Mostrar detalles de la reserva y opciones de confirmación
            var reservationDetails = document.getElementById('reservationDetails');
            reservationDetails.innerHTML = '';
            reservationDetails.innerHTML += '<li><strong>Nombre:</strong> ' + nombre + '</li>';
            reservationDetails.innerHTML += '<li><strong>Teléfono:</strong> ' + telefono + '</li>';
            reservationDetails.innerHTML += '<li><strong>Categoría:</strong> ' + document.getElementById('categoria_id').options[document.getElementById('categoria_id').selectedIndex].text + '</li>';
            reservationDetails.innerHTML += '<li><strong>Servicio:</strong> ' + document.getElementById('servicio_id').options[document.getElementById('servicio_id').selectedIndex].text + '</li>';
            reservationDetails.innerHTML += '<li><strong>Fecha:</strong> ' + fecha + '</li>';
            reservationDetails.innerHTML += '<li><strong>Hora:</strong> ' + hora + '</li>';

            document.getElementById('availabilityError').style.display = 'none';
            document.getElementById('confirmationMessage').style.display = 'block';
        })
        .catch(error => console.error('Error:', error));
    });

    // Manejo del botón de editar
    document.getElementById('editButton').addEventListener('click', function() {
        document.getElementById('confirmationMessage').style.display = 'none';
        document.getElementById('reservaForm').scrollIntoView({ behavior: 'smooth' });
    });

    // Manejo del botón de confirmar
    document.getElementById('confirmButton').addEventListener('click', function() {
        document.getElementById('confirmationMessage').style.display = 'none';
        document.getElementById('printConfirmation').style.display = 'block';
    });

    // Manejo del botón de imprimir
    document.getElementById('printButton').addEventListener('click', function() {
        window.print();
        document.getElementById('printConfirmation').style.display = 'none';
        document.getElementById('reservaForm').submit();
        document.getElementById('finalMessage').style.display = 'block'; // Mostrar mensaje final
    });

    // Manejo del botón de cancelar impresión
    document.getElementById('cancelPrintButton').addEventListener('click', function() {
        document.getElementById('printConfirmation').style.display = 'none';
        document.getElementById('reservaForm').submit();
        document.getElementById('finalMessage').style.display = 'block'; // Mostrar mensaje final
    });
});

    </script>
</div>
@endsection
