<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SENA - Gestión Documental Empresarial</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-green: #2d5016;
            --secondary-green: #4a7c59;
            --accent-green: #6b9080;
            --light-green: #a4c3b2;
            --bg-green: #f0f7f4;
            --dark-text: #1a1a1a;
            --light-text: #666;
            --white: #ffffff;
            --shadow: 0 10px 30px rgba(0,0,0,0.1);
            --shadow-hover: 0 20px 40px rgba(0,0,0,0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--dark-text);
            overflow-x: hidden;
        }

        /* Header Superior */
        .top-bar {
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            color: white;
            padding: 8px 0;
            font-size: 14px;
        }

        .top-bar .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .social-links {
            display: flex;
            gap: 15px;
        }

        .social-links a {
            color: white;
            font-size: 18px;
            transition: all 0.3s ease;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
        }

        .social-links a:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }

        /* Header Principal */
        .main-header {
            background: var(--white);
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .logo-main {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 30px;
            box-shadow: var(--shadow);
        }

        .logo-text h1 {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary-green);
            line-height: 1;
        }

        .logo-text p {
            font-size: 1rem;
            color: var(--secondary-green);
            font-weight: 500;
        }

        .logo-secondary {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--accent-green), var(--light-green));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: var(--shadow);
        }

        .logo-secondary::before {
            content: '🌿';
            font-size: 35px;
        }

        .auth-section {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background: var(--bg-green);
            border-radius: 50px;
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .user-menu:hover {
            background: var(--primary-green);
            color: white;
            transform: translateY(-2px);
        }

        /* Navegación */
        .navigation {
            background: var(--bg-green);
            padding: 0;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-menu li {
            position: relative;
        }

        .nav-menu a {
            display: block;
            padding: 20px 30px;
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            background: var(--primary-green);
            color: white;
        }

        .nav-menu a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 3px;
            background: var(--accent-green);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-menu a:hover::after {
            width: 80%;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 50%, var(--accent-green) 100%);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,0 1000,0 1000,100 0,80"/></svg>');
            background-size: cover;
        }

        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .hero-content {
            animation: slideInLeft 1s ease-out;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 12px 25px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 25px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-highlight {
            color: var(--light-green);
            position: relative;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 40px;
            opacity: 0.95;
            line-height: 1.7;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 18px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: white;
            color: var(--primary-green);
            box-shadow: var(--shadow);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
            background: var(--bg-green);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: var(--primary-green);
            transform: translateY(-3px);
        }

        .hero-visual {
            position: relative;
            animation: slideInRight 1s ease-out;
        }

        .hero-card {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 40px;
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: var(--shadow);
        }

        .hero-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            display: block;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }

        /* Servicios Section */
        .services {
            padding: 120px 0;
            background: var(--white);
        }

        .services-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 80px;
        }

        .section-subtitle {
            color: var(--secondary-green);
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--primary-green);
            margin-bottom: 25px;
            line-height: 1.2;
        }

        .section-description {
            font-size: 1.2rem;
            color: var(--light-text);
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.7;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
        }

        .service-card {
            background: var(--white);
            border-radius: 25px;
            padding: 50px 40px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }

        .service-card:hover::before {
            left: 100%;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-hover);
        }

        .service-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            color: white;
            font-size: 40px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .service-card:hover .service-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .service-card h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-green);
            margin-bottom: 20px;
        }

        .service-card p {
            color: var(--light-text);
            line-height: 1.7;
            font-size: 16px;
        }

        /* Features Section */
        .features {
            padding: 120px 0;
            background: var(--bg-green);
        }

        .features-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .features-content h2 {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary-green);
            margin-bottom: 30px;
            line-height: 1.2;
        }

        .features-list {
            list-style: none;
            margin: 40px 0;
        }

        .features-list li {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px 0;
            border-bottom: 1px solid rgba(45, 80, 22, 0.1);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--secondary-green), var(--accent-green));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            flex-shrink: 0;
        }

        .feature-text {
            flex: 1;
        }

        .feature-text h4 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-green);
            margin-bottom: 5px;
        }

        .feature-text p {
            color: var(--light-text);
            font-size: 14px;
        }

        .features-visual {
            position: relative;
        }

        .features-image {
            width: 100%;
            height: 500px;
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 80px;
            box-shadow: var(--shadow-hover);
        }

        /* CTA Section */
        .cta {
            padding: 120px 0;
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            color: white;
            text-align: center;
        }

        .cta-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .cta h2 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 30px;
            line-height: 1.2;
        }

        .cta p {
            font-size: 1.3rem;
            margin-bottom: 50px;
            opacity: 0.95;
            line-height: 1.7;
        }

        /* Footer */
        .footer {
            background: var(--dark-text);
            color: white;
            padding: 80px 0 30px;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 60px;
            margin-bottom: 60px;
        }

        .footer-brand h3 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--light-green);
            margin-bottom: 20px;
        }

        .footer-brand p {
            color: #ccc;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .footer-section h4 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--light-green);
            margin-bottom: 25px;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 12px;
        }

        .footer-section ul li a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-section ul li a:hover {
            color: var(--light-green);
        }

        .footer-bottom {
            border-top: 1px solid #333;
            padding-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-bottom p {
            color: #999;
            font-size: 14px;
        }

        /* Animations */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .hero-container,
            .features-container {
                grid-template-columns: 1fr;
                gap: 40px;
                text-align: center;
            }
            
            .footer-content {
                grid-template-columns: 1fr 1fr;
                gap: 40px;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 20px;
            }
            
            .logo-section {
                flex-direction: column;
                gap: 20px;
            }
            
            .nav-menu {
                flex-direction: column;
                width: 100%;
            }
            
            .nav-menu a {
                padding: 15px 20px;
                text-align: center;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 2.5rem;
            }
            
            .services-grid {
                grid-template-columns: 1fr;
            }
            
            .hero-stats {
                grid-template-columns: 1fr;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div>
                <i class="fas fa-phone"></i> +57 (1) 234-5678 | 
                <i class="fas fa-envelope"></i> info@senagestiondocumental.edu.co
            </div>
            <div class="social-links">
                <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header">
        <div class="header-content">
            <div class="logo-section">
                <div class="logo-main">
                    <div class="logo-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="logo-text">
                        <h1>SENA EMPRESA</h1>
                        <p>Gestión Documental</p>
                    </div>
                </div>
                <div class="logo-secondary"></div>
            </div>
            
            <div class="auth-section">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="user-menu">
                            <i class="fas fa-user-circle"></i>
                            <span>Panel de Control</span>
                        </a>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Cerrar sesión
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="user-menu">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Iniciar Sesión</span>
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="navigation">
        <div class="nav-container">
            <ul class="nav-menu">
                <li><a href="#inicio" class="active">Inicio</a></li>
                <li><a href="#servicios">Servicios</a></li>
                <li><a href="#sistema">Sistema Integrado</a></li>
                <li><a href="#resultados">Resultados</a></li>
                <li><a href="#investigacion">Investigación</a></li>
                <li><a href="#egresados">Egresados</a></li>
                <li><a href="#contacto">Contacto</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="inicio">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-award"></i> SENA - Proyecto Formativo 
                </div>
                <h1>
                    Bienvenidos a la Estrategia
                    <span class="hero-highlight">"SENA Gestión Documental"</span>
                </h1>
                <p>
                    Transformamos la gestión documental empresarial a través de soluciones innovadoras, 
                    tecnología de vanguardia y formación especializada. Construyendo el futuro digital 
                    de las organizaciones colombianas.
                </p>
                <div class="hero-buttons">
                    <a href="#servicios" class="btn btn-primary">
                        <i class="fas fa-rocket"></i>
                        Explorar Servicios
                    </a>
                    <a href="#contacto" class="btn btn-secondary">
                        <i class="fas fa-phone"></i>
                        Contactar Ahora
                    </a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="hero-card">
                    <h3 style="margin-bottom: 20px; font-size: 1.5rem;">Impacto en Números</h3>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number">500+</span>
                            <span class="stat-label">Empresas Transformadas</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">50K+</span>
                            <span class="stat-label">Documentos Gestionados</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">99.9%</span>
                            <span class="stat-label">Disponibilidad</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">24/7</span>
                            <span class="stat-label">Soporte Técnico</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="servicios">
        <div class="services-container">
            <div class="section-header">
                <div class="section-subtitle">Nuestros Servicios</div>
                <h2 class="section-title">Soluciones Integrales de Gestión Documental</h2>
                <p class="section-description">
                    Ofrecemos un ecosistema completo de herramientas y servicios diseñados para 
                    revolucionar la manera en que tu organización gestiona su información.
                </p>
            </div>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <h3>Digitalización Avanzada</h3>
                    <p>
                        Convertimos tus archivos físicos en documentos digitales de alta calidad, 
                        con reconocimiento óptico de caracteres (OCR) y clasificación automática 
                        para una gestión eficiente.
                    </p>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Seguridad Empresarial</h3>
                    <p>
                        Protección de datos de nivel bancario con encriptación end-to-end, 
                        control de acceso granular y auditorías completas para garantizar 
                        la confidencialidad de tu información.
                    </p>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-search-plus"></i>
                    </div>
                    <h3>Búsqueda Inteligente</h3>
                    <p>
                        Motor de búsqueda avanzado con inteligencia artificial que permite 
                        encontrar cualquier documento en segundos, incluso dentro del contenido 
                        de archivos PDF e imágenes.
                    </p>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <h3>Flujos de Trabajo</h3>
                    <p>
                        Automatización de procesos documentales con workflows personalizables, 
                        aprobaciones digitales y notificaciones automáticas para optimizar 
                        la productividad organizacional.
                    </p>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Analítica y Reportes</h3>
                    <p>
                        Dashboard ejecutivo con métricas en tiempo real, reportes personalizados 
                        y análisis predictivo para la toma de decisiones basada en datos.
                    </p>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Capacitación Especializada</h3>
                    <p>
                        Programas de formación integral para tu equipo, certificaciones 
                        profesionales y soporte técnico continuo para maximizar el retorno 
                        de inversión.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="sistema">
        <div class="features-container">
            <div class="features-content">
                <h2>Sistema Integrado de Gestión Documental</h2>
                <p>
                    Nuestra plataforma unifica todos los aspectos de la gestión documental 
                    en una solución coherente y escalable, diseñada específicamente para 
                    las necesidades del entorno empresarial colombiano.
                </p>
                
                <ul class="features-list">
                    <li>
                        <div class="feature-icon">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Sincronización en Tiempo Real</h4>
                            <p>Acceso instantáneo desde cualquier dispositivo y ubicación</p>
                        </div>
                    </li>
                    <li>
                        <div class="feature-icon">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Colaboración Avanzada</h4>
                            <p>Herramientas de trabajo en equipo con control de versiones</p>
                        </div>
                    </li>
                    <li>
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Aplicación Móvil</h4>
                            <p>Gestión completa desde dispositivos móviles iOS y Android</p>
                        </div>
                    </li>
                    <li>
                        <div class="feature-icon">
                            <i class="fas fa-plug"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Integración API</h4>
                            <p>Conecta con tus sistemas existentes sin complicaciones</p>
                        </div>
                    </li>
                </ul>
                
                <a href="#contacto" class="btn btn-primary">
                    <i class="fas fa-play"></i>
                    Ver Demo en Vivo
                </a>
            </div>
            <div class="features-visual">
                <div class="features-image">
                    <i class="fas fa-laptop-code"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-container">
            <h2>¿Listo para Transformar tu Gestión Documental?</h2>
            <p>
                Únete a las empresas líderes que ya confían en nuestra plataforma. 
                Comienza tu transformación digital hoy mismo con una consultoría gratuita.
            </p>
            <div class="hero-buttons">
                <a href="#contacto" class="btn btn-primary">
                    <i class="fas fa-calendar-check"></i>
                    Agendar Consultoría Gratuita
                </a>
                <a href="tel:+5712345678" class="btn btn-secondary">
                    <i class="fas fa-phone"></i>
                    Llamar Ahora
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h3>SENA Gestión Documental</h3>
                    <p>
                        Lideramos la transformación digital empresarial a través de soluciones 
                        innovadoras de gestión documental, formación especializada y tecnología 
                        de vanguardia.
                    </p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Servicios</h4>
                    <ul>
                        <li><a href="#">Digitalización</a></li>
                        <li><a href="#">Almacenamiento</a></li>
                        <li><a href="#">Seguridad</a></li>
                        <li><a href="#">Automatización</a></li>
                        <li><a href="#">Capacitación</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Empresa</h4>
                    <ul>
                        <li><a href="#">Nosotros</a></li>
                        <li><a href="#">Equipo</a></li>
                        <li><a href="#">Carreras</a></li>
                        <li><a href="#">Noticias</a></li>
                        <li><a href="#">Contacto</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Soporte</h4>
                    <ul>
                        <li><a href="#">Centro de Ayuda</a></li>
                        <li><a href="#">Documentación</a></li>
                        <li><a href="#">Tutoriales</a></li>
                        <li><a href="#">API</a></li>
                        <li><a href="#">Estado del Sistema</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>© 2024 SENA Gestión Documental. Todos los derechos reservados.</p>
                <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} | PHP v{{ PHP_VERSION }}</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Active navigation highlighting
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-menu a');
            
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (scrollY >= (sectionTop - 200)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>