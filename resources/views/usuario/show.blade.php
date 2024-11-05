@extends('adminlte::page')

@section('title', 'Perfil de Usuario')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">
            Información del Usuario: {{ $usuario->name }}
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="container">
            <div class="row align-items-center">
                <!-- Columna de Información -->
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <label for="" style="width:30%">Nombre Completo:</label>
                        <input type="text" class="form-control" value="{{ $usuario->name }}" disabled>
                    </div>

                    <div class="input-group mb-3">
                        <label for="" style="width:30%">Nombre de Usuario:</label>
                        <input type="text" class="form-control" value="{{ $usuario->username }}" disabled>
                    </div>

                    <div class="input-group mb-3">
                        <label for="" style="width:30%">Correo Electrónico:</label>
                        <input type="text" class="form-control" 
                               value="{{ Str::mask($usuario->email, '*', 3) }}" disabled>
                    </div>

                    <div class="input-group mb-3">
                        <label for="" style="width:30%">Rol:</label>
                        <input type="text" class="form-control" value="{{ ucfirst($usuario->role) }}" disabled>
                    </div>
                </div>

                <!-- Columna de Imagen -->
                <div class="col-md-6 text-center">
                    <div class="image-container">
                        <img 
                            src="{{ $usuario->imagen ? asset("storage/{$usuario->imagen}") : '' }}" 
                            class="img-fluid" alt="Imagen del Usuario">
                        @if (!$usuario->imagen)
                            <p class="text-muted mt-2">No hay imagen disponible</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-5 justify-content-start">
    <div class="col-md-8 text-center">
        <a href="{{ route('usuario.edit', $usuario->id) }}" 
           class="btn btn-success btn-lg mx-2" title="Editar">
            <i class="fas fa-edit"></i> Editar Perfil
        </a>
        <a href="{{ route('usuario.editPassword') }}" 
           class="btn btn-warning btn-lg mx-2" title="Cambiar Contraseña">
            <i class="fas fa-key"></i> Cambiar Contraseña
        </a>
        <a href="{{ route('home') }}" 
           class="btn btn-danger btn-lg mx-2" title="Cancelar">
            <i class="fa fa-times"></i> Cancelar
        </a>
    </div>
</div>

        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .breadcrumb-item a, 
    .breadcrumb-item.active {
        font-size: 1.2em;
    }

    .image-container img {
        width: 300px;
        height: 300px; 
        object-fit: cover; 
        border: 2px dashed #ddd; 
        background-color: #f8f9fa;
    }

    .btn-lg {
        width: 200px;
    }

    .input-group label {
        font-weight: bold;
        margin-right: 10px;
    }
</style>
@endsection
