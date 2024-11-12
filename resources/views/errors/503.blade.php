
@extends('layouts.app')
@section('background_image')
{{ '' }}
@endsection

@section('title', 'Servicio No Disponible')

@section('content')
<div class="home-page">
    <h1>Oops!</h1>
    <h2>Error {{ $code }}</h2>
    <p>{{ $message }}</p> 
</div>
@endsection

