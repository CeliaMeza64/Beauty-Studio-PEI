@extends('adminlte::page')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Servicios</li> 
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('servicios.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear
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
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Disponibilidad</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($servicios as $servicio)
                            <tr>
                                <td>{{ ($servicios->currentPage() - 1) * $servicios->perPage() + $loop->iteration }}</td>
                                <td>{{ $servicio->nombre }}</td>
                                <td><div class="truncate">{{ $servicio->descripcion }}</div></td>
                                <td>{{ $servicio->categoria->nombre ?? 'No Asignada' }}</td>
                                <td>{{ $servicio->disponibilidad ? 'Disponible' : 'No Disponible' }}</td>
                                <td>
                                    @if ($servicio->imagen)
                                        <img src="{{ asset('storage/' . $servicio->imagen) }}" alt="Imagen" style="width: 50px; height: auto; max-height: 50px; object-fit: cover;">
                                    @else
                                        No hay imagen
                                    @endif
                                </td>
                                <td class="d-flex align-items-center">
                                    <a href="{{ route('servicios.edit', $servicio->id) }}" class="btn btn-success mr-2" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="{{ route('serviciosImagen.create', ['servicio' => $servicio->id]) }}" class="btn btn-primary mr-2" title="Agregar imágenes"> 
                                        <i class="fas fa-plus"></i> Imágenes
                                    </a>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#eliminarModal_{{ $servicio->id }}" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <!-- Modal de confirmación -->
                                    <div class="modal fade" id="eliminarModal_{{ $servicio->id }}" tabindex="-1" role="dialog" aria-labelledby="eliminarModalLabel_{{ $servicio->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="eliminarModalLabel_{{ $servicio->id }}">Eliminar servicio</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Realmente quieres eliminar el servicio {{ $servicio->nombre }}?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" style="display:inline-block;">
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
    {{ $servicios->links() }}
@endsection
