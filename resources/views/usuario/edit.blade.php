@extends('adminlte::page')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('usuario.show') }}">Usuarios</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Editar Usuario: {{ $usuario->name }}
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form id="usuarioForm" action="{{ route('usuario.update', $usuario->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <div class="row">
                    <!-- Cambiar Imagen -->
                    <div class="col-md-6 order-md-2">
                        <div class="form-group">
                            <label class="font-weight-bold-custom mb-1">Cambiar Imagen</label>
                            <div id="imagePlaceholder" class="image-placeholder"
                                 style="background-image: url('{{ $usuario->imagen ? asset('storage/' . $usuario->imagen) : '' }}');">
                                @if (!$usuario->imagen)
                                    <p class="text-muted">Seleccione la imagen</p>
                                @endif
                            </div>
                            <input type="file" name="imagen" class="form-control-file d-none" id="imagenInput">
                            <div class="invalid-feedback">Por favor, suba una imagen válida.</div>
                        </div>
                    </div>

                    <!-- Información del Usuario -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="font-weight-bold-custom">Nombre</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $usuario->name) }}" required maxlength="100">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="username" class="font-weight-bold-custom">Usuario</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username', $usuario->username) }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class="font-weight-bold-custom">Nueva Contraseña</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="font-weight-bold-custom">Confirmar Nueva Contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-outline-success mr-2">
                            <span class="fas fa-save"></span> Actualizar
                        </button>
                        <a href="{{ route('usuario.show') }}" class="btn btn-outline-danger">
                            <i class="fa fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('imagePlaceholder').addEventListener('click', () => {
        document.getElementById('imagenInput').click();
    });

    document.getElementById('imagenInput').addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('imagePlaceholder').style.backgroundImage = `url(${e.target.result})`;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection

@section('css')
<style>
    .breadcrumb-item a, .breadcrumb-item.active {
        font-size: 1.2em;
    }

    .font-weight-bold-custom {
        font-weight: bold;
    }

    .image-placeholder {
        width: 350px;
        height: 350px;
        border: 2px dashed #ddd;
        border-radius: 5px;
        background-color: #f8f9fa;
        background-size: cover;
        background-position: center;
        cursor: pointer;
    }
</style>
@endsection
