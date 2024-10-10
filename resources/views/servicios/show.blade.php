@extends('layouts.app')

@section('background_image')
{{ '' }}
@endsection

@section('content')
<div class="container">
<nav aria-label="breadcrumb">
  <ol class="breadcrumb-custom">
    <li class="breadcrumb-item"><a href="{{ route('servicios.showServicios', ['categoriaN' => $categoriaN]) }}"> {{ ucfirst($categoriaN) }} </a></li>
    <li class="breadcrumb-item active" aria-current="page"> {{ $servicio->nombre }}</li>
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
            @foreach($images as $image)
                <div class="image-item">
                    <a href="{{ asset('storage/' . $image->path) }}" data-fancybox="gallery" data-caption="{{ $servicio->nombre }}">
                        <img src="{{ asset('storage/' . $image->path) }}" alt="Imagen del servicio" class="img-fluid clickable-image">
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
<style>
    .gallery-container {
        display: flex;
        flex-wrap: wrap; /* Permite que las imágenes se ajusten a la fila */
        justify-content: flex-start; /* Alinea las imágenes al inicio del contenedor */
        margin-top: 20px; /* Espacio superior */
        padding: 0; /* Elimina el padding del contenedor */
    }

    .image-item {
        margin: 15px; /* Espacio entre imágenes */
        flex: 1 1 calc(25% - 30px); /* Permite que las imágenes se distribuyan en filas según el espacio */
        box-sizing: border-box; /* Incluye el padding y el borde en el cálculo del ancho */
    }

    .image-item img {
        width: 100%; /* Hace que las imágenes ocupen el 100% del contenedor */
        height: auto; /* Mantiene la relación de aspecto */
        max-height: 300px; /* Altura máxima de las imágenes */
        object-fit: cover; /* Asegura que las imágenes se ajusten al contenedor */
    }
</style>


@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Fancybox.bind("[data-fancybox='gallery']", {
            Thumbs: false,
            Toolbar: true,
            loop: true,
        });
    });
</script>
@endsection
