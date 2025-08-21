<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Administrador - SENA')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sidebar-styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/notifications.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <div id="app">
        <!-- Sidebar -->
        @include('partials.sidebar_admin')
        
        <!-- Main Content -->
        <div class="main-content-wrapper">
            @yield('content')
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/sidebar-functions.js') }}"></script>

    @stack('scripts')

    <style>
    /* Ajustes para el layout con sidebar fijo */
    .main-content-wrapper {
        margin-left: 280px; /* Mismo ancho que el sidebar */
        min-height: 100vh;
        background-color: #f8f9fa;
        padding: 0;
        width: calc(100% - 280px);
    }

    /* Responsive para pantallas pequeñas */
    @media (max-width: 768px) {
        .main-content-wrapper {
            margin-left: 0;
            width: 100%;
        }
    }
    </style>
</body>
</html>
