
// Toggle del sidebar (colapsar/expandir)
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar-modern');
    
    if (sidebar) {
        sidebar.classList.toggle('collapsed');
        
        // Detectar y ajustar automáticamente cualquier contenedor principal
        adjustMainContent(sidebar.classList.contains('collapsed'));
        
        // Guardar estado en localStorage
        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
        
        // Cambiar el ícono del botón
        const toggleIcon = document.querySelector('.sidebar-toggle i');
        if (toggleIcon) {
            toggleIcon.className = isCollapsed ? 'fas fa-bars' : 'fas fa-times';
        }
    }
}

// Función para ajustar automáticamente el contenido principal
function adjustMainContent(isCollapsed) {
    // Buscar diferentes tipos de contenedores principales
    const selectors = [
        '.main-content',
        '.main-content-wrapper', 
        '#mainContent',
        '.content-area',
        '.container-fluid',
        '.content',
        'main',
        '.test-content'
    ];
    
    selectors.forEach(selector => {
        const elements = document.querySelectorAll(selector);
        elements.forEach(element => {
            if (element && !element.closest('.sidebar-modern')) {
                if (isCollapsed) {
                    element.classList.add('sidebar-collapsed');
                    element.style.marginLeft = '70px';
                    element.style.width = 'calc(100% - 70px)';
                    element.style.maxWidth = 'calc(100vw - 70px)';
                } else {
                    element.classList.remove('sidebar-collapsed');
                    element.style.marginLeft = '280px';
                    element.style.width = 'calc(100% - 280px)';
                    element.style.maxWidth = 'calc(100vw - 280px)';
                }
            }
        });
    });
    
    // Ajustar el body si es necesario
    const body = document.body;
    if (body) {
        if (isCollapsed) {
            body.classList.add('sidebar-collapsed');
            body.classList.remove('sidebar-expanded');
        } else {
            body.classList.remove('sidebar-collapsed');
            body.classList.add('sidebar-expanded');
        }
    }
    
    // Debug: mostrar en consola qué contenedores se ajustaron
    console.log('Sidebar ajustado:', isCollapsed ? 'Colapsado' : 'Expandido');
    console.log('Contenedores encontrados:', selectors.map(s => document.querySelectorAll(s).length));
}

// Restaurar estado del sidebar al cargar la página
function restoreSidebarState() {
    const sidebar = document.querySelector('.sidebar-modern');
    const toggleIcon = document.querySelector('.sidebar-toggle i');
    
    if (sidebar) {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            adjustMainContent(true);
            if (toggleIcon) {
                toggleIcon.className = 'fas fa-bars';
            }
        } else {
            adjustMainContent(false);
        }
    }
}

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
    // Restaurar estado del sidebar
    restoreSidebarState();
    
    // Verificar si hay un submenu activo
    const activeSubmenu = document.querySelector('.submenu-link.active');
    if (activeSubmenu) {
        const parentDropdown = activeSubmenu.closest('.dropdown-submenu');
        const parentId = parentDropdown.id.replace('-dropdown', '');
        const arrow = document.getElementById(parentId + '-arrow');
        
        parentDropdown.classList.add('show');
        arrow.classList.add('rotated');
    }
    
    // Agregar tooltips a los enlaces del sidebar cuando esté colapsado
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        const text = link.querySelector('span');
        if (text) {
            link.setAttribute('data-title', text.textContent.trim());
        }
    });
    
    // Ajustar contenido principal inicialmente
    const sidebar = document.querySelector('.sidebar-modern');
    if (sidebar) {
        adjustMainContent(sidebar.classList.contains('collapsed'));
    }
});
