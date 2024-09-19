@extends('adminlte::page')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Servicios</a></li>
            <li class="breadcrumb-item"><a href="{{ route('servicios.edit', $servicio->id) }}">Editando {{ $servicio->nombre }}</a></li>
            <li aria-current="page" class="breadcrumb-item active">Editando Imagen</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container">
                <h1>Editar Imagen para {{ $servicio->nombre }}</h1>

                <form id="imageForm" action="{{ route('serviciosImagen.update', [$servicio->id, $image->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Cambiar Imagen</label>
                                <div class="image-placeholder" id="imagePlaceholder" style="background-image: url({{ asset('storage/' . $image->path) }});">

                                    @if (!$image->path)
                                        <p class="text-sm text-gray-400 pt-1 tracking-wider">Seleccione la imagen</p>
                                    @endif
                                </div>
                                <input type="file" name="image" class="form-control-file d-none" id="imageInput">
                                <div class="invalid-feedback">Por favor, suba una imagen válida.</div>
                            </div>
                        </div>

                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Actualizar Imagen</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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
@stop

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

        input[type="file"].d-none {
            display: none;
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
