@extends('layouts.app')

@section('background_image')
{{ '' }}
@endsection

@section('content')
<div class="container">
<nav aria-label="breadcrumb">
    <ol class="breadcrumb-custom">
        <li class="breadcrumb-item">
            <a href="{{ route('servicios.showServicios', ['categoriaN' => $categoriaN]) }}">
                {{ ucfirst($categoriaN) }} 
            </a>
        </li>
        <li class="breadcrumb-item separator">
            <i class="fas fa-angle-double-right"></i>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            {{ $servicio->nombre }}
        </li>
    </ol>
</nav>

    <div class="card mb-4">
        <div class="row no-gutters">
            <div class="col-md-4">
                @if ($servicio->imagen)
                    <img src="{{ asset('storage/' . $servicio->imagen) }}" 
                         alt="Imagen del servicio" 
                         class="img-fluid" 
                         style="max-width: 100%; height: auto;">
                @else
                    <img src="ruta/a/imagen/placeholder.jpg" 
                         alt="Imagen no disponible" 
                         class="img-fluid" 
                         style="max-width: 100%; height: auto;">
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
        <!-- Galería de Imágenes con Fancybox -->
        <div class="row row-cols-2 row-cols-md-4 g-3">
            @foreach($images as $image)
                <div class="col">
                    <a href="{{ asset('storage/' . $image->path) }}" 
                       data-fancybox="gallery" 
                       data-caption="{{ $servicio->nombre }}">
                        <img src="{{ asset('storage/' . $image->path) }}" 
                             class="img-fluid rounded" 
                             alt="Imagen del servicio" 
                             style="object-fit: cover; width: 100%; height: 200px;">
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inicialización de Fancybox
        Fancybox.bind("[data-fancybox='gallery']", {
            Thumbs: true, 
            Toolbar: true,             loop: true,    
            animationEffect: "zoom",  
        });
    });
</script>
@endsection
