@extends('layouts.app')
@section('background_image')
{{ '' }}
@endsection


@section('title', 'Página no encontrada')

@section('content')

<nav>
    @if(auth()->check())
        <span>{{ auth()->user()->name }}</span>
    @else
        <a href="{{ route('login') }}">Login</a>
    @endif
</nav>


<div class="home-page">
    <h1>Oops!</h1>
    <h2>Error {{ $code }}</h2>
    <p>{{ $message }}</p> 
</div>

@endsection