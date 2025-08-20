<link href="{{ asset('css/sidebar-styles.css') }}" rel="stylesheet">


<div class="sidebar-modern">
    <!-- Header del Usuario -->
    <div class="user-section">
        <div class="user-avatar">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="user-info">
            <h4>{{ Auth::user()->name ?? 'Aprendiz' }}</h4>
            <span>Aprendiz</span>
        </div>
        
        <!-- Dropdown del Usuario -->
        <div class="user-dropdown">
            <button class="dropdown-toggle" onclick="toggleUserDropdown()">
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="dropdown-menu" id="userDropdownMenu">
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user-cog"></i>
                    <span>Mi Perfil</span>
                </a>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="dropdown-item text-danger">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Navegación Principal -->
    <nav class="sidebar-nav">
        <ul class="nav-list">
            <!-- Inicio -->
            <li class="nav-item">
                <a href="{{ route('aprendiz.dashboard') }}" 
                   class="nav-link {{ request()->routeIs('aprendiz.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('aprendiz.mi-unidad') }}" 
                   class="nav-link {{ request()->routeIs('aprendiz.mi-unidad') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>Mi Unidad Productiva</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('aprendiz.phases.index') }}" 
                   class="nav-link {{ request()->routeIs('aprendiz.phases.index') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Fases</span>
                </a>
            </li>
             <li class="nav-item">
                <a href="{{ route('aprendiz.documentos-requeridos') }}" 
                   class="nav-link {{ request()->routeIs('aprendiz.documentos-requeridos') ? 'active' : '' }}">
                    <i class="fas fa-flag"></i>
                    <span>Documentos Requeridos</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Footer del Sidebar -->
    <div class="sidebar-footer">
        <div class="footer-info">
            <small>SENA - Gestión Documental</small>
            <small>v2.0.1</small>
        </div>
    </div>

    <!-- Form de Logout (oculto) -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>

<style>
/* SOLUCIÓN RÁPIDA PARA SIDEBAR QUE TAPA CONTENIDO */
/* Copia este CSS a tu archivo principal de estilos */

/* Forzar espacio para el sidebar */
body {
  padding-left: 320px !important;
  margin: 0 !important;
  transition: padding-left 0.3s ease;
}

/* Resetear márgenes del contenido principal */
.main-content,
.content-wrapper,
.container-fluid,
.content-area {
  margin-left: 0 !important;
  padding-left: 20px !important;
}

/* Responsive para móviles */
@media (max-width: 768px) {
  body {
    padding-left: 0 !important;
  }

  .main-content {
    padding-left: 10px !important;
  }
}

/* Si el sidebar tiene toggle, ajustar dinámicamente */
body.sidebar-collapsed {
  padding-left: 0 !important;
}

body.sidebar-expanded {
  padding-left: 320px !important;
}

/* Asegurar que el sidebar esté fijo */
.sidebar-container {
  position: fixed !important;
  left: 0 !important;
  top: 0 !important;
  width: 280px !important;
  height: 100vh !important;
  z-index: 1000 !important;
}
</style>


<!-- JavaScript al final del body -->
<script src="{{ asset('js/sidebar-functions.js') }}"></script>