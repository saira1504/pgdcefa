<link href="{{ asset('css/sidebar-styles.css') }}" rel="stylesheet">

<div class="sidebar-modern">
    <!-- Header del Usuario -->
    <div class="user-section">
        <div class="user-avatar">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="user-info">
            <h4>{{ Auth::user()->name ?? 'Superadmin' }}</h4>
            <span>Superadministrador</span>
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
            <!-- fases -->
            <li class="nav-item">
                <a href="{{ route('superadmin.phases.index') }}" 
                   class="nav-link {{ request()->routeIs('superadmin.phases.index') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>Fases</span>
                </a>
            </li>
 


            <!-- Unidades Productivas con Dropdown -->
            <li class="nav-item has-dropdown">
                <a href="#" class="nav-link dropdown-trigger" onclick="toggleDropdown('unidades')">
                    <i class="fas fa-industry"></i>
                    <span>Unidades Productivas</span>
                    <i class="fas fa-chevron-right dropdown-arrow" id="unidades-arrow"></i>
                </a>
                <ul class="dropdown-submenu" id="unidades-dropdown">
                    <li>
                        <a href="{{ route('superadmin.unidades-productivas.index') }}" 
                           class="submenu-link {{ request()->routeIs('superadmin.unidades-productivas.index') ? 'active' : '' }}">
                            <i class="fas fa-eye"></i>
                            <span>Ver Todas</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="submenu-link" data-bs-toggle="modal" data-bs-target="#createUnidadModal">
                            <i class="fas fa-plus"></i>
                            <span>Nueva Unidad</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="submenu-link">
                            <i class="fas fa-chart-bar"></i>
                            <span>Estadísticas</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="submenu-link">
                            <i class="fas fa-users"></i>
                            <span>Gestores</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Documentos con Dropdown -->
            <li class="nav-item has-dropdown">
                <a href="#" class="nav-link dropdown-trigger" onclick="toggleDropdown('documentos')">
                    <i class="fas fa-file-alt"></i>
                    <span>Documentos</span>
                    <i class="fas fa-chevron-right dropdown-arrow" id="documentos-arrow"></i>
                </a>
                <ul class="dropdown-submenu" id="documentos-dropdown">
                    <li>
                        <a href="#" 
                           class="submenu-link {{ request()->routeIs('superadmin.documentos') ? 'active' : '' }}">
                            <i class="fas fa-folder-open"></i>
                            <span>Ver Documentos</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="submenu-link" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Subir Documento</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="submenu-link">
                            <i class="fas fa-tags"></i>
                            <span>Categorías</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Resultados -->
            <li class="nav-item">
                <a href="#" 
                   class="nav-link {{ request()->routeIs('superadmin.resultados') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Resultados</span>
                </a>
            </li>

            <!-- Reportes -->
            <li class="nav-item">
                <a href="#" 
                   class="nav-link {{ request()->routeIs('superadmin.reportes') ? 'active' : '' }}">
                    <i class="fas fa-flag"></i>
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