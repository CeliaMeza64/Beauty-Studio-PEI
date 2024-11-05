@extends('layouts.errores')

@section('title', 'Página no encontrada')

@section('content')
<div class="error-page">
    <h1>{{ $code }}</h1> 
    <p>{{ $message ?? 'Lo sentimos, la página que estás buscando no se encuentra.' }}</p> <!-- Mensaje de error -->
    <a href="{{ url('/') }}">Volver a la página de inicio</a>
</div>
@endsection
