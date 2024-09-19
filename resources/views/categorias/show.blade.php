@extends('layouts.app')

@section('title', 'Categoria')

@section('content')
<h2 class="services-title">Servicios</h2>

<div class="card-container">
    @foreach($categorias as $categoria)
        @if(is_object($categoria) && isset($categoria->nombre))
        <a href="{{ route('servicios.showServicios', $categoria->nombre) }}" class="card-link">
            <div class="card">
                @if (!empty($categoria->imagen))
                    <img src="{{ asset('storage/' . $categoria->imagen) }}" alt="{{ $categoria->nombre }}" class="card-img-top">
                @else
                    <img src="ruta/a/imagen/placeholder.jpg" alt="Imagen no disponible" class="card-img-top">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $categoria->nombre }}</h5>
                    <p class="card-text">{{ Str::limit($categoria->descripcion, 255) }}</p>
                </div>
            </div>
        </a>
        @else
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Información no disponible</h5>
                <p class="card-text">Los detalles de esta entrada no están disponibles.</p>
            </div>
        </div>
        @endif
    @endforeach
</div>

<style>
  .services-title {
    font-size: 2.5rem;
    text-align: center;
    margin-bottom: 2rem;
    color: #333;
    font-weight: bold;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
  }
  
  .card-container {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    justify-content: center;
    margin: 0 auto;
    max-width: 1000px;
    padding: 0 1rem;
  }

  .card-link {
    text-decoration: none;
    flex: 1 1 calc(50% - 2rem);
    max-width: calc(50% - 2rem);
    display: flex;
  }

  .card {
    display: flex;
    flex-direction: column;
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
    cursor: pointer;
    height: 400px; /* Ajusta la altura según tus necesidades */
  }

  .card:hover {
    transform: scale(1.03);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
  }

  .card-img-top {
    width: 100%;
    height: 200px;
    object-fit: cover;
  }

  .card-body {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 1rem;
  }

  .card-title {
    font-size: 1.5rem;
    color: #FF6EA2;
    margin-bottom: 0.75rem;
  }

  .card-text {
    font-size: 1rem;
    text-align: justify;
    color: #333;
  }
</style>
@endsection
