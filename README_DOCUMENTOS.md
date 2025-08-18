# Sistema de Revisión de Documentos - SENA Gestión Documental

## 📋 Descripción General

Este sistema permite a los **administradores** revisar, aprobar o rechazar los documentos subidos por los **aprendices** en el sistema de gestión documental del SENA.

## 🚀 Funcionalidades Principales

### Para Administradores:
- ✅ **Lista de Documentos**: Ver todos los documentos de aprendices de sus unidades
- 🔍 **Filtros Avanzados**: Filtrar por estado, unidad, aprendiz, fase
- 📊 **Estadísticas**: Dashboard con métricas de documentos
- 👁️ **Vista Detallada**: Revisar cada documento individualmente
- 📄 **Vista Previa**: Ver PDFs directamente en el navegador
- ⬇️ **Descarga**: Descargar documentos para revisión offline
- ✅ **Aprobación**: Aprobar documentos con comentarios opcionales
- ❌ **Rechazo**: Rechazar documentos con comentarios obligatorios
- ⏰ **Estado Intermedio**: Marcar documentos como "En Revisión"

### Para Aprendices:
- 📤 **Subida de Documentos**: Subir documentos por fase del proyecto
- 📋 **Seguimiento**: Ver estado de sus documentos
- 📊 **Progreso**: Monitorear avance en el proyecto

## 🏗️ Arquitectura del Sistema

### Modelos:
- **DocumentoAprendiz**: Documentos subidos por aprendices
- **User**: Usuarios del sistema (admin, aprendiz, superadmin)
- **UnidadProductiva**: Unidades productivas del SENA
- **Phase**: Fases del proyecto
- **TipoDocumento**: Tipos de documentos requeridos

### Controladores:
- **AdminDocumentoController**: Maneja la revisión de documentos
- **AprendizController**: Maneja la subida de documentos

### Vistas:
- **admin/documentos/index.blade.php**: Lista principal de documentos
- **admin/documentos/show.blade.php**: Vista detallada y acciones
- **admin/documentos/preview.blade.php**: Vista previa de PDFs

## 🛠️ Instalación y Configuración

### 1. Ejecutar Migraciones
```bash
php artisan migrate
```

### 2. Ejecutar Seeders
```bash
php artisan db:seed
```

### 3. Crear Enlace Simbólico para Storage
```bash
php artisan storage:link
```

### 4. Generar Documentos de Prueba (Opcional)
```bash
# Crear 10 documentos pendientes
php artisan simular:documentos-aprendiz --count=10 --estado=pendiente

# Crear 5 documentos en revisión
php artisan simular:documentos-aprendiz --count=5 --estado=en_revision

# Crear 3 documentos aprobados
php artisan simular:documentos-aprendiz --count=3 --estado=aprobado
```

## 📱 Uso del Sistema

### Acceso para Administradores:
1. Iniciar sesión con rol `admin`
2. Ir a **Revisar Documentos** en el sidebar
3. Ver lista de documentos pendientes de revisión
4. Usar filtros para encontrar documentos específicos
5. Hacer clic en **Ver detalles** para revisar un documento
6. Aprobar, rechazar o marcar como "En Revisión"

### Acceso para Aprendices:
1. Iniciar sesión con rol `aprendiz`
2. Ir a **Documentos Requeridos**
3. Seleccionar fase y subir documento
4. Ver estado de documentos subidos

## 🔧 Configuración Avanzada

### Tipos de Archivo Permitidos:
- PDF (`.pdf`)
- Word (`.doc`, `.docx`)
- Excel (`.xls`, `.xlsx`)
- PowerPoint (`.ppt`, `.pptx`)

### Límites de Archivo:
- Tamaño máximo: 10MB
- Almacenamiento: `storage/app/public/documentos/aprendiz/`

### Estados de Documentos:
- **Pendiente**: Documento subido, esperando revisión
- **En Revisión**: Administrador está revisando
- **Aprobado**: Documento aprobado
- **Rechazado**: Documento rechazado con comentarios

## 📊 Rutas del Sistema

### Rutas de Administrador:
```
GET  /admin/documentos                    # Lista de documentos
GET  /admin/documentos/{id}              # Ver documento
POST /admin/documentos/{id}/aprobar      # Aprobar documento
POST /admin/documentos/{id}/rechazar     # Rechazar documento
POST /admin/documentos/{id}/en-revision  # Marcar en revisión
GET  /admin/documentos/{id}/descargar    # Descargar documento
GET  /admin/documentos/{id}/preview      # Vista previa PDF
GET  /admin/documentos/estadisticas      # Estadísticas JSON
```

### Rutas de Aprendiz:
```
GET  /aprendiz/documentos-requeridos     # Ver documentos requeridos
POST /aprendiz/documentos-requeridos/subir # Subir documento
GET  /aprendiz/documentos/descargar/{id} # Descargar documento
```

## 🎨 Personalización

### Colores de Estados:
- **Pendiente**: `secondary` (gris)
- **En Revisión**: `warning` (amarillo)
- **Aprobado**: `success` (verde)
- **Rechazado**: `danger` (rojo)

### Iconos de Archivos:
- **PDF**: `fas fa-file-pdf` (rojo)
- **Word**: `fas fa-file-word` (azul)
- **Excel**: `fas fa-file-excel` (verde)
- **PowerPoint**: `fas fa-file-powerpoint` (naranja)

## 🔒 Seguridad

### Middleware:
- **Auth**: Usuario autenticado
- **Role**: Verificación de rol específico

### Permisos:
- Administradores solo pueden revisar documentos de sus unidades
- Aprendices solo pueden ver sus propios documentos
- Verificación de propiedad antes de cualquier acción

### Validaciones:
- Tipos de archivo permitidos
- Tamaño máximo de archivo
- Comentarios obligatorios para rechazo
- Verificación de relaciones de base de datos

## 📈 Monitoreo y Reportes

### Métricas Disponibles:
- Total de documentos por estado
- Documentos por unidad productiva
- Documentos por aprendiz
- Documentos subidos esta semana
- Tiempo promedio de revisión

### Dashboard del Administrador:
- Contador de documentos pendientes
- Gráficos de progreso
- Actividad reciente
- Tareas urgentes

## 🐛 Solución de Problemas

### Problemas Comunes:

#### 1. Documentos no se muestran:
- Verificar que el admin tenga unidades asignadas
- Verificar permisos de usuario
- Revisar logs de Laravel

#### 2. Error al subir archivos:
- Verificar permisos de directorio `storage/app/public`
- Verificar límites de PHP (`upload_max_filesize`, `post_max_size`)
- Verificar enlace simbólico de storage

#### 3. Vista previa de PDF no funciona:
- Verificar que el archivo existe
- Verificar permisos de archivo
- Verificar tipo MIME del archivo

### Logs y Debugging:
```bash
# Ver logs de Laravel
tail -f storage/logs/laravel.log

# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 🚀 Mejoras Futuras

### Funcionalidades Planificadas:
- [ ] Notificaciones por email
- [ ] Sistema de comentarios en tiempo real
- [ ] Historial de cambios detallado
- [ ] Exportación de reportes (PDF, Excel)
- [ ] API REST para integración externa
- [ ] Sistema de auditoría completo
- [ ] Backup automático de documentos
- [ ] Compresión automática de archivos

### Optimizaciones Técnicas:
- [ ] Cache de consultas frecuentes
- [ ] Paginación infinita
- [ ] Búsqueda en tiempo real
- [ ] Drag & drop para archivos
- [ ] Vista previa de más tipos de archivo

## 📞 Soporte

### Contacto:
- **Desarrollador**: Equipo de Desarrollo SENA
- **Email**: desarrollo@sena.edu.co
- **Documentación**: [Enlace a documentación completa]

### Repositorio:
- **GitHub**: [URL del repositorio]
- **Versión**: 2.0.1
- **Última actualización**: {{ date('d/m/Y') }}

---

**Nota**: Este sistema está diseñado específicamente para el SENA y debe ser usado en conjunto con el sistema principal de gestión documental.
