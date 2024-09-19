@extends('adminlte::page')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Tendencias</li> 
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('trends.create') }}" class="btn btn-primary">
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
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trends as $trend)
                            <tr>
                                <td>{{ ($trends->currentPage() - 1) * $trends->perPage() + $loop->iteration }}</td>
                                <td>{{ $trend->title }}</td>
                                <td class="truncate">{{ $trend->description }}</td>
                                <td>
                                    @if ($trend->image && $trend->image !== 'noimage.jpg')
                                        <img src="{{ asset('storage/trends_images/' . $trend->image) }}" alt="Imagen" style="width: 50px;">
                                    @else
                                        No hay imagen
                                    @endif
                                </td>
                                <td class="d-flex align-items-center">
                                    <a href="{{ route('trends.edit', $trend->id) }}" class="btn btn-success mr-2" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Botón para abrir el modal -->
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#eliminarModal_{{ $trend->id }}" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <!-- Modal para confirmar la eliminación -->
                                    <div class="modal fade" id="eliminarModal_{{ $trend->id }}" tabindex="-1" role="dialog" aria-labelledby="eliminarModalLabel_{{ $trend->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="eliminarModalLabel_{{ $trend->id }}">Eliminar tendencia</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Realmente quieres eliminar la tendencia {{ $trend->title }}?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('trends.destroy', $trend->id) }}" method="POST" style="display:inline-block;">
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

    {{ $trends->links() }}
@stop

@section('css')
    <style>
        .breadcrumb-item a, 
        .breadcrumb-item.active {
            font-size: 1.30em; 
        }
        .truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 350px; 
        }
    </style>
@stop
