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
        
        <!-- Icono de Notificaciones -->
        <div class="notifications-icon">
            <button class="btn btn-link text-white p-0" onclick="openNotificationsModal()">
                <i class="fas fa-bell fa-lg"></i>
                @php
                    $notificacionesNoLeidas = Auth::user()->unreadNotifications()->where('type', 'App\Notifications\DocumentoSubidoNotification')->count();
                @endphp
                @if($notificacionesNoLeidas > 0)
                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" style="font-size: 0.6rem;">
                        {{ $notificacionesNoLeidas > 99 ? '99+' : $notificacionesNoLeidas }}
                    </span>
                @endif
            </button>
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
                        <a href="{{ route('superadmin.documentos.index') }}" 
                           class="submenu-link {{ request()->routeIs('superadmin.documentos.*') ? 'active' : '' }}">
                            <i class="fas fa-folder-open"></i>
                            <span>Revisar Documentos de Aprendices</span>
                            @php
                                $documentosPendientes = \App\Models\DocumentoAprendiz::where('estado', 'pendiente')->count();
                            @endphp
                            @if($documentosPendientes > 0)
                                <span class="badge bg-warning text-dark ms-auto">{{ $documentosPendientes }}</span>
                            @endif
                        </a>
                    </li>
                  
                   
                </ul>
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

<!-- Modal de Notificaciones -->
<div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="notificationsModalLabel">
                    🔔 Notificaciones del Listado Maestro
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <span class="badge bg-primary">{{ Auth::user()->notifications()->where('type', 'App\Notifications\DocumentoSubidoNotification')->count() }} total</span>
                        <span class="badge bg-danger">{{ Auth::user()->unreadNotifications()->where('type', 'App\Notifications\DocumentoSubidoNotification')->count() }} no leídas</span>
                    </div>
                </div>
                
                <div id="notificationsList">
                    @php
                        $notificaciones = Auth::user()->notifications()->where('type', 'App\Notifications\DocumentoSubidoNotification')->orderBy('created_at', 'desc')->get();
                    @endphp
                    
                                            @if($notificaciones->count() > 0)
                            @foreach($notificaciones as $notif)
                                @php
                                    $data = $notif->data;
                                    $isUnread = $notif->read_at === null;
                                    
                                    // Obtener el documento para información actualizada
                                    $documento = \App\Models\ListadoMaestro::find($data['documento_id']);
                                @endphp
                                <div class="notification-item border rounded p-3 mb-3 {{ $isUnread ? 'border-primary bg-light' : 'border-secondary' }}" data-id="{{ $notif->id }}">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <span class="fs-4">📋</span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="mb-1 {{ $isUnread ? 'fw-bold text-primary' : '' }}">
                                                    📄 {{ $data['titulo'] }}
                                                </h6>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($data['fecha'])->diffForHumans() }}
                                                </small>
                                            </div>
                                            <p class="mb-2 text-dark">{{ $data['mensaje'] }}</p>
                                            
                                            @if($documento)
                                                <div class="bg-light p-2 rounded mb-2">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <small class="text-muted d-block">
                                                                <strong>📊 Proceso:</strong> {{ $documento->nombre_proceso }}
                                                            </small>
                                                            <small class="text-muted d-block">
                                                                <strong>👤 Responsable:</strong> {{ $documento->responsable }}
                                                            </small>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <small class="text-muted d-block">
                                                                <strong>📁 Documento:</strong> {{ $documento->nombre_documento }}
                                                            </small>
                                                            <small class="text-muted d-block">
                                                                <strong>📈 Estado:</strong> 
                                                                @if($documento->estado === 'pendiente')
                                                                    <span class="badge bg-warning text-dark">⏳ Pendiente</span>
                                                                @elseif($documento->estado === 'aprobado')
                                                                    <span class="badge bg-success">✅ Aprobado</span>
                                                                @else
                                                                    <span class="badge bg-danger">❌ Rechazado</span>
                                                                @endif
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <strong>🆔 ID:</strong> {{ $data['documento_id'] }}
                                                    @if($documento && $documento->creador)
                                                        | <strong>👨‍💼 Subido por:</strong> {{ $documento->creador->name }}
                                                    @elseif(isset($data['admin']))
                                                        | <strong>👨‍💼 Subido por:</strong> {{ $data['admin'] }}
                                                    @endif
                                                </small>
                                                @if($isUnread)
                                                    <span class="badge bg-warning text-dark">✨ Nueva</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay notificaciones</h5>
                            <p class="text-muted">Cuando se suban nuevos documentos, aparecerán aquí las notificaciones.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function openNotificationsModal() {
    const modal = new bootstrap.Modal(document.getElementById('notificationsModal'));
    modal.show();
}
</script>