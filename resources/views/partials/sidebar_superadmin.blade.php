<link href="{{ asset('css/sidebar-styles.css') }}" rel="stylesheet">
<link href="{{ asset('css/notifications.css') }}" rel="stylesheet">

<div class="sidebar-modern">
    <!-- Header del Usuario -->
    <div class="user-section">
        <div class="user-avatar">
            <i class="fas fa-user-crown"></i>
        </div>
        <div class="user-info">
            <h4>{{ Auth::user()->name ?? 'superadmin' }}</h4>
            <span>Super Administrador</span>
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
                <a href="{{ route('superadmin.dashboard') }}" 
                   class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
            </li>
            
            <!-- Fases -->
            <li class="nav-item">
                <a href="{{ route('superadmin.phases.index') }}" 
                   class="nav-link {{ request()->routeIs('superadmin.phases.index') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>Fases</span>
                </a>
            </li>

            <!-- Listado Maestro -->
            <li class="nav-item">
                <a href="{{ route('superadmin.listado_maestro.index') }}" 
                   class="nav-link {{ request()->routeIs('superadmin.listado_maestro.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Listado Maestro</span>
                    @php
                        $documentosPendientes = \App\Models\ListadoMaestro::where('estado', 'pendiente')->count();
                    @endphp
                    @if($documentosPendientes > 0)
                        <span class="badge bg-warning text-dark ms-auto">{{ $documentosPendientes }}</span>
                    @endif
                </a>
            </li>

            <!-- Unidades Productivas -->
            <li class="nav-item">
                <a href="{{ route('superadmin.unidades-productivas.index') }}" 
                   class="nav-link {{ request()->routeIs('superadmin.unidades-productivas.*') ? 'active' : '' }}">
                    <i class="fas fa-industry"></i>
                    <span>Unidades Productivas</span>
                </a>
            </li>

            <!-- Documentos -->
            <li class="nav-item">
                <a href="{{ route('superadmin.documentos.index') }}" 
                   class="nav-link {{ request()->routeIs('superadmin.documentos.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Documentos</span>
                    @php
                        $documentosPendientes = \App\Models\DocumentoAprendiz::where('estado', 'pendiente')->count();
                    @endphp
                    @if($documentosPendientes > 0)
                        <span class="badge bg-warning text-dark ms-auto">{{ $documentosPendientes }}</span>
                    @endif
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