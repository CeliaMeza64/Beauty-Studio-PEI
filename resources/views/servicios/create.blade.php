@extends('adminlte::page')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('servicios.index') }}">Servicios</a></li>
            <li aria-current="page" class="breadcrumb-item active">Crear un servicio</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container">
                <!-- Mostrar errores de validación -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="servicioForm" action="{{ route('servicios.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 order-md-2 position-relative">
                            <div class="form-group">
                                <label class="font-weight-bold mb-1">Subir Imagen</label>
                                <div class="image-placeholder @error('imagen') is-invalid @enderror" id="imagePlaceholder" style="cursor: pointer;">
                                    <p class="text-sm text-gray-400 pt-1 tracking-wider">Seleccione la imagen</p>
                                </div>
                                <input type="file" name="imagen" class="form-control-file visually-hidden" id="imagenInput" required>
                                @error('imagen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 order-md-1">
                            <div class="form-group">
                                <label for="nombre" class="font-weight-bold">Nombre</label>
                                <input type="text" name="nombre" id="nombre" placeholder="Nombre del servicio" class="form-control @error('nombre') is-invalid @enderror"   required  maxlength="50" value="{{ old('nombre') }}">
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="descripcion" class="font-weight-bold">Descripción</label>
                                <textarea name="descripcion" id="descripcion" placeholder="Añada los detalles sobre el servicio" class="form-control @error('descripcion') is-invalid @enderror" rows="3" required>{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="categoria_id" class="font-weight-bold">Categoría</label>
                                <select name="categoria_id" id="categoria_id" class="form-control @error('categoria_id') is-invalid @enderror" required>
                                    <option value="">Seleccione una categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="disponibilidad" class="font-weight-bold">Disponibilidad</label>
                                <input type="hidden" name="disponibilidad" value="0">
                                <input type="checkbox" name="disponibilidad" value="1" {{ old('disponibilidad') ? 'checked' : '' }}>
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
                    const requiredFields = document.querySelectorAll('#servicioForm [required]');
                    
                    requiredFields.forEach(function(field) {
                        if (!field.value.trim()) {
                            field.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    });

                    const imagenInput = document.getElementById('imagenInput');
                    if (!imagenInput.files.length) {
                        document.getElementById('imagePlaceholder').classList.add('is-invalid');
                        isValid = false;
                    } else {
                        document.getElementById('imagePlaceholder').classList.remove('is-invalid');
                    }

                    if (!isValid) {
                        event.preventDefault(); 
                        alert('Por favor, complete todos los campos obligatorios.');
                    }
                });
            </script>
        </div>
    </div>
@stop

@section('css')
    <style>
        .breadcrumb-item a, 
        .breadcrumb-item.active {
            font-size: 1.2em; 
        }

        .font-weight-bold {
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

        input[type="file"].visually-hidden {
            visibility: hidden;
            position: absolute;
            width: 0;
            height: 0;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: none;
            color: #dc3545;
        }

        .is-invalid ~ .invalid-feedback {
            display: block;
        }
    </style>
@stop
