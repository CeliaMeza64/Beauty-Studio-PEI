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
            <div class="d-flex justify-content-start align-items-center">
                <a type="button" class="btn btn-outline-success mx-3" href="{{ route('usuario.edit', $usuario->id) }}" tabindex="4">
                    <i class="fas fa-edit" aria-hidden="true"></i> Editar Perfil
                </a>
                
                <a type="button" class="btn btn-outline-warning mx-3" href="{{ route('usuario.editPassword') }}" tabindex="4">
                    <i class="fas fa-key" aria-hidden="true"></i> Cambiar Contraseña
                </a>
                
                <a type="button" class="btn btn-outline-danger mx-3" href="{{ route('home') }}" tabindex="4">
                    <i class="fa fa-times" aria-hidden="true"></i> Cancelar
                </a>
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
