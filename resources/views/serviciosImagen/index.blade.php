@extends('adminlte::page')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('servicios.index') }}">Servicios</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Imágenes de {{ $servicio->nombre }}</li> 
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('serviciosImagen.create', $servicio->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Añadir Imagen
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Servicio</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($images as $image)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $servicio->nombre }}</td>
                                <td>
                                    @if ($image->path)
                                        <img src="{{ asset('storage/' . $image->path) }}" alt="Imagen de {{ $servicio->nombre }}" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        No hay imagen
                                    @endif
                                </td>
                                <td class="d-flex align-items-center">
                                    <a href="{{ route('serviciosImagen.edit', ['servicio' => $servicio->id, 'image' => $image->id]) }}" class="btn btn-success mr-2" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Botón Eliminar con Modal -->
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#eliminarModal_{{ $image->id }}" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <!-- Modal de confirmación -->
                                    <div class="modal fade" id="eliminarModal_{{ $image->id }}" tabindex="-1" role="dialog" aria-labelledby="eliminarModalLabel_{{ $image->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="eliminarModalLabel_{{ $image->id }}">Eliminar Imagen</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Estás seguro de que quieres eliminar esta imagen del servicio {{ $servicio->nombre }}?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('serviciosImagen.destroy', ['servicio' => $servicio->id, 'image' => $image->id]) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
