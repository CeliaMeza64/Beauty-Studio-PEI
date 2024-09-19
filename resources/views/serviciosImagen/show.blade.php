@extends('layouts.app')

@section('background_image')
{{ '' }}
@endsection

@section('content')
    <div class="container">
        <h4 class="my-4">Galería de Imágenes</h4>
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach($images as $index => $image)
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}" 
                            class="{{ $index === 0 ? 'active' : '' }}" aria-current="true" aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach($images as $index => $image)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $image->path) }}" class="d-block w-100" alt="Imagen del servicio">
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    </div>

    @push('styles')
        <style>
            /* Ajuste del carrusel */
            .carousel {
                max-width: 100%; /* Asegura que el carrusel no exceda el ancho del contenedor */
                margin: 0 auto; /* Centra el carrusel en su contenedor */
            }

            /* Ajuste de las imágenes dentro del carrusel */
            .carousel-inner img {
                width: 100%; /* La imagen ocupa el 100% del ancho del carrusel */
                height: auto; /* La altura se ajusta automáticamente para mantener la relación de aspecto */
            }

            /* Ajuste de los controles del carrusel */
            .carousel-control-prev, .carousel-control-next {
                width: 5%; /* Ajusta el ancho de los controles de navegación */
            }
        </style>
    @endpush
@stop
