<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Superadmin - Gestión Documental')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Sidebar Styles -->
    <link href="{{ asset('css/sidebar-styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/notifications.css') }}" rel="stylesheet">
    
    <style>
        /* Estilos específicos para superadmin (sin interferir con el sidebar) */
        :root {
            --primary-color: #2d5a27;
            --secondary-color: #52b788;
            --accent-color: #74c69d;
            --light-green: #b7e4c7;
            --dark-green: #1b4332;
            --gradient-primary: linear-gradient(135deg, #2d5a27 0%, #52b788 100%);
            --gradient-secondary: linear-gradient(135deg, #74c69d 0%, #b7e4c7 100%);
            --shadow-light: 0 2px 10px rgba(45, 90, 39, 0.1);
            --shadow-medium: 0 8px 30px rgba(45, 90, 39, 0.15);
            --shadow-heavy: 0 15px 40px rgba(45, 90, 39, 0.2);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f8fffe 0%, #e8f5e8 100%);
            color: #2d3748;
            line-height: 1.6;
        }
        
        /* Cards mejoradas */
        .card-modern {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: var(--shadow-light);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .card-modern:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-heavy);
            border-color: var(--accent-color);
        }

        /* Estadísticas mejoradas */
        .stats-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(116, 198, 157, 0.3);
            border-radius: 24px;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
            border-color: var(--secondary-color);
        }

        .stats-number {
            font-size: 3rem;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        /* Botones mejorados */
        .btn-modern {
            padding: 0.875rem 2rem;
            font-weight: 600;
            border-radius: 12px;
            border: none;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.875rem;
        }

        .btn-primary-modern {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-light);
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        /* Animaciones de entrada */
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        .animate-slide-up {
            animation: slideUp 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(30px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Espaciado general del contenido */
        .content-wrapper {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

       /* Alinear contenido principal a la derecha del sidebar fijo */
.main-content-wrapper {
    margin-left: 320px; /* desplazado más a la derecha */
    transition: margin-left .3s ease;
}

@media (max-width: 1200px) {
    .main-content-wrapper { margin-left: 280px; }
}

@media (max-width: 992px) {
    .main-content-wrapper { margin-left: 110px; }
}

@media (max-width: 576px) {
    .main-content-wrapper { margin-left: 0; }
}

/* Responsive para el contenido principal */
@media (max-width: 768px) {
    .content-wrapper {
        padding: 1.5rem;
    }
}

    </style>
    
    @stack('styles')
</head>
<body>
    <div id="app">
        <!-- Sidebar -->
        @include('partials.sidebar_superadmin')
        
        <!-- Main Content -->
        <div class="main-content-wrapper">
            <div class="content-wrapper animate-fade-in">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Sidebar Functions -->
    <script src="{{ asset('js/sidebar-functions.js') }}"></script>
    
    <script>
        // Animaciones al cargar
        document.addEventListener('DOMContentLoaded', function() {
            // Animar elementos al hacer scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-slide-up');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.card-modern, .stats-card').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>