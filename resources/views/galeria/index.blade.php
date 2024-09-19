@extends('adminlte::page')

@section('title', 'Galería de Imágenes')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Galería</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <a href="{{ route('galeria.create') }}" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i> Añadir Imagen
            </a>

            @if(session('success'))
                <div class="alert alert-success mt-2">
                    {{ session('success') }}
                </div>
            @endif

            @if($images->isEmpty())
                <p>No hay imágenes en la galería.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Imagen</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($images as $image)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $image->imagen) }}" alt="Imagen de galería" style="width: 150px; height: auto; max-height: 100px; object-fit: cover;">
                                    </td>
                                    <td class="d-flex align-items-center">
                                        
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#eliminarModal_{{ $image->id }}" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>

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
                                                        <p>¿Realmente quieres eliminar esta imagen?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        <form action="{{ route('galeria.destroy', $image->id) }}" method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fin del modal -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Paginación --}}
            {{ $images->links() }}
        </div>
    </div>
@endsection
