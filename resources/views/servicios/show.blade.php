@extends('layouts.app')

@section('background_image')
{{ '' }}
@endsection

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="first d-md-flex">
        <ol class="breadcrumb indigo lighten-6 first-1 shadow-lg mb-5">
            <li class="breadcrumb-item">
                <a class="black-text" href="{{ route('servicios.showServicios', ['categoriaN' => $categoriaN]) }}">
                    <i class="fas fa-tag"></i> {{ ucfirst($categoriaN) }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                    <i class="fas fa-tag"></i> {{ $servicio->nombre }}
                </a>
            </li>
        </ol>
    </nav>

    <div class="card mb-4">
        <div class="row no-gutters">
            <div class="col-md-4">
                @if ($servicio->imagen)
                    <img src="{{ asset('storage/' . $servicio->imagen) }}" alt="Imagen del servicio" class="img-fluid" style="max-width: 100%; height: auto;">
                @else
                    <img src="ruta/a/imagen/placeholder.jpg" alt="Imagen no disponible" class="img-fluid" style="max-width: 100%; height: auto;">
                @endif
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ $servicio->nombre }}</h5>
                    <p class="card-text">{!! nl2br(e($servicio->descripcion)) !!}</p>
                </div>
            </div>
        </div>
    </div>

   
    @if($images->isEmpty())
        <div class="col-12">
            <p class="text-muted">No hay imágenes disponibles para este servicio.</p>
        </div>
    @else
        <div class="gallery-container">
            <div class="image-row d-flex">
                @foreach($images as $image)
                    <div class="image-item">
                        <a href="{{ asset('storage/' . $image->path) }}" data-fancybox="gallery" data-caption="{{ $servicio->nombre }}">
                            <img src="{{ asset('storage/' . $image->path) }}" class="img-fluid clickable-image" alt="Imagen del servicio" style="max-height: 300px; object-fit: contain;">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@section('css')
<style>
    .container {
        margin-top: 50px;
    }

    .breadcrumb {
        padding: 25px;
        font-size: 14px;
        color: #aaa !important;
        letter-spacing: 2px;
        border-radius: 5px !important;
    }

    .gallery-container {
        overflow-x: auto;
        white-space: nowrap;
    }

    .image-row {
        display: flex;
        gap: 10px;
    }

    .image-item {
        flex: 0 0 auto;
        max-width: 300px;
    }

    .image-item img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        cursor: pointer;
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        Fancybox.bind("[data-fancybox='gallery']", {
            Thumbs: false,
            Toolbar: true,
            loop: true,
        });

        const galleryContainer = document.querySelector('.gallery-container');
        const imageRow = document.querySelector('.image-row');
        const imageCarousel = document.getElementById('imageCarousel');

        if (imageRow.scrollWidth > galleryContainer.clientWidth) {
            imageCarousel.classList.remove('d-none');
            galleryContainer.classList.add('d-none');
        }
    });
</script>
@endsection
