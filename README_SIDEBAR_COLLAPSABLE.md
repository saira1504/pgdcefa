# 🚀 Sidebar Colapsable - Guía de Implementación

## 📋 Descripción

Este proyecto implementa un **sidebar colapsable** que funciona como un acordeón, permitiendo a los usuarios expandir o contraer la barra lateral de navegación según sus necesidades. El sidebar se puede contraer para mostrar solo los iconos (ahorrando espacio) o expandir para mostrar iconos + texto completo.

## ✨ Características Principales

- **Toggle Button**: Botón hamburguesa (tres rayas) en el footer del sidebar
- **Estado Colapsado**: Solo muestra iconos (70px de ancho)
- **Estado Expandido**: Muestra iconos + texto (280px de ancho)
- **Tooltips**: Aparecen al hacer hover en estado colapsado
- **Persistencia**: Recuerda el estado entre sesiones (localStorage)
- **Responsive**: Se adapta automáticamente a dispositivos móviles
- **Transiciones Suaves**: Animaciones CSS optimizadas
- **Ajuste Automático**: El contenido principal se ajusta automáticamente

## 🎯 Cómo Funciona

### 1. **Botón Toggle**
- Ubicado en el **footer del sidebar** (no tapa información importante)
- Cambia entre íconos: `fa-times` (X) cuando está expandido, `fa-bars` (☰) cuando está colapsado
- Al hacer clic, alterna entre los dos estados
- **No interfiere** con la información del usuario o navegación

### 2. **Estados del Sidebar**

#### **Estado Expandido (280px)**
```
┌─────────────────────────────────────┐
│ 👤 Usuario Demo                    │
│    Administrador                   │
│    [▼] Dropdown                    │
├─────────────────────────────────────┤
│ 🏠 Inicio                          │
│ 📄 Documentos [2]                  │
│ 📋 Listado Maestro [1]             │
│ 🗂️ Áreas                          │
│ 🏢 Unidades Productivas            │
├─────────────────────────────────────┤
│ SENA - Gestión Documental          │
│ v2.0.1                             │
│ [X] Toggle Button                  │
└─────────────────────────────────────┘
```

#### **Estado Colapsado (70px)**
```
┌─────────┐
│ 👤      │
├─────────┤
│ 🏠      │
│ 📄      │
│ 📋      │
│ 🗂️      │
│ 🏢      │
├─────────┤
│ [☰]     │
└─────────┘
```

### 3. **Tooltips**
- Cuando el sidebar está colapsado, aparecen tooltips al hacer hover sobre los iconos
- Muestran el texto completo de cada opción de navegación
- Se posicionan a la derecha del sidebar

## 🛠️ Implementación Técnica

### **Archivos Modificados**

1. **`public/css/sidebar-styles.css`**
   - Variables CSS para anchos del sidebar
   - Estilos para el botón toggle
   - Estados colapsado/expandido
   - Tooltips y transiciones

2. **`public/js/sidebar-functions.js`**
   - Función `toggleSidebar()` para alternar estados
   - Función `restoreSidebarState()` para persistencia
   - Manejo de localStorage
   - Tooltips automáticos

3. **`resources/views/partials/sidebar_*.blade.php`**
   - Botón toggle agregado a todos los sidebars
   - Estructura HTML consistente

### **Variables CSS Clave**

```css
:root {
    --sidebar-width: 280px;           /* Ancho expandido */
    --sidebar-collapsed-width: 70px;  /* Ancho colapsado */
    --transition: all 0.3s ease;      /* Transiciones */
}
```

### **Clases CSS Principales**

- `.sidebar-modern` - Sidebar base
- `.sidebar-modern.collapsed` - Estado colapsado
- `.sidebar-toggle` - Botón toggle
- `.sidebar-collapsed` - Contenido principal ajustado

## 📱 Responsive Design

### **Desktop (>768px)**
- Sidebar siempre visible
- Toggle funcional
- Transiciones suaves

### **Mobile (≤768px)**
- Sidebar se oculta automáticamente
- Botón toggle se oculta
- Comportamiento nativo de móvil

## 🔧 Personalización

### **Cambiar Colores**
```css
:root {
    --sidebar-bg: #4a7c59;           /* Color de fondo */
    --sidebar-hover: #4a7c59;        /* Color hover */
    --sidebar-active: #1b4332;       /* Color activo */
    --text-primary: #ffffff;         /* Texto principal */
    --text-secondary: rgba(255, 255, 255, 0.8); /* Texto secundario */
}
```

### **Cambiar Anchos**
```css
:root {
    --sidebar-width: 300px;           /* Ancho expandido personalizado */
    --sidebar-collapsed-width: 80px;  /* Ancho colapsado personalizado */
}
```

### **Cambiar Transiciones**
```css
:root {
    --transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); /* Transición personalizada */
}
```

## 🚀 Uso en el Proyecto

### **1. Incluir CSS y JS**
```html
<link href="{{ asset('css/sidebar-styles.css') }}" rel="stylesheet">
<script src="{{ asset('js/sidebar-functions.js') }}"></script>
```

### **2. Estructura HTML del Sidebar**
```html
<div class="sidebar-modern">
    <!-- Botón Toggle -->
    <button class="sidebar-toggle" onclick="toggleSidebar()" title="Colapsar/Expandir Sidebar">
        <i class="fas fa-times"></i>
    </button>
    
    <!-- Contenido del Sidebar -->
    <div class="user-section">...</div>
    <nav class="sidebar-nav">...</nav>
    <div class="sidebar-footer">...</div>
</div>
```

### **3. Contenido Principal**
```html
<div class="main-content" id="mainContent">
    <!-- Tu contenido aquí -->
</div>
```

## 🎨 Demo Interactivo

Para ver el sidebar en acción, abre el archivo:
```
public/sidebar-demo.html
```

Este archivo muestra todas las funcionalidades del sidebar colapsable en un entorno de demostración.

## 🔍 Solución de Problemas

### **Sidebar no se colapsa**
- Verificar que el JavaScript esté cargado
- Revisar la consola del navegador para errores
- Confirmar que la función `toggleSidebar()` esté definida

### **Contenido principal no se ajusta**
- Asegurar que el contenido principal tenga la clase `.main-content`
- Verificar que el CSS esté aplicando los márgenes correctos
- Comprobar que las transiciones CSS estén funcionando

### **Tooltips no aparecen**
- Verificar que los enlaces tengan el atributo `data-title`
- Confirmar que el CSS de tooltips esté cargado
- Revisar que el sidebar esté en estado colapsado

## 📚 Recursos Adicionales

- **Font Awesome**: Para los iconos (ya incluido en el proyecto)
- **CSS Variables**: Para personalización fácil
- **localStorage API**: Para persistencia del estado
- **CSS Transitions**: Para animaciones suaves

## 🤝 Contribución

Para mejorar el sidebar colapsable:

1. Modifica los estilos en `sidebar-styles.css`
2. Actualiza la funcionalidad en `sidebar-functions.js`
3. Prueba en diferentes dispositivos y navegadores
4. Documenta los cambios en este README

---

**¡El sidebar colapsable está listo para usar! 🎉**

Para cualquier pregunta o problema, revisa la consola del navegador y los archivos de implementación.
