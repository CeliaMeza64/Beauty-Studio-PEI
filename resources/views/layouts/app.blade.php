<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Beauty Studio</title>
    <link rel="icon" href="{{ asset('imagenes/log.png') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('assets/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <script src="{{ asset('assets/js/jquery-3.5.1.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @yield('css')

    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #FF6EA2; 
        }
        .navbar-brand,
        .navbar-nav .nav-link {
            color: white !important; 
        }
        .navbar-toggler-icon {
            background-color: #FF6EA2; 
        }
        .image-container {
            width: 100%; 
            overflow: hidden;
            height: 50vh; 
        }
        .image-container img {
            width: 100%; 
            height: 100%;
            object-fit: cover; 
            display: block; 
        }
        .card-container {
            font-family: Arial, sans-serif; 
        }
        .card {
            background-color: #f5e3c3; 
            color: #FF6EA2; 
            border: 1px solid gold; 
            margin-bottom: 20px; 
            overflow: hidden; 
            transition: transform 0.5s ease;
        }
        .card:hover {
            transform: scale(1.02); 
            text-decoration: none; 
        }
        .card-text {
            color: black; 
            overflow: hidden; 
            text-overflow:ellipsis;
            text-align: justify;
            display:-webkit-box;
             -webkit-box-orient:vertical;
            text-decoration: none; 
        }
        .services-title {
            text-align: center; 
            color: black; 
            font-family: Arial, sans-serif;
            margin-top: 40px;
            margin-bottom: 20px; 
        }
        .card img {
            height: 200px; 
            object-fit: cover; 
            transition: transform 0.5s ease; 
        }
        .card:hover img {
            transform: scale(1.1); 
        }
        .card a {
            color: inherit; 
            text-decoration: none; 
        }
        
        img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .truncate {
            display: -webkit-box;
            -webkit-line-clamp: 3; 
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: justify;
            max-width: 460px; 
        }

        .table {
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .table tbody tr:last-child {
            border-bottom: 1px solid #ddd; 
        }

        .d-flex.align-items-center {
            justify-content: center;
            margin-bottom: 0;
            border-bottom: none;
        }

        .breadcrumb {
            padding: 0.2rem;
            margin: 0;
            border: none;
            background-color: transparent;
            border-radius: 0;
        }

        .breadcrumb-item {
            margin-right: 0.5rem;
        }

        .breadcrumb-item a {
            color: #007bff;
            text-decoration: none;
            font-size: 1.25rem; 
        }

        .breadcrumb-item.active {
            color: #6c757d;
        }

        .whatsapp-float {
            position: fixed;
            bottom: 20px; 
            right: 20px; 
            background-color: #25D366; 
            border-radius: 50%; 
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000; 
            width: 50px; 
            height: 50px; 
            display: flex; 
            align-items: center;
            justify-content: center;
        }

        .whatsapp-icon {
            width: 30px; 
            height: 30px; 
        }

        .tooltip-text {
            display: none;
        }

        .whatsapp-float:hover .tooltip-text {
            display: block;
            position: absolute;
            bottom: 100%;
            right: 50%;
            transform: translateX(50%);
            background-color: #333;
            color: #fff;
            padding: 5px;
            border-radius: 3px;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div id="app">
    <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">Beauty Studio</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        @foreach($categorias as $categoria)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('servicios.showServicios', $categoria->nombre) }}">{{ $categoria->nombre }}</a>
                        </li>
                        @endforeach
                        
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reservas.create') }}">Reserva</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('trends.show') }}">Tendencias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('galeria.show') }}">Galería</a>
                        </li>    
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            @if(Auth::check())
                                <a class="nav-link" href="{{ route('home') }}">{{ Auth::user()->name }}</a>
                            @else
                                <a class="nav-link" href="{{ route('home') }}">Login</a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        @section('background_image')
        <div class="image-container">
            <img src="{{ asset('imagenes/fondo3.png') }}" alt="Imagen horizontal" class="img-fluid">
        </div>
        @show

        <main class="py-4">
            @yield('content')
        </main>

        @unless(View::hasSection('hide-footer'))
            <footer class="footer mt-5 py-5" style="background: linear-gradient(135deg, #f9e3e3, #fce4ec); color: #333;">
                <div class="container text-center">
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <h5 class="text-uppercase mb-3" style="color: #FF6EA2;">Dirección</h5>
                            <p style="font-size: 1.1rem;">El Paraiso, El Paraiso Residencial Los Olivos 2 cuadra</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h5 class="text-uppercase mb-3" style="color: #FF6EA2;">Horario</h5>
                            <p style="font-size: 1.1rem;">Lunes a Domingo: 9:00 AM - 8:00 PM</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h5 class="text-uppercase mb-3" style="color: #FF6EA2;">Contacto</h5>
                            <p style="font-size: 1.1rem;">Tel: +504 8937-3440</p>
                            <p style="font-size: 1.1rem;">Email: info@beautystudio.com</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <p class="mb-0" style="font-size: 0.9rem; color: #555;">© 2024 Beauty Studio. </p>
                        </div>
                    </div>
                </div>
            </footer>
            <a href="https://wa.me/50489373440" class="whatsapp-float" target="_blank">
                <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" class="whatsapp-icon">
                <span class="tooltip-text">Escribenos</span>
            </a>
        @endunless
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.scroller.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.responsive.min.js') }}"></script>
    @stack('js')
</body>
</html>
