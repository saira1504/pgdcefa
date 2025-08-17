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
                <a href="#" 
                   class="nav-link {{ request()->routeIs('superadmin.reportes') ? 'active' : '' }}">
                    <i class="fas fa-flag"></i>
                    <span>Subsistemas</span>
                </a>
            </li>
             <li class="nav-item">
                <a href="{{ route('aprendiz.documentos-requeridos') }}" 
                   class="nav-link {{ request()->routeIs('aprendiz.documentos-requeridos') ? 'active' : '' }}">
                    <i class="fas fa-flag"></i>
                    <span>Documentos Requeridos</span>
                </a>
            </li>
              <li class="nav-item">
                <a href="{{ route('aprendiz.progreso') }}" 
                   class="nav-link {{ request()->routeIs('aprendiz.progreso') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Mi Progreso</span>
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
/* Variables CSS Limpias */
:root {
    --sidebar-bg: #2d5016;
    --sidebar-hover: #4a7c59;
    --sidebar-active: #1b4332;
    --text-primary: #ffffff;
    --text-secondary: rgba(255, 255, 255, 0.8);
    --border-color: rgba(255, 255, 255, 0.1);
    --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
}

/* Sidebar Principal */
.sidebar-modern {
    position: fixed;
    top: 0;
    left: 0;
    width: 280px;
    height: 100vh;
    background: var(--sidebar-bg);
    display: flex;
    flex-direction: column;
    z-index: 1000;
    box-shadow: var(--shadow);
}

/* Sección del Usuario */
.user-section {
    padding: 2rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    position: relative;
}

.user-avatar {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: var(--text-primary);
}

.user-info {
    text-align: center;
    margin-bottom: 1rem;
}

.user-info h4 {
    color: var(--text-primary);
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
}

.user-info span {
    color: var(--text-secondary);
    font-size: 0.85rem;
}

/* Dropdown del Usuario */
.user-dropdown {
    position: relative;
}

.dropdown-toggle {
    background: none;
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
    padding: 0.5rem;
    border-radius: 6px;
    cursor: pointer;
    transition: var(--transition);
    width: 100%;
}

.dropdown-toggle:hover {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #fff;
    border-radius: 8px;
    box-shadow: var(--shadow);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: var(--transition);
    z-index: 1001;
    margin-top: 0.5rem;
}

.dropdown-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    color: #333;
    text-decoration: none;
    transition: var(--transition);
}

.dropdown-item:hover {
    background: #f8f9fa;
    color: var(--sidebar-bg);
}

.dropdown-item.text-danger {
    color: #dc3545 !important;
}

.dropdown-divider {
    height: 1px;
    background: #e9ecef;
    margin: 0.5rem 0;
}

/* Navegación */
.sidebar-nav {
    flex: 1;
    padding: 1rem 0;
    overflow-y: auto;
}

.nav-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.nav-item {
    margin-bottom: 0.25rem;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    color: var(--text-secondary);
    text-decoration: none;
    transition: var(--transition);
    position: relative;
}

.nav-link:hover {
    background: var(--sidebar-hover);
    color: var(--text-primary);
}

.nav-link.active {
    background: var(--sidebar-active);
    color: var(--text-primary);
}

.nav-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #74c69d;
}

.nav-link i {
    width: 20px;
    text-align: center;
    font-size: 1.1rem;
}

.nav-link span {
    font-weight: 500;
}

/* Dropdown Arrows */
.dropdown-arrow {
    margin-left: auto;
    font-size: 0.8rem;
    transition: var(--transition);
}

.dropdown-arrow.rotated {
    transform: rotate(90deg);
}

/* Submenús */
.dropdown-submenu {
    list-style: none;
    margin: 0;
    padding: 0;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background: rgba(0, 0, 0, 0.1);
}

.dropdown-submenu.show {
    max-height: 300px;
}

.submenu-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.5rem 0.75rem 3rem;
    color: var(--text-secondary);
    text-decoration: none;
    transition: var(--transition);
    font-size: 0.9rem;
}

.submenu-link:hover {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
}

.submenu-link.active {
    background: var(--sidebar-active);
    color: var(--text-primary);
}

.submenu-link i {
    width: 16px;
    text-align: center;
    font-size: 0.9rem;
}

/* Footer del Sidebar */
.sidebar-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border-color);
}

.footer-info {
    text-align: center;
}

.footer-info small {
    display: block;
    color: var(--text-secondary);
    font-size: 0.75rem;
    line-height: 1.4;
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar-modern {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    
    .sidebar-modern.mobile-open {
        transform: translateX(0);
    }
}

/* Scrollbar personalizado */
.sidebar-nav::-webkit-scrollbar {
    width: 4px;
}

.sidebar-nav::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}
</style>

<script>
// Toggle del dropdown del usuario
function toggleUserDropdown() {
    const menu = document.getElementById('userDropdownMenu');
    menu.classList.toggle('show');
}

// Toggle de dropdowns de navegación
function toggleDropdown(id) {
    const dropdown = document.getElementById(id + '-dropdown');
    const arrow = document.getElementById(id + '-arrow');
    
    // Cerrar otros dropdowns
    document.querySelectorAll('.dropdown-submenu').forEach(menu => {
        if (menu.id !== id + '-dropdown') {
            menu.classList.remove('show');
        }
    });
    
    document.querySelectorAll('.dropdown-arrow').forEach(arr => {
        if (arr.id !== id + '-arrow') {
            arr.classList.remove('rotated');
        }
    });
    
    // Toggle del dropdown actual
    dropdown.classList.toggle('show');
    arrow.classList.toggle('rotated');
}

// Cerrar dropdowns al hacer clic fuera
document.addEventListener('click', function(event) {
    if (!event.target.closest('.user-dropdown')) {
        document.getElementById('userDropdownMenu').classList.remove('show');
    }
});

// Mantener dropdown abierto si hay una página activa
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si hay un submenu activo
    const activeSubmenu = document.querySelector('.submenu-link.active');
    if (activeSubmenu) {
        const parentDropdown = activeSubmenu.closest('.dropdown-submenu');
        const parentId = parentDropdown.id.replace('-dropdown', '');
        const arrow = document.getElementById(parentId + '-arrow');
        
        parentDropdown.classList.add('show');
        arrow.classList.add('rotated');
    }
});
</script>