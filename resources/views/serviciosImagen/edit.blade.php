@extends('adminlte::page')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('servicios.index') }}">Servicios</a></li>
            <li aria-current="page" class="breadcrumb-item active">Editando Imagen de: {{ $servicio->nombre }}</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container">
                <form id="imageForm" action="{{ route('serviciosImagen.update', [$servicio->id, $image->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editMode" value="{{ $image->path ? 'true' : 'false' }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold-custom mb-1">Cambiar Imagen</label>
                                <div class="image-placeholder" id="imagePlaceholder" style="cursor: pointer; background-image: url({{ asset('storage/' . $image->path) }});">
                                    @if (!$image->path)
                                        <p class="text-sm text-gray-400 pt-1 tracking-wider">Seleccione la imagen</p>
                                    @endif
                                </div>
                                <input type="file" name="image" class="form-control-file d-none" id="imageInput">
                                <div class="invalid-feedback">Por favor, haga clic en la imagen para cambiarla y seleccione una imagen válida.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            
                        </div>
                    </div>
                    <div class="d-flex justify-content-start">
                        <button type="submit" class="btn btn-outline-success mr-2">
                            <span class="fas fa-save"></span> Actualizar
                        </button>
                        <a href="{{ route('serviciosImagen.index', ['servicio' => $servicio->id]) }}" class="btn btn-outline-danger">
                            <i class="fa fa-times" aria-hidden="true"></i> Cancelar
                        </a>
                    </div>

                </form>
            </div>

            <script>
                document.getElementById('imagePlaceholder').addEventListener('click', function() {
                    document.getElementById('imageInput').click();
                });

                document.getElementById('imageInput').addEventListener('change', function(event) {
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

                document.getElementById('imageForm').addEventListener('submit', function(event) {
                    let isValid = true;
                    const requiredFields = document.querySelectorAll('#imageForm [required]');

                    requiredFields.forEach(function(field) {
                        if (!field.value.trim()) {
                            field.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    });

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
        .image-placeholder {
            width: 100%;
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
@endsection
