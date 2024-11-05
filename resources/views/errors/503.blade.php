@extends('layouts.errores')

@section('title', 'Servicio No Disponible')

@section('content')
<div class="error-page">
<h1>{{ $code }}</h1> 
<p>{{ $message ??Estamos realizando tareas de mantenimiento. Por favor, vuelve más tarde.}}</p>
    <a href="{{ url('/') }}">Volver a la página de inicio</a>
</div>
@endsection
