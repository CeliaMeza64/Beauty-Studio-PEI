@extends('adminlte::page')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Categorías</li> 
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('categorias.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Crear Categoría
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
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->nombre }}</td>
                            <td>{{ Str::limit($categoria->descripcion, 50) }}</td>
                            <td>{{ $categoria->estado ? 'Activo' : 'Inactivo' }}</td>
                            <td>
                                @if ($categoria->imagen)
                                    <img src="{{ asset('storage/' . $categoria->imagen) }}" alt="Imagen" style="width: 50px; height: auto; max-height: 50px; object-fit: cover;">
                                @else
                                    No hay imagen
                                @endif
                            </td>
                            <td class="d-flex align-items-center">
                                <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-success mr-2" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#eliminarModal_{{ $categoria->id }}" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <div class="modal fade" id="eliminarModal_{{ $categoria->id }}" tabindex="-1" role="dialog" aria-labelledby="eliminarModalLabel_{{ $categoria->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="eliminarModalLabel_{{ $categoria->id }}">Eliminar Categoría</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>¿Realmente quieres eliminar la categoría {{ $categoria->nombre }}?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" style="display:inline-block;">
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
            {{ $categorias->links() }}
        </div>
    </div>
@endsection
