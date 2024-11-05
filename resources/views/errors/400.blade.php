@extends('layouts.errores')

@section('title', 'Solicitud Incorrecta')

@section('content')
<div class="error-page">
<h1>{{ $code }}</h1> 
<p>{{ $message ??>Lo sentimos, tu solicitud no pudo ser procesada. Por favor, verifica y vuelve a intentarlo.}}</p>
    <a href="{{ url('/') }}">Volver a la página de inicio</a>
</div>
@endsection
