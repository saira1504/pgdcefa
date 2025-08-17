<link href="{{ asset('css/sidebar-styles.css') }}" rel="stylesheet">

<div class="sidebar-modern">
    <!-- Header del Usuario -->
    <div class="user-section">
        <div class="user-avatar">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="user-info">
            <h4>{{ Auth::user()->name ?? 'admin' }}</h4>
            <span>Admistrador</span>
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
                <a href="#" 
                   class="nav-link {{ request()->routeIs('#') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
            </li>
            <!-- fases -->
            <li class="nav-item">
                <a href="#" 
                   class="nav-link {{ request()->routeIs('#') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>Lista</span>
                </a>
            </li>
          <li class="nav-item">
                <a href="#" 
                   class="nav-link {{ request()->routeIs('#') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>Archivos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" 
                   class="nav-link {{ request()->routeIs('#') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Unidades Productivas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" 
                   class="nav-link {{ request()->routeIs('#') ? 'active' : '' }}">
                    <i class="fas fa-flag"></i>
                    <span>Documentos</span>
                </a>
            </li>
             <li class="nav-item">
                <a href="#" 
                   class="nav-link {{ request()->routeIs('#') ? 'active' : '' }}">
                    <i class="fas fa-flag"></i>
                    <span>Resultados</span>
                </a>
            </li>
              <li class="nav-item">
                <a href="#" 
                   class="nav-link {{ request()->routeIs('#') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reportes</span>
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


<!-- JavaScript al final del body -->
<script src="{{ asset('js/sidebar-functions.js') }}"></script>