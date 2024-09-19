@extends('adminlte::page')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('categorias.index') }}">Categorías</a></li>
            <li aria-current="page" class="breadcrumb-item active">Editando la categoría: {{ $categoria->nombre }}</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container">
                <form id="categoriaForm" action="{{ route('categorias.update', $categoria) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="editMode" value="{{ $categoria->imagen ? 'true' : 'false' }}">

                    <div class="row">
                        <!-- Imagen -->
                        <div class="col-md-6 order-md-2 position-relative">
                            <div class="form-group">
                                <label class="font-weight-bold-custom mb-1">Cambiar Imagen</label>
                                <div class="image-placeholder" id="imagePlaceholder" style="cursor: pointer; background-image: url({{ asset('storage/' . $categoria->imagen) }});">
                                    @if (!$categoria->imagen)
                                        <p class="text-sm text-gray-400 pt-1 tracking-wider">Seleccione la imagen</p>
                                    @endif
                                </div>
                                <input type="file" name="imagen" class="form-control-file d-none" id="imagenInput">
                                <div class="invalid-feedback">Por favor, suba una imagen válida.</div>
                            </div>
                        </div>

                        <!-- Información de la categoría -->
                        <div class="col-md-6 order-md-1">
                            <div class="form-group">
                                <label for="nombre" class="font-weight-bold">Nombre</label>
                                <input type="text" name="nombre" id="nombre" placeholder="Nombre de la categoría" class="form-control @error('nombre') is-invalid @enderror" required maxlength="50" value="{{ old('nombre', $categoria->nombre) }}">
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="descripcion" class="font-weight-bold-custom">Descripción</label>
                                <textarea name="descripcion" placeholder="Detalles de la categoría" class="form-control @error('descripcion') is-invalid @enderror" rows="3" maxlength="50"  required>{{ old('descripcion', $categoria->descripcion) }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="estado" class="font-weight-bold">Estado</label>
                                <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                                    <option value="1" {{ old('estado', $categoria->estado) == '1' ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ old('estado', $categoria->estado) == '0' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Botones alineados -->
                            <div class="row justify-content-start">
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-outline-success mr-2" style="flex: 1;" tabindex="4">
                                            <span class="fas fa-save"></span> Guardar
                                        </button>
                                        <a href="{{ route('categorias.index') }}" class="btn btn-outline-danger" style="flex: 1;" tabindex="4">
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

                document.getElementById('categoriaForm').addEventListener('submit', function(event) {
                    let isValid = true;

                    // Validar nombre
                    const nombre = document.getElementById('nombre');
                    if (!nombre.value.trim()) {
                        nombre.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        nombre.classList.remove('is-invalid');
                    }

                    // Validar descripción
                    const descripcion = document.getElementById('descripcion');
                    if (!descripcion.value.trim()) {
                        descripcion.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        descripcion.classList.remove('is-invalid');
                    }

                    // Validar imagen solo si estamos en modo edición y no se ha cambiado la imagen
                    const imagenInput = document.getElementById('imagenInput');
                    const placeholder = document.getElementById('imagePlaceholder');
                    const editMode = document.getElementById('editMode').value === 'true';
                    
                    if (editMode && !imagenInput.files.length && !{{ $categoria->imagen ? 'true' : 'false' }}) {
                        placeholder.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        placeholder.classList.remove('is-invalid');
                    }

                    if (!isValid) {
                        event.preventDefault(); // Detener el envío del formulario
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
        }

        input[type="file"].d-none {
            display: none !important;
        }
    </style>
@stop
