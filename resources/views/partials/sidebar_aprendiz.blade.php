<link href="{{ asset('css/sidebar-styles.css') }}" rel="stylesheet">
<link href="{{ asset('css/notifications.css') }}" rel="stylesheet">

<!-- Sidebar Moderno -->
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
            </button>
            <div class="dropdown-menu" id="userDropdownMenu">
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user-cog"></i>
                    <span>Mi Perfil</span>
                </a>
                <a href="{{ asset('docs/manual.pdf') }}" target="_blank" class="dropdown-item">
                <i class="fas fa-question-circle"></i>
                <span>Ayuda</span>
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
                <a href="{{ route('aprendiz.unidades-disponibles') }}" 
                   class="nav-link {{ request()->routeIs('aprendiz.unidades-disponibles') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <span>Unidades Disponibles</span>
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
        
        <!-- Botón Toggle del Sidebar -->
        <button class="sidebar-toggle" onclick="toggleSidebar()" title="Colapsar/Expandir Sidebar">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Form de Logout (oculto) -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>