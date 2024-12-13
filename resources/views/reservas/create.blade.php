@extends('layouts.app')

@section('background_image')
{{''}}
@endsection


@section('content')
<div class="container">
    <div id="alertaContainer"></div>
    <h1>Crear Reserva</h1>

    <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm" class="bg-light p-4 rounded shadow">
        @csrf
        <div class="form-group">
            <label for="nombre_cliente"> <i class="fas fa-user"></i> Nombre del Cliente</label>
            <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" placeholder="Ingrese su nombre"  value="{{ old('nombre_cliente') }}" maxlength="30" required>
            <span id="nombreError" style="color:red; display:none;">Completa este campo, formato en letras</span>
            @error('nombre_cliente')
                <span style="color:red;">{{ $message }}</span>
            @enderror
        </div>

        <br>
        <div class="form-group">
            <label for="telefono_cliente"><i class="fas fa-phone"></i> Teléfono:</label>
            <input type="text" id="telefono_cliente" name="telefono_cliente" class="form-control" placeholder="XXXX-XXXX" required
                   maxlength="9" pattern="\d{4}-\d{4}" title="El teléfono debe tener el formato XXXX-XXXX">
            <span id="telefonoError" style="display:none; color:red;">El teléfono debe tener el formato XXXX-XXXX.</span>
            @error('telefono_cliente')
                <span style="color:red;">{{ $message }}</span>
            @enderror
        </div>

        <br>
        <div class="form-group mb-3">
        <label><i class="fas fa-th-list"></i> Categorías:</label>
        <div id="categorias_container">
            @foreach($categorias as $categoria)
                <div class="form-check">
                    <input class="form-check-input categoria-checkbox" type="checkbox" name="categoria_id[]" value="{{ $categoria->id }}" id="categoria_{{ $categoria->id }}"
                    {{ (is_array(old('categoria_id')) && in_array($categoria->id, old('categoria_id'))) ? 'checked' : '' }} onchange="updateServicios({{ $categoria->id }})">
                    <label class="form-check-label" for="categoria_{{ $categoria->id }}">
                       <b> {{ $categoria->nombre }}</b>
                    </label>
                </div>
                <div class="servicios" id="servicios_categoria_{{ $categoria->id }}" style="display: none;">
                    <label><i class="fas fa-briefcase"></i> Servicios:</label>
                    @foreach($categoria->servicios as $servicio)
                        <div class="form-check">
                            <input class="form-check-input servicio-checkbox" type="checkbox" name="servicios[]" value="{{ $servicio->id }}"
                            {{ (is_array(old('servicios')) && in_array($servicio->id, old('servicios'))) ? 'checked' : '' }}>
                            <label class="form-check-label">
                                {{ $servicio->nombre }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @endforeach
            </div>
            <span id="categoriasError" class="text-danger" style="display:none;">Por favor, selecciona al menos una categoría.</span>
            @error('categoria_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>



        <br>
        <div class="form-group">
            <label for="fecha_reservacion"><i class="fas fa-calendar-alt"></i> Fecha de la Reserva</label>
            <input type="date" class="form-control" id="fecha_reservacion" name="fecha_reservacion" value="{{ old('fecha_reservacion') }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
            <span id="fechaError" style="color:red; display:none;">Por favor, selecciona una fecha válida.</span>
            @error('fecha_reservacion')
                <span style="color:red;">{{ $message }}</span>
            @enderror
        </div>

        <br>
        <div class="form-group mb-4">
            <label for="hora_reservacion">
                <i class="fas fa-clock"></i> Hora de la Reserva
            </label>
            <select class="form-control" id="hora_reservacion" name="hora_reservacion" required>
                <option value="">Seleccione una hora</option>
                @php
                    $duracionServicio = 60; 
                    $startHour = 9; 
                    $endHour = 21; 
                    $endTime = strtotime("{$endHour}:00");
                    for ($i = $startHour; $i < $endHour; $i++) {
                        for ($j = 0; $j < 60; $j += 30) {
                            $value = sprintf('%02d:%02d', $i, $j); 
                            $finReserva = strtotime($value) + ($duracionServicio * 60);
                            if ($finReserva <= $endTime) {
                                $label = date('h:i A', strtotime($value)); 
                                echo "<option value=\"{$value}\" " . (old('hora_reservacion') == $value ? 'selected' : '') . ">{$label}</option>";
                            }}      }
                @endphp
            </select>
            <span id="horaError" class="text-danger" style="display:none;">Por favor, selecciona una hora.</span>
            @error('hora_reservacion')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <br>
        <button type="button" class="btn btn-primary" id="guardarReservaButton">
    Guardar Reserva
</button>

    </form>

  <!-- Modal -->
<div class="modal fade" id="reservaModal" tabindex="-1" aria-labelledby="reservaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservaModalLabel">Comprobante de la Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Nombre del Cliente:</strong> <span id="modalNombreCliente"></span></p>
                <p><strong>Teléfono:</strong> <span id="modalTelefonoCliente"></span></p>
                <p><strong>Fecha de la Reserva:</strong> <span id="modalFecha"></span></p>
                <p><strong>Hora de Inicio:</strong> <span id="modalHoraInicio"></span></p>
                <p><strong>Duración Total (minutos):</strong> <span id="modalDuracionTotal"></span></p>
                <p><strong>Hora Final de la Reserva:</strong> <span id="modalHoraFinal"></span></p>
                <p><strong>Estado de la Reserva:</strong> <span id="modalEstadoReserva"></span></p>
                <hr>
                <h3 style="text-align: center; margin-bottom: 1em;">Gracias por preferirnos</h3>
                <div id="modalCategoriasServicios"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="editarReservaButton">Editar</button>
                <button type="button" class="btn btn-primary" id="aceptarReservaButton" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>
 

 <div class="modal fade" id="imprimirModal" tabindex="-1" aria-labelledby="imprimirModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imprimirModalLabel">Imprimir Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Desea imprimir los detalles de la reserva?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id= "imprimirReservaButton">Imprimir</button>
            </div>
        </div>
    </div>
</div>


    <script>
    document.getElementById('guardarReservaButton').addEventListener('click', function (event) {
        event.preventDefault(); 


        const nombre = document.getElementById('nombre_cliente').value;
        const telefono = document.getElementById('telefono_cliente').value;
        const fecha = document.getElementById('fecha_reservacion').value;
        const hora = document.getElementById('hora_reservacion').value;
        const categoriasSeleccionadas = [];
        let totalServicios = 0;  

        document.querySelectorAll('.categoria-checkbox:checked').forEach(categoria => {
            const categoriaId = categoria.value;
            const servicios = [];
            document.querySelectorAll(`#servicios_categoria_${categoriaId} .servicio-checkbox:checked`).forEach(servicio => {
                servicios.push(servicio.parentNode.textContent.trim());
                totalServicios++; 

            });
            categoriasSeleccionadas.push({
                nombre: categoria.nextElementSibling.textContent.trim(),
                servicios: servicios
            });
        });

        const alertaContainer = document.getElementById('alertaContainer');
        alertaContainer.innerHTML = ''; 

        if (!nombre || !/^[A-Za-z\s]+$/.test(nombre)) {
            alertaContainer.innerHTML = `<div class="alert alert-danger" role="alert">
            El nombre no es válido. Asegúrate de que solo contenga letras y espacios.
        </div>`;
            return;
        }
        if (!telefono || !/^\d{4}-\d{4}$/.test(telefono)) {
            alertaContainer.innerHTML = `<div class="alert alert-danger" role="alert">
            El teléfono debe tener el formato correcto (ej. 1234-5678).
        </div>`;
            return;
        }
        if (categoriasSeleccionadas.length === 0 || totalServicios === 0) {
            alertaContainer.innerHTML = `<div class="alert alert-danger" role="alert">
            Por favor, selecciona al menos una categoría con sus servicios.
        </div>`;
            return;
        }
        if (!fecha || new Date(fecha) <= new Date()) {
            alertaContainer.innerHTML = `<div class="alert alert-danger" role="alert">
        La fecha debe ser válida y no puede ser en el pasado.
    </div>`;
            return;
        }
        if (!hora || hora < "09:00" || hora > "21:00") {
            alertaContainer.innerHTML = `<div class="alert alert-danger" role="alert">
        La hora debe estar entre las 09:00 AM y las 09:00 PM.
    </div>`;
            return;
        }

       

        const duracionTotal = totalServicios * 60;


        const horaInicio = new Date(`${fecha}T${hora}:00`); 
        horaInicio.setMinutes(horaInicio.getMinutes() + duracionTotal); 


        const formatoHora = (fecha) => {
            let horas = fecha.getHours().toString().padStart(2, '0');
            let minutos = fecha.getMinutes().toString().padStart(2, '0');
            return `${horas}:${minutos}`;
        };


        document.getElementById('modalNombreCliente').innerText = nombre;
        document.getElementById('modalTelefonoCliente').innerText = telefono;
        document.getElementById('modalFecha').innerText = fecha;
        document.getElementById('modalHoraInicio').innerText = hora;
        document.getElementById('modalDuracionTotal').innerText = duracionTotal;
        document.getElementById('modalHoraFinal').innerText = formatoHora(horaInicio);


        document.getElementById('modalEstadoReserva').innerText = "Pendiente";


        const reservaModal = new bootstrap.Modal(document.getElementById('reservaModal'));
        reservaModal.show();


        document.getElementById('aceptarReservaButton').addEventListener('click', function () {
            const formData = new FormData();
            formData.append('nombre_cliente', nombre);
            formData.append('telefono_cliente', telefono);
            formData.append('fecha_reservacion', fecha);
            formData.append('hora_reservacion', hora);
            formData.append('duracion', duracionTotal);


            fetch('{{ route("reservas.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Reserva realizada con éxito');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ha ocurrido un error al procesar la solicitud.');
            });
        });

        document.getElementById('editarReservaButton').addEventListener('click', function () {

            const modal = bootstrap.Modal.getInstance(document.getElementById('reservaModal'));
    modal.hide();


    document.getElementById('reservaForm').scrollIntoView({ behavior: 'smooth' });
    document.getElementById('reservaForm').style.display = 'block'; 


    
    document.getElementById('formNombreCliente').value = document.getElementById('modalNombreCliente').innerText;
    document.getElementById('formTelefonoCliente').value = document.getElementById('modalTelefonoCliente').innerText;
    document.getElementById('formFechaReserva').value = document.getElementById('modalFecha').innerText;
   
});

    });

    document.getElementById('imprimirModal').addEventListener('hidden.bs.modal', function () {

        document.getElementById('reservaForm').submit();
    });

    
    document.getElementById('telefono_cliente').addEventListener('input', function(event) {
        var value = event.target.value.replace(/[^0-9]/g, ''); 
        if (value.length > 9) {
            value = value.slice(0, 9); 
        }
        if (value.length > 4) {
            value = value.slice(0, 4) + '-' + value.slice(4); 
        }
        event.target.value = value;
    });

    
    document.getElementById('nombre_cliente').addEventListener('input', function(event) {
        var value = event.target.value.replace(/[^A-Za-z\s]/g, ''); 
        event.target.value = value;
    });

    

    document.getElementById('categoria_id').addEventListener('change', function(event) {
        var categoriaId = event.target.value;

       

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

    
    function updateServicios(categoriaId) {
        var serviciosContainer = document.getElementById('servicios_categoria_' + categoriaId);
        var categoriaCheckbox = document.getElementById('categoria_' + categoriaId);

       
        if (categoriaCheckbox.checked) {
            serviciosContainer.style.display = 'block';
        } else {
            serviciosContainer.style.display = 'none';
        }
    }

    
    document.addEventListener('DOMContentLoaded', function () {
       

        var categoriasCheckboxes = document.querySelectorAll('.categoria-checkbox');
        categoriasCheckboxes.forEach(function (checkbox) {
            updateServicios(checkbox.value);
        });
    });
</script>
<script>
    

    document.getElementById('aceptarReservaButton').addEventListener('click', function () {
        
        let reservaModal = bootstrap.Modal.getInstance(document.getElementById('reservaModal'));
        reservaModal.hide();

        
        let imprimirModal = new bootstrap.Modal(document.getElementById('imprimirModal'));
        imprimirModal.show();
    });
    document.getElementById('imprimirReservaButton').addEventListener('click', function () {
    
    const modalContent = document.querySelector('#reservaModal .modal-content').innerHTML;

    
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    printWindow.document.open();
    printWindow.document.write(`
        <html>
            <head>
                <title>Detalles de la Reserva</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }
                    .modal-header, .modal-footer {
                        display: none;
                    }
                    .modal-body {
                        margin: 0 auto;
                        text-align: left;
                    }
                </style>
            </head>
            <body>
                <div class="modal-body">
                    ${modalContent}
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();

    
    printWindow.print();
    printWindow.close();
});
</script>
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

</div>

@endsection
