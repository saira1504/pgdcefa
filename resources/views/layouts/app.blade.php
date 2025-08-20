<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .sidebar-collapsed {
            margin-left: -250px;
            transition: margin 0.3s;
        }
        .sidebar-expanded {
            margin-left: 0;
            transition: margin 0.3s;
        }
        .sidebar {
            min-width: 250px;
            max-width: 250px;
            height: 100vh;
            position: fixed;
            top: 56px;
            left: 0;
            z-index: 1030;
        }
        .main-content {
            margin-left: 250px;
            transition: margin 0.3s;
        }
        .main-content.full {
            margin-left: 0;
        }
        @media (max-width: 768px) {
            .sidebar {
                top: 56px;
                height: calc(100vh - 56px);
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            if(toggleBtn && sidebar && mainContent) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('sidebar-collapsed');
                    sidebar.classList.toggle('sidebar-expanded');
                    mainContent.classList.toggle('full');
                });
            }
        });
    </script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSS de Notificaciones -->
    <link href="{{ asset('css/notifications.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                @if(Auth::check())
                    <button class="btn btn-outline-secondary me-2" id="sidebarToggle" type="button">
                        <i class="fas fa-bars"></i>
                    </button>
                @endif
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                                         <!-- Right Side Of Navbar -->
                     <ul class="navbar-nav ms-auto">
                         <!-- Authentication Links -->
                         @guest
                             @if (Route::has('login'))
                                 <li class="nav-item">
                                     <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                 </li>
                             @endif
 
                             @if (Route::has('register'))
                                 <li class="nav-item">
                                     <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                 </li>
                             @endif
                                                  @else
 
                             <li class="nav-item dropdown">
                                 <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                     {{ Auth::user()->name }}
                                 </a>
 
                                 <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                     <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                      document.getElementById('logout-form').submit();">
                                         {{ __('Logout') }}
                                     </a>
 
                                     <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                         @csrf
                                     </form>
                                 </div>
                             </li>
                         @endguest
                     </ul>
                </div>
            </div>
        </nav>

        @if(Auth::check())
            <div>
                <div id="sidebar" class="sidebar sidebar-expanded bg-success text-white">
                    @if(Auth::user()->role === 'superadmin')
                        @include('partials.sidebar_superadmin')
                    @elseif(Auth::user()->role === 'admin')
                        @include('partials.sidebar_admin')
                    @elseif(Auth::user()->role === 'aprendiz')
                        @include('partials.sidebar_aprendiz')
                    @endif
                </div>
                <div id="mainContent" class="main-content">
                    <main class="py-4 flex-fill">
                        @yield('content')
                    </main>
                </div>
                

                         </div>
         @else
             <main class="py-4">
                 @yield('content')
             </main>
         @endif
           </div>
      

 
  </body>
  </html>
