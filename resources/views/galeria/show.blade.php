@extends('layouts.app')

@section('title', 'Galería de Imágenes')
@section('background_image')
{{''}}
@endsection

@section('content')
    <h1>Galería </h1>
    @if(Auth::check() && Auth::user()->role == 'admin')
        <a href="{{ route('galeria.index') }}" class="btn btn-primary mb-3">Volver a la galería</a>
    @endif

    <div class="row">
        @forelse ($images as $image)
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card" data-bs-toggle="modal" data-bs-target="#imageModal-{{ $image->id }}">
                    <img src="{{ asset('storage/' . $image->imagen) }}" class="card-img-top" alt="Imagen de galería" style="height: 250px; object-fit: cover;">
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="imageModal-{{ $image->id }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $image->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="imageModalLabel{{ $image->id }}"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <img src="{{ asset('storage/' . $image->imagen) }}" class="img-fluid mb-3" alt="Imagen de galería">
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>Lo sentimos, no hay imágenes disponibles todavía.</p>
        @endforelse
    </div>

    <style>
        .card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .card-img-top {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .modal-body img {
            max-width: 100%;
            height: auto;
            margin-bottom: 1rem;
        }
    </style>
@endsection
