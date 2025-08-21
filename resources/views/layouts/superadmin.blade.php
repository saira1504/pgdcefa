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
    
    <style>
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f8fffe 0%, #e8f5e8 100%);
            color: #2d3748;
            line-height: 1.6;
        }
        
        html, body {
            height: 100%;
            overflow-y: auto !important;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: var(--gradient-primary);
            z-index: 1000;
            box-shadow: var(--shadow-medium);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto !important;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none;  /* IE y Edge */
        }

        .sidebar::-webkit-scrollbar {
            display: none; /* Chrome, Safari y Opera */
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
            opacity: 0.1;
        }
        
        .main-content {
            margin-left: 280px;
            width: calc(100% - 280px);
            min-height: 100vh;
            overflow-y: auto !important;
            position: relative;
            z-index: 1;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .content-wrapper {
            padding: 0;
            max-width: 100%;
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

        .card-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .card-modern:hover::before {
            transform: scaleX(1);
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

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, var(--accent-color), transparent);
            animation: rotate 4s linear infinite;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stats-card:hover::before {
            opacity: 0.1;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
            border-color: var(--secondary-color);
        }

        @keyframes rotate {
            100% { transform: rotate(360deg); }
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

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-modern:hover::before {
            left: 100%;
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

        .btn-success-modern {
            background: var(--gradient-secondary);
            color: var(--dark-green);
            box-shadow: var(--shadow-light);
        }

        .btn-outline-modern {
            background: transparent;
            border: 2px solid var(--secondary-color);
            color: var(--secondary-color);
        }

        .btn-outline-modern:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Modales mejorados */
        .modal-modern .modal-content {
            border: none;
            border-radius: 24px;
            box-shadow: var(--shadow-heavy);
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }

        .modal-modern .modal-header {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 2rem;
            position: relative;
        }

        .modal-modern .modal-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        }

        .modal-modern .modal-body {
            padding: 2rem;
        }

        .modal-modern .modal-footer {
            border: none;
            padding: 1.5rem 2rem 2rem;
            background: rgba(248, 250, 252, 0.5);
        }

        /* Formularios mejorados */
        .form-control-modern {
            border: 2px solid rgba(116, 198, 157, 0.2);
            border-radius: 12px;
            padding: 0.875rem 1.25rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control-modern:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(82, 183, 136, 0.25);
            background: white;
        }

        .form-label-modern {
            font-weight: 600;
            color: var(--dark-green);
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
            .content-wrapper {
                padding: 1.5rem;
            }
        }

        /* Efectos de glassmorphism */
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        /* Progress bars mejoradas */
        .progress-modern {
            height: 8px;
            border-radius: 10px;
            background: rgba(116, 198, 157, 0.2);
            overflow: hidden;
        }

        .progress-bar-modern {
            background: var(--gradient-primary);
            border-radius: 10px;
            position: relative;
            overflow: hidden;
        }

        .progress-bar-modern::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background-image: linear-gradient(
                -45deg,
                rgba(255, 255, 255, .2) 25%,
                transparent 25%,
                transparent 50%,
                rgba(255, 255, 255, .2) 50%,
                rgba(255, 255, 255, .2) 75%,
                transparent 75%,
                transparent
            );
            background-size: 50px 50px;
            animation: move 2s linear infinite;
        }

        @keyframes move {
            0% { background-position: 0 0; }
            100% { background-position: 50px 50px; }
        }

        /* Asegura que ningún overlay tape el sidebar */
        .modal-backdrop, .overlay, .backdrop {
            pointer-events: none !important;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        @include('partials.sidebar_superadmin')
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="content-wrapper animate-fade-in">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
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