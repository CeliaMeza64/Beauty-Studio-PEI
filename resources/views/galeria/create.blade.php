@extends('adminlte::page')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('galeria.index') }}">Imágenes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Añadir Imagen</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container">
                <form id="imagenForm" action="{{ route('galeria.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Columna para la imagen -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold-custom mb-1">Seleccionar Imagen</label>
                                <div class="image-placeholder" id="imagePlaceholder" style="cursor: pointer;">
                                    <p class="text-sm text-gray-400 pt-1 tracking-wider">Seleccione la imagen</p>
                                </div>
                                <input type="file" name="imagen" class="form-control-file visually-hidden" id="imagenInput" required>
                                <div class="invalid-feedback">Por favor, seleccione una imagen.</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-star">
                        <button type="submit" class="btn btn-outline-success mr-4">
                            <span class="fas fa-check"></span> Guardar
                        </button>
                        <a href="{{ route('galeria.index') }}" class="btn btn-outline-danger">
                            <i class="fa fa-times" aria-hidden="true"></i> Cancelar
                        </a>
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

                document.getElementById('imagenForm').addEventListener('submit', function(event) {
                    let isValid = true;
                    const requiredFields = document.querySelectorAll('#imagenForm [required]');

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
@stop

@section('css')
    <style>
        .breadcrumb-item a, 
        .breadcrumb-item.active {
            font-size: 1.30em; 
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
