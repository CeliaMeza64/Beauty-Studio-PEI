@extends('layouts.app')
@section('background_image')
    {{ '' }}
@endsection
@section(section: 'content')
<div class="container mt-4">
    <h1 class="text-center mb-4">Crear Reserva</h1>
    <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm" class="bg-light p-4 rounded shadow">
        @csrf
    <div class="form-group mb-3">
        <label><i class="fas fa-user"></i> Nombre del Cliente</label>
        <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente"
            value="{{ old('nombre_cliente') }}" maxlength="30" required>
        @error('nombre_cliente')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group mb-3">
        <label><i class="fas fa-phone"></i> Teléfono:</label>
        <input type="text" id="telefono_cliente" name="telefono_cliente" class="form-control"
            placeholder="3345-7865" required maxlength="9" pattern="\d{4}-\d{4}"
            title="El teléfono debe tener el formato 3345-7865">
        @error('telefono_cliente')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group mb-3">
        <label><i class="fas fa-th-list"></i> Categorías:</label>
        <div id="categorias_container">
            @foreach($categorias as $categoria)
                <div class="form-check">
                    <input class="form-check-input categoria-checkbox" type="checkbox" name="categoria_id[]" value="{{ $categoria->id }}" id="categoria_{{ $categoria->id }}"
                    {{ (is_array(old('categoria_id')) && in_array($categoria->id, old('categoria_id'))) ? 'checked' : '' }} onchange="updateServicios({{ $categoria->id }})">
                    <label class="form-check-label" for="categoria_{{ $categoria->id }}">
                        {{ $categoria->nombre }}
                    </label>
                </div>
                <div class="servicios" id="servicios_categoria_{{ $categoria->id }}" style="display: none;"></div>
            @endforeach
        </div>
        <span id="categoriasError" class="text-danger" style="display:none;">Por favor, selecciona al menos una categoría.</span>
        @error('categoria_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group mb-3">
        <label><i class="fas fa-clock"></i> Duración Total (minutos)</label>
        <input type="text" class="form-control" id="duracion" name="duracion" value="{{ old('duracion') }}" readonly>
        @error('duracion')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group mb-3">
        <label><i class="fas fa-calendar-alt"></i> Fecha de la Reserva</label>
        <input type="date" class="form-control" id="fecha_reservacion" name="fecha_reservacion"
            value="{{ old('fecha_reservacion') }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
        @error('fecha_reservacion')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group mb-4">
            <label for="hora_reservacion">
                <i class="fas fa-clock"></i> Hora de la Reserva
            </label>
            <select class="form-control" id="hora_reservacion" name="hora_reservacion" required>
                <option value="">Seleccione una hora</option>
                @php
                    $duracionServicio = 60; // Ejemplo: 60 minutos
                    $startHour = 9; // 09:00 AM
                    $endHour = 20; // 08:00 PM
                    $endTime = strtotime("{$endHour}:00");
                    for ($i = $startHour; $i < $endHour; $i++) {
                        for ($j = 0; $j < 60; $j += 30) {
                            $value = sprintf('%02d:%02d', $i, $j); // Formato 24 horas
                            $finReserva = strtotime($value) + ($duracionServicio * 60);
                            if ($finReserva <= $endTime) {
                                $label = date('h:i A', strtotime($value)); // Formato AM/PM
                                echo "<option value=\"{$value}\" " . (old('hora_reservacion') == $value ? 'selected' : '') . ">{$label}</option>";
                            }}      }
                @endphp
            </select>
            <span id="horaError" class="text-danger" style="display:none;">Por favor, selecciona una hora.</span>
            @error('hora_reservacion')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="button" class="btn btn-primary" id="confirmarReserva">Guardar Reserva</button>

    </button>
</form>
    @csrf
</form>
        <!-- Contenedor para el mensaje de error de disponibilidad -->
        <div id="availabilityError" style="display: none; margin-top: 1em;" class="alert alert-danger">
            <span id="availabilityErrorText">Ya existe una reserva para esta fecha y hora. Por favor, elija otro
                horario.</span>
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
        <br><br><br>
        <div class="map">
            <h2>Encuentra Nuestra Ubicación</h2>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3873.4854422613735!2d-86.566536!3d13.869897!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTPCsDUyJzExLjYiTiA4NsKwMzMnNTkuNSJX!5e0!3m2!1ses-419!2shn!4v1723487048417!5m2!1ses-419!2shn"
                width="100%" height="350"
                style="border:0; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin-top: 1em;"
                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
        <br><br>
    @section('styles')
        <style>
            .contact-info,
            .map,
            .testimonials,
            .social-media,
            .faq {
                margin: 2em 0;
                padding: 1em;
                border: 1px solid #ddd;
                border-radius: 8px;
                background-color: #f9f9f9;
            }
            .contact-info h2,
            .map h2,
            .testimonials h2,
            .social-media h2,
            .faq h2 {
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
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Esta es tu reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Estás seguro de confirmar la reserva:</p>
                    <ul id="modalReservationDetails"></ul>
                    <p>¿Desea continuar?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Editar</button>
                    <button type="button" class="btn btn-primary" id="modalConfirmButton">Confirmar</button>
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
                var value = event.target.value.replace(/[^A-Za-z\s]/g,
                    ''); // Eliminar caracteres no permitidos
                event.target.value = value;
            });
            // Manejo del cambio de categoría
            document.getElementById('categoria_id').addEventListener('change', function(event) {
                var categoriaId = event.target.value;
                // Limpia las opciones de servicios
                var servicioSelect = document.getElementById('servicio_id');
                servicioSelect.innerHTML = '<option value="">Seleccione un servicio</option>';
                if (categoriaId) {
                    fetch('{{ route('reservas.filtrarServicios') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({
                                categoria_id: categoriaId
                            })
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
                var duracion = document.getElementById('duracion').value;
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
                // Validación de hora
                if (!duracion) {
                    document.getElementById('duracionError').style.display = 'inline';
                    return;
                } else {
                    document.getElementById('duracionError').style.display = 'none';
                }
                // Verificar disponibilidad de la fecha y hora
                fetch('{{ route('reservas.checkAvailability') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            fecha: fecha,
                            hora: hora
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.disponible) {
                            document.getElementById('availabilityError').style.display = 'block';
                            return;
                        }
                        // Ocultar mensaje de disponibilidad
                        document.getElementById('availabilityError').style.display = 'none';
                        // Mostrar detalles de la reserva en el modal
                        var modalReservationDetails = document.getElementById(
                            'modalReservationDetails');
                        modalReservationDetails.innerHTML = '';
                        modalReservationDetails.innerHTML += '<li><strong>Nombre:</strong> ' + nombre +
                            '</li>';
                        modalReservationDetails.innerHTML += '<li><strong>Teléfono:</strong> ' +
                            telefono + '</li>';
                        modalReservationDetails.innerHTML += '<li><strong>Categoría:</strong> ' +
                            document.getElementById('categoria_id').options[document.getElementById(
                                'categoria_id').selectedIndex].text + '</li>';
                        modalReservationDetails.innerHTML += '<li><strong>Servicio:</strong> ' +
                            document.getElementById('servicio_id').options[document.getElementById(
                                'servicio_id').selectedIndex].text + '</li>';
                        modalReservationDetails.innerHTML += '<li><strong>Fecha:</strong> ' + fecha +
                            '</li>';
                        modalReservationDetails.innerHTML += '<li><strong>Hora:</strong> ' + hora +
                            '</li>';
                        modalReservationDetails.innerHTML += '<li><strong>Duracion:</strong> ' + duracion + '</li>';
                        // Mostrar el modal
                        var confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
                        confirmModal.show();
                    })
                    .catch(error => console.error('Error:', error));
            });
            // Manejo del botón de confirmar en el modal
            document.getElementById('modalConfirmButton').addEventListener('click', function() {
                // Cerrar el modal
                var confirmModalEl = document.getElementById('confirmModal');
                var confirmModal = bootstrap.Modal.getInstance(confirmModalEl);
                confirmModal.hide();
                // Mostrar el contenedor de impresión
                document.getElementById('printConfirmation').style.display = 'block';
            });
            // Manejo del botón de imprimir
            document.getElementById('printButton').addEventListener('click', function() {
                // Recopilar los detalles de la reserva desde el formulario
                var nombre = document.getElementById('nombre_cliente').value.trim();
                var telefono = document.getElementById('telefono_cliente').value.trim();
                var categoria = document.getElementById('categoria_id').options[document.getElementById(
                    'categoria_id').selectedIndex].text;
                var servicio = document.getElementById('servicio_id').options[document.getElementById(
                    'servicio_id').selectedIndex].text;
                var fecha = document.getElementById('fecha_reservacion').value;
                var hora = document.getElementById('hora_reservacion').value;
                // Crear contenido HTML para la impresión
                var printContent = `
                <html>
                <head>
                    <title>Confirmación de Reserva</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        h1 { text-align: center; }
                        .reservation-details { margin-top: 20px; }
                        .reservation-details ul { list-style-type: none; padding: 0; }
                        .reservation-details li { margin-bottom: 10px; }
                    </style>
                </head>
                <body>
                    <h1>Confirmación de Reserva</h1>
                    <div class="reservation-details">
                        <ul>
                            <li><strong>Nombre:</strong> ${nombre}</li>
                            <li><strong>Teléfono:</strong> ${telefono}</li>
                            <li><strong>Categoría:</strong> ${categoria}</li>
                            <li><strong>Servicio:</strong> ${servicio}</li>
                            <li><strong>Fecha:</strong> ${fecha}</li>
                            <li><strong>Hora:</strong> ${hora}</li>
                        </ul>
                    </div>
                </body>
                </html>
            `;
                // Abrir una nueva ventana para imprimir
                var printWindow = window.open('', '_blank');
                // Escribir el contenido en la nueva ventana
                printWindow.document.open();
                printWindow.document.write(printContent);
                printWindow.document.close();
                // Esperar a que el contenido se cargue y luego imprimir
                printWindow.onload = function() {
                    printWindow.print();
                    printWindow.close();
                };
                // Enviar el formulario para guardar la reserva
                document.getElementById('reservaForm').submit();
                // Ocultar el contenedor de impresión y mostrar el mensaje final
                document.getElementById('printConfirmation').style.display = 'none';
                document.getElementById('finalMessage').style.display = 'block';
            });
            // Manejo del botón de cancelar impresión
            document.getElementById('cancelPrintButton').addEventListener('click', function() {
                // Enviar el formulario sin imprimir
                document.getElementById('reservaForm').submit();
                // Ocultar el contenedor de impresión y mostrar el mensaje final
                document.getElementById('printConfirmation').style.display = 'none';
                document.getElementById('finalMessage').style.display = 'block';
            });
            // Manejo del botón de editar (si decides mantenerlo fuera del modal)
            document.getElementById('editButton').addEventListener('click', function() {
                document.getElementById('confirmationMessage').style.display = 'none';
                document.getElementById('reservaForm').scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    function updateServicios(categoriaId) {
        const categoriaCheckbox = document.getElementById('categoria_' + categoriaId);
        const serviciosDiv = document.getElementById('servicios_categoria_' + categoriaId);
        serviciosDiv.innerHTML = ''; // Limpiar servicios previos
        if (categoriaCheckbox.checked) {
            serviciosDiv.style.display = 'block';
            let servicioSelect = document.createElement('select');
            servicioSelect.name = 'servicio_id[' + categoriaId + ']';
            servicioSelect.classList.add('form-control', 'mb-3');
            servicioSelect.required = true;
            servicioSelect.innerHTML = `<option value="">Seleccione un servicio</option>`;
            @foreach ($servicios as $servicio)
                if ({{ $servicio->categoria_id }} === categoriaId) {
                    servicioSelect.innerHTML += `<option value="{{ $servicio->id }}" data-duracion="{{ $servicio->duracion }}">
                                                {{ $servicio->nombre }}</option>`;
                }
            @endforeach

            servicioSelect.addEventListener('change', actualizarDuracionTotal);
            serviciosDiv.appendChild(servicioSelect);
        } else {
            serviciosDiv.style.display = 'none';
            actualizarDuracionTotal();
        }
    }
    function actualizarDuracionTotal() {
        let duracionTotal = 0;
        const selectsServicios = document.querySelectorAll('#categorias_container select');

        selectsServicios.forEach(select => {
            if (select.value) {
                let optionSeleccionada = select.options[select.selectedIndex];
                duracionTotal += parseInt(optionSeleccionada.dataset.duracion) || 0;
            }
        });
        document.getElementById('duracion').value = duracionTotal;
    }
</script>
</div>
@endsection