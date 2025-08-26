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
            <h4>{{ Auth::user()->name ?? 'admin' }}</h4>
            <span>Admistrador</span>
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
                <a href="{{ route('admin.dashboard') }}" 
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <!-- Revisión de Documentos -->
            <li class="nav-item">
                <a href="{{ route('admin.documentos.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.documentos.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Revisar Documentos</span>
                    @php
                        $documentosPendientes = \App\Models\DocumentoAprendiz::whereHas('unidad', function($q) {
                            $q->where('admin_principal_id', Auth::id())
                              ->orWhereHas('admins', function($q2) {
                                  $q2->where('admin_id', Auth::id());
                              });
                        })->where('estado', 'pendiente')->count();
                    @endphp
                    @if($documentosPendientes > 0)
                        <span class="badge bg-warning text-dark ms-auto">{{ $documentosPendientes }}</span>
                    @endif
                </a>
            </li>
            
         

            <!-- 🚀 Listado Maestro -->
            <li class="nav-item">
                <a href="{{ route('admin.listado_maestro') }}" 
                   class="nav-link {{ request()->routeIs('admin.listado_maestro') ? 'active' : '' }}">
                    <i class="fas fa-list-alt"></i>
                    <span>Listado Maestro</span>
                </a>
            </li>

            <!-- Áreas (enlace a filtro dentro de Listado Maestro) -->
            <li class="nav-item">
                <a href="{{ route('admin.areas.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.areas.*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i>
                    <span>Áreas</span>
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

<!-- JavaScript al final del body -->
<script src="{{ asset('js/sidebar-functions.js') }}"></script>



