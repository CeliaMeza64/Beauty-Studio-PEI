@extends('adminlte::page')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('servicios.index') }}">Servicios</a></li>
            <li aria-current="page" class="breadcrumb-item active">Editando el servicio de: {{ $servicio->nombre }}</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container">
                <form id="servicioForm" action="{{ route('servicios.update', $servicio->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editMode" value="{{ $servicio->imagen ? 'true' : 'false' }}">

                    <div class="row">
                        <div class="col-md-6 order-md-2 position-relative">
                            <div class="form-group">
                                <label class="font-weight-bold-custom mb-1">Cambiar Imagen</label>
                                <div class="image-placeholder" id="imagePlaceholder" style="cursor: pointer; background-image: url({{ asset('storage/' . $servicio->imagen) }});">
                                    @if (!$servicio->imagen)
                                        <p class="text-sm text-gray-400 pt-1 tracking-wider">Seleccione la imagen</p>
                                    @endif
                                </div>
                                <input type="file" name="imagen" class="form-control-file d-none" id="imagenInput">
                                <div class="invalid-feedback">Por favor, haga clic en la imagen para cambiarla y seleccione una imagen válida.</div>
                            </div>
                            <a href="{{ route('serviciosImagen.index', ['servicio' => $servicio->id]) }}" class="btn btn-primary mr-2 mb-3 add-image-btn" title="Agregar más imágenes"> 
                                    <i class="fas fa-plus"></i> Agregar otras imágenes
                                </a>
                        </div>

                       
                        <div class="col-md-6 order-md-1">
                            <div class="form-group">
                                <label for="nombre" class="font-weight-bold-custom">Nombre</label>
                                <input type="text" name="nombre" value="{{ old('nombre', $servicio->nombre) }}" placeholder="Nombre del servicio" class="form-control @error('nombre') is-invalid @enderror" required maxlength="50">
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="descripcion" class="font-weight-bold-custom">Descripción</label>
                                <textarea name="descripcion" placeholder="Añada los detalles sobre el servicio" class="form-control @error('descripcion') is-invalid @enderror" rows="3" required>{{ old('descripcion', $servicio->descripcion) }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="categoria_id" class="font-weight-bold-custom">Categoría</label>
                                <select name="categoria_id" class="form-control @error('categoria_id') is-invalid @enderror" required>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ $categoria->id == old('categoria_id', $servicio->categoria_id) ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="disponibilidad" class="font-weight-bold-custom">Disponibilidad</label>
                                <input type="hidden" name="disponibilidad" value="0">
                                <input type="checkbox" name="disponibilidad" value="1" {{ old('disponibilidad', $servicio->disponibilidad) ? 'checked' : '' }}>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="duracion" class="font-weight-bold-custom">Duración (en minutos)</label>
                                <input 
                                    type="number" name="duracion" 
                                    id="duracion" 
                                    placeholder="Ingrese la duración en minutos" 
                                    class="form-control @error('duracion') is-invalid @enderror" 
                                    required 
                                    min="30" 
                                    value="{{ old('duracion', $servicio->duracion) }}"
                                >
                                @error('duracion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>


                            <div class="row justify-content-start">
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-outline-success mr-2" style="flex: 1;" tabindex="4">
                                            <span class="fas fa-save"></span> Guardar
                                        </button>
                                        <a href="{{ route('servicios.index') }}" class="btn btn-outline-danger" style="flex: 1;" tabindex="4">
                                            <i class="fa fa-times" aria-hidden="true"></i> Cancelar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <script>
                document.getElementById('imagePlaceholder').addEventListener('click', function() {
                    document.getElementById('imagenInput').click();
                });

                document.getElementById('imagenInput').addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const placeholder = document.getElementById('imagePlaceholder');
                        placeholder.style.backgroundImage = 'url(' + e.target.result + ')';
                        placeholder.style.backgroundSize = 'contain';
                        placeholder.style.backgroundPosition = 'center';
                        placeholder.innerHTML = '';
                    };

                    reader.readAsDataURL(file);
                });

                document.getElementById('servicioForm').addEventListener('submit', function(event) {
                    let isValid = true;

                    const nombre = document.getElementById('nombre');
                    if (!nombre.value.trim()) {
                        nombre.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        nombre.classList.remove('is-invalid');
                    }

                    const descripcion = document.getElementById('descripcion');
                    if (!descripcion.value.trim()) {
                        descripcion.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        descripcion.classList.remove('is-invalid');
                    }

                    if (!isValid) {
                        event.preventDefault();
                        alert('Por favor, complete todos los campos obligatorios.');
                    }
                });
            </script>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .breadcrumb-item a, 
        .breadcrumb-item.active {
            font-size: 1.2em; 
        }

        .font-weight-bold-custom {
            font-weight: bold;
        }

        .image-placeholder {
            width: 350px;
            height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed #ddd;
            border-radius: 5px;
            background-color: #f8f9fa;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: #aaa;
            font-size: 1.2em;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .image-placeholder p {
            margin: 0;
            position: absolute;
        }

        .input-group .is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 .2rem rgba(220, 53, 69, .25); 
        }

        .invalid-feedback {
            display: block;
            font-size: 1em;
        }

        input[type="file"].d-none {
            display: none !important;
        }
    </style>
@endsection
