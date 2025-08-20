<!-- Icono de Notificaciones Reutilizable -->
<div class="notifications-icon-global">
    <button class="btn btn-link text-white p-0" onclick="openNotificationsModal()">
        <i class="fas fa-bell fa-lg"></i>
        @php
            $notificacionesNoLeidas = Auth::user()->unreadNotifications()->where('type', 'App\Notifications\DocumentoSubidoNotification')->count() + 
                                    Auth::user()->unreadNotifications()->where('type', 'App\Notifications\DocumentoProcesadoNotification')->count();
        @endphp
        @if($notificacionesNoLeidas > 0)
            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" style="font-size: 0.6rem;">
                {{ $notificacionesNoLeidas > 99 ? '99+' : $notificacionesNoLeidas }}
            </span>
        @endif
    </button>
</div>

<!-- Modal de Notificaciones Global -->
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
                        @php
                            $totalNotificaciones = Auth::user()->notifications()->whereIn('type', [
                                'App\Notifications\DocumentoSubidoNotification',
                                'App\Notifications\DocumentoProcesadoNotification'
                            ])->count();
                        @endphp
                        <span class="badge bg-primary">{{ $totalNotificaciones }} total</span>
                        <span class="badge bg-danger">{{ $notificacionesNoLeidas }} no leídas</span>
                    </div>
                </div>
                
                <div id="notificationsList">
                    @php
                        $notificaciones = Auth::user()->notifications()->whereIn('type', [
                            'App\Notifications\DocumentoSubidoNotification',
                            'App\Notifications\DocumentoProcesadoNotification'
                        ])->orderBy('created_at', 'desc')->get();
                    @endphp
                    
                    @if($notificaciones->count() > 0)
                        @foreach($notificaciones as $notif)
                            @php
                                $data = $notif->data;
                                $isUnread = $notif->read_at === null;
                                $isDocumentoSubido = $notif->type === 'App\Notifications\DocumentoSubidoNotification';
                                
                                if ($isDocumentoSubido) {
                                    $documento = \App\Models\ListadoMaestro::find($data['documento_id']);
                                } else {
                                    $documento = \App\Models\ListadoMaestro::find($data['documento_id']);
                                }
                            @endphp
                            
                            <div class="notification-item border rounded p-3 mb-3 {{ $isUnread ? 'border-primary bg-light' : 'border-secondary' }}" data-id="{{ $notif->id }}">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        @if($isDocumentoSubido)
                                            <span class="fs-4">📋</span>
                                        @else
                                            <span class="fs-4">{{ $data['accion'] === 'aprobado' ? '✅' : '❌' }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h6 class="mb-1 {{ $isUnread ? 'fw-bold text-primary' : '' }}">
                                                @if($isDocumentoSubido)
                                                    📄 {{ $data['titulo'] }}
                                                @else
                                                    {{ $data['titulo'] }}
                                                @endif
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
                                                @if($isDocumentoSubido)
                                                    @if($documento && $documento->creador)
                                                        | <strong>👨‍💼 Subido por:</strong> {{ $documento->creador->name }}
                                                    @elseif(isset($data['admin']))
                                                        | <strong>👨‍💼 Subido por:</strong> {{ $data['admin'] }}
                                                    @endif
                                                @else
                                                    | <strong>👨‍💼 Procesado por:</strong> {{ $data['superadmin'] }}
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
                            <p class="text-muted">Cuando se procesen documentos, aparecerán aquí las notificaciones.</p>
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
