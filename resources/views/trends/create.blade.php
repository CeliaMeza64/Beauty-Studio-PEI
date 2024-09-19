@extends('adminlte::page')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('trends.index') }}">Tendencias</a></li>
            <li aria-current="page" class="breadcrumb-item active">Crear una Tendencia</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('trends.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 order-md-2 position-relative">
                        <div class="form-group">
                            <label class="font-weight-bold-custom mb-1">Subir Imagen</label>
                            <div class="image-placeholder" id="imagePlaceholder" style="cursor: pointer;">
                                <p class="text-sm text-gray-400 pt-1 tracking-wider">Seleccione la imagen</p>
                            </div>
                            <input type="file" name="image" class="form-control-file d-none" id="imagenInput">
                        </div>
                    </div>
                    <div class="col-md-6 order-md-1 d-flex flex-column justify-content-between">
                        <div>
                            <div class="form-group">
                                <label for="title" class="font-weight-bold-custom">Título</label>
                                <input type="text" name="title" id="title" placeholder="Título de la tendencia" class="form-control" required maxlength="100">
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="description" class="font-weight-bold-custom">Descripción</label>
                                <textarea name="description" placeholder="Añada los detalles sobre la tendencia" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="row justify-content-start mt-4">
                            <div class="col-md-6 d-flex">
                                <button type="submit" class="btn btn-outline-success mr-2 flex-fill" tabindex="4">
                                    <span class="fas fa-save"></span> Guardar
                                </button>
                                <a href="{{ route('trends.index') }}" class="btn btn-outline-danger flex-fill" tabindex="5">
                                    <i class="fa fa-times" aria-hidden="true"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
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
    </script>
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

        input[type="file"].d-none {
            display: none !important;
        }

        .d-flex.justify-content-start.mt-4 {
            position: absolute;
            bottom: 0;
            left: 0;
        }
    </style>
@stop