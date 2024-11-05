@extends('adminlte::page')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('usuario.show') }}">Perfil del Usuario</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Cambiando contraseña de : {{ $usuario->name }}
        </li>
       
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('usuario.updatePassword') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group pt-3">
            <label for="current_password">Contraseña Actual</label>
            <input type="password" name="current_password" class="form-control" required>
            @error('current_password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Nueva Contraseña</label>
            <input type="password" name="password" class="form-control" required>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmar Nueva Contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
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

    </form>
</div>
@endsection
