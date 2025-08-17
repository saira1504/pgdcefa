
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
