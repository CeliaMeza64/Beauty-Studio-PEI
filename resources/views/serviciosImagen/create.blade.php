imagen @extends('adminlte::page')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('servicios.index') }}">Servicios</a></li>
            <li aria-current="page" class="breadcrumb-item active">Añadir Imagen</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container">
          
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('serviciosImagen.store', ['servicio' => $servicioId]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="servicio_id" value="{{ $servicioId }}">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold-custom mb-1">Seleccionar Imagen</label>
                                <div class="image-placeholder @error('image') is-invalid @enderror"  id="imagePlaceholder" style="cursor: pointer;">
                                    <p class="text-sm text-gray-400 pt-1 tracking-wider">Seleccione la imagen</p>
                                </div>
                                <input type="file" name="image" class="form-control-file d-none" id="imagenInput" >
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-start">
                                <button type="submit" class="btn btn-outline-success mr-2">
                                <span class="fas fa-save"></span> Guardar
                                </button>
                                
                                <a href="{{ route('serviciosImagen.index', ['servicio' => $servicioId]) }}" onclick="cancelarCreacion()" class="btn btn-outline-danger">
                                    <i class="fa fa-times" aria-hidden="true"></i> Cancelar
                                </a>
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

                function cancelarCreacion() {
                    const servicioId = document.querySelector('select[name="servicio_id"]').value;
                    if (servicioId) {
                        window.location.href = '{{ url('servicios') }}/' + servicioId + '/imagenes';
                    } else {
                        window.location.href = '{{ route('servicios.index') }}';
                    }
                }
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
