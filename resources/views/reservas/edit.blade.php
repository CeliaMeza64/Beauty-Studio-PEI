@extends('adminlte::page')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('reservas.index') }}">Reservas</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar Reserva</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container">
    <h2>Editar Reserva</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reservas.update', $reserva->id) }}" method="POST" id="reservaForm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre_cliente" class="form-label">Nombre del Cliente</label>
            <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" value="{{ old('nombre_cliente', $reserva->nombre_cliente) }}" required maxlength="30">
            <span id="nombreError" style="color:red; display:none;">Completa este campo, formato en letras</span>
        </div>

        <div class="mb-3">
            <label for="telefono_cliente" class="form-label">Teléfono del Cliente</label>
            <input type="text" class="form-control" id="telefono_cliente" name="telefono_cliente" value="{{ old('telefono_cliente', $reserva->telefono_cliente) }}" required maxlength="9" pattern="\d{4}-\d{4}" title="El teléfono debe tener el formato 3345-7865">
            <span id="telefonoError" style="color:red; display:none;">El teléfono debe tener el formato 3345-7865.</span>
        </div>

        <div class="mb-3">
            <label for="categoria_id" class="form-label">Categoría:</label>
            <select name="categoria_id" id="categoria_id" class="form-control" required>
                <option value="">Seleccione una categoría</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ old('categoria_id', $reserva->categoria_id) == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
            <span id="categoriaError" style="color:red; display:none;">Por favor, selecciona una categoría.</span>
        </div>

        <div class="mb-3">
            <label for="servicio_id" class="form-label">Servicio:</label>
            <select name="servicio_id" id="servicio_id" class="form-control" required>
                <option value="">Seleccione un servicio</option>
                @foreach($categorias as $categoria)
                    <optgroup label="{{ $categoria->nombre }}">
                        @foreach($categoria->servicios as $servicio)
                            <option value="{{ $servicio->id }}" {{ old('servicio_id', $reserva->servicio_id) == $servicio->id ? 'selected' : '' }}>
                                {{ $servicio->nombre }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            <span id="servicioError" style="color:red; display:none;">Por favor, selecciona un servicio.</span>
        </div>

        <div class="mb-3">
            <label for="fecha_reservacion" class="form-label">Fecha de Reservación</label>
            <input type="date" class="form-control" id="fecha_reservacion" name="fecha_reservacion" value="{{ old('fecha_reservacion', $reserva->fecha_reservacion) }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
            <span id="fechaError" style="color:red; display:none;">Por favor, selecciona una fecha válida.</span>
        </div>

        <div class="mb-3">
            <label for="hora_reservacion" class="form-label">Hora de Reservación</label>
            <select class="form-control" id="hora_reservacion" name="hora_reservacion" required>
                <option value="09:00" {{ old('hora_reservacion', $reserva->hora_reservacion) == '09:00' ? 'selected' : '' }}>09:00 AM</option>
                <option value="11:00" {{ old('hora_reservacion', $reserva->hora_reservacion) == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                <option value="13:00" {{ old('hora_reservacion', $reserva->hora_reservacion) == '13:00' ? 'selected' : '' }}>01:00 PM</option>
                <option value="15:00" {{ old('hora_reservacion', $reserva->hora_reservacion) == '15:00' ? 'selected' : '' }}>03:00 PM</option>
                <option value="18:00" {{ old('hora_reservacion', $reserva->hora_reservacion) == '18:00' ? 'selected' : '' }}>06:00 PM</option>
                <option value="20:00" {{ old('hora_reservacion', $reserva->hora_reservacion) == '20:00' ? 'selected' : '' }}>08:00 PM</option>
            </select>
            <span id="horaError" style="color:red; display:none;">Por favor, selecciona una hora.</span>
        </div>

        <!-- Estado -->
<div class="form-group">
    <label for="estado">Estado</label>
    <select name="estado" id="estado" class="form-control">
        @if ($reserva->estado == 'Aprobado')
            <option value="Aprobado" {{ $reserva->estado == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
            <option value="Realizado" {{ $reserva->estado == 'Realizado' ? 'selected' : '' }}>Realizado</option>
            <option value="Rechazado" {{ $reserva->estado == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
        @else
            <option value="Pendiente" {{ $reserva->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="Aprobado" {{ $reserva->estado == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
            <option value="Realizado" {{ $reserva->estado == 'Realizado' ? 'selected' : '' }}>Realizado</option>
            <option value="Cancelado" {{ $reserva->estado == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
        @endif
    </select>
</div>

        <div class="col-md-6 order-md-2 position-relative">
                            <div class="form-group">
                                <a href="{{ route('serviciosImagen.index', ['servicio' => $servicio->id]) }}" class="btn btn-primary mr-2 mb-3 add-image-btn" title="Agregar más imágenes"> 
                                    <i class="fas fa-plus"></i> Imágenes
                                </a>
                                <div class="image-placeholder" id="imagePlaceholder" style="cursor: pointer; background-image: url({{ asset('storage/' . $reserva->imagen) }});">
                                    @if (!$reserva->imagen)
                                        
                                    @endif
                                </div>
                                <input type="file" name="imagen" class="form-control-file d-none" id="imagenInput">
                                <div class="invalid-feedback">Por favor, suba una imagen válida.</div>
                            </div>
                        </div>
        <div class="row mt-3" id="preview-container"></div> <!-- Contenedor para las vistas previas -->

        

        <div class="row justify-content-start">
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-outline-success mr-2" style="flex: 1;">
                                            <span class="fas fa-save"></span> Actualizar
                                        </button>
                                        <a href="{{ route('servicios.index') }}" class="btn btn-outline-danger" style="flex: 1;">
                                            <i class="fa fa-times" aria-hidden="true"></i> Cancelar
                                        </a>
                                    </div>
                                </div>
                            </div>
    </form>
</div>
@endsection

@section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
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

        document.getElementById('reservaForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var nombre = document.getElementById('nombre_cliente').value.trim();
            var telefono = document.getElementById('telefono_cliente').value.trim();
            var categoria = document.getElementById('categoria_id').value;
            var servicio = document.getElementById('servicio_id').value;
            var fecha = document.getElementById('fecha_reservacion').value;
            var hora = document.getElementById('hora_reservacion').value;

            if (nombre.length === 0 || !/^[A-Za-z\s]+$/.test(nombre)) {
                document.getElementById('nombreError').style.display = 'inline';
                return;
            } else {
                document.getElementById('nombreError').style.display = 'none';
            }

            if (telefono.length === 0 || !/^\d{4}-\d{4}$/.test(telefono)) {
                document.getElementById('telefonoError').style.display = 'inline';
                return;
            } else {
                document.getElementById('telefonoError').style.display = 'none';
            }

            if (!categoria) {
                document.getElementById('categoriaError').style.display = 'inline';
                return;
            } else {
                document.getElementById('categoriaError').style.display = 'none';
            }

            if (!servicio) {
                document.getElementById('servicioError').style.display = 'inline';
                return;
            } else {
                document.getElementById('servicioError').style.display = 'none';
            }

            if (!fecha) {
                document.getElementById('fechaError').style.display = 'inline';
                return;
            } else {
                document.getElementById('fechaError').style.display = 'none';
            }

            if (!hora) {
                document.getElementById('horaError').style.display = 'inline';
                return;
            } else {
                document.getElementById('horaError').style.display = 'none';
            }

            this.submit(); 
        });
    });
    </script>
    <script>
    function previewImages() {
        const previewContainer = document.getElementById('preview-container');
        previewContainer.innerHTML = ''; 

        const files = document.getElementById('imagenes').files;
        if (files) {
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const colDiv = document.createElement('div');
                    colDiv.classList.add('col-md-3', 'mb-3');
                    colDiv.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded shadow" alt="Vista previa">`;
                    previewContainer.appendChild(colDiv);
                };
                reader.readAsDataURL(file);
            });
        }
    }

document.getElementById('reservaForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var estado = document.getElementById('estado').value;
    var estadoError = document.getElementById('estadoError');

    if (!estado) {
        estadoError.style.display = 'inline';
        return; 
    } else {
        estadoError.style.display = 'none';
    }

    this.submit();
});


</script>

@endsection
