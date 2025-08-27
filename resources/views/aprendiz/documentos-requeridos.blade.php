@extends('layouts.aprendiz')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR COMPLETAMENTE REMOVIDO -->
        
        <!-- CONTENIDO PRINCIPAL OCUPA TODO EL ANCHO -->
        <div class="col-12 p-4">
            <!-- Header moderno y elegante CON RELOJ -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1 text-success">
                        <i class="fas fa-file-upload me-2"></i>Documentos Requeridos
                    </h1>
                    <p class="text-muted small mb-0">Gestiona y sube los documentos requeridos para tu proyecto productivo</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <!-- ⏰ RELOJ EN TIEMPO REAL VISIBLE -->
                    <div class="text-end me-3 p-2 bg-light rounded">
                        <div class="fw-bold text-success" id="currentTime" style="font-size: 1rem;">
                            <i class="fas fa-clock me-1"></i>
                            <span id="timeDisplay">{{ now()->format('H:i:s') }}</span>
                        </div>
                        <small class="text-muted" id="currentDate" style="font-size: 0.8rem;">{{ now()->format('d/m/Y') }}</small>
                    </div>
                    <span class="badge bg-warning fs-6">
                        <i class="fas fa-exclamation-circle me-1"></i><span id="totalRequeridos">{{ $documentosRequeridos->count() }}</span> Requeridos
                    </span>
                    <span class="badge bg-info fs-6">
                        <i class="fas fa-building me-1"></i>{{ $todasLasUnidades->count() }} Unidades
                    </span>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalSubirDocumento">
                        <i class="fas fa-cloud-upload-alt me-1"></i>Subir Documento
                    </button>
                </div>
            </div>

            <!-- Breadcrumb moderno -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-light rounded-pill px-3 py-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('aprendiz.dashboard') }}" class="text-success text-decoration-none">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-muted">Documentos Requeridos</li>
                </ol>
            </nav>

            <!-- Alertas modernas -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" 
                 style="background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1)); border-left: 4px solid #28a745 !important;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                    <div>
                        <h6 class="mb-1 text-success fw-bold">¡Documento subido exitosamente!</h6>
                        <p class="mb-0 text-muted">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" 
                 style="background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(255, 107, 107, 0.1)); border-left: 4px solid #dc3545 !important;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x text-danger me-3"></i>
                    <div>
                        <h6 class="mb-1 text-danger fw-bold">Error al subir documento</h6>
                        <p class="mb-0 text-muted">{{ session('error') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- RESUMEN AL INICIO - Estadísticas modernas CON MÁS ESPACIO -->
            @if($documentosRequeridos->count() > 0)
            <div class="row mb-4">
                <div class="col-xl-3 col-lg-6 col-md-6 mb-2">
                    <div class="card border-0 shadow-sm" style="height: 80px; background: linear-gradient(135deg, #ffc107, #ffeb3b);">
                        <div class="card-body p-3 d-flex align-items-center text-white">
                            <div class="me-3">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-0 fw-bold" id="pendientesCount">{{ $documentosRequeridos->where('estado', 'pendiente')->count() }}</h3>
                                <small class="opacity-75">Pendientes</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 mb-2">
                    <div class="card border-0 shadow-sm" style="height: 80px; background: linear-gradient(135deg, #17a2b8, #20c997);">
                        <div class="card-body p-3 d-flex align-items-center text-white">
                            <div class="me-3">
                                <i class="fas fa-eye fa-2x"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-0 fw-bold" id="revisionCount">{{ $documentosRequeridos->whereIn('estado', ['subido', 'en_revision'])->count() }}</h3>
                                <small class="opacity-75">En Revisión</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 mb-2">
                    <div class="card border-0 shadow-sm" style="height: 80px; background: linear-gradient(135deg, #28a745, #20c997);">
                        <div class="card-body p-3 d-flex align-items-center text-white">
                            <div class="me-3">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-0 fw-bold" id="aprobadosCount">{{ $documentosRequeridos->where('estado', 'aprobado')->count() }}</h3>
                                <small class="opacity-75">Aprobados</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 mb-2">
                    <div class="card border-0 shadow-sm" style="height: 80px; background: linear-gradient(135deg, #dc3545, #fd7e14);">
                        <div class="card-body p-3 d-flex align-items-center text-white">
                            <div class="me-3">
                                <i class="fas fa-times-circle fa-2x"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-0 fw-bold" id="rechazadosCount">{{ $documentosRequeridos->where('estado', 'rechazado')->count() }}</h3>
                                <small class="opacity-75">Rechazados</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Filtros modernos FUNCIONALES CON MÁS ESPACIO -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-filter me-2 text-success"></i>Filtros de Búsqueda
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-layer-group text-success me-1"></i>Filtrar por Fase
                            </label>
                            <select class="form-select" id="filtroFase">
                                <option value="">📋 Todas las fases</option>
                                @foreach($fases as $fase)
                                    <option value="{{ $fase->id }}">🔢 Fase {{ $fase->numero }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-building text-success me-1"></i>Filtrar por Unidad
                            </label>
                            <select class="form-select" id="filtroUnidad">
                                <option value="">🏢 Todas las unidades</option>
                                @foreach($todasLasUnidades as $unidad)
                                    <option value="{{ $unidad->id }}">{{ $unidad->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-filter text-success me-1"></i>Filtrar por Estado
                            </label>
                            <select class="form-select" id="filtroEstado">
                                <option value="">⚡ Todos los estados</option>
                                <option value="pendiente">⏳ Pendiente</option>
                                <option value="subido">👁 En Revisión</option>
                                <option value="en_revision">👁 En Revisión</option>
                                <option value="aprobado">✅ Aprobado</option>
                                <option value="rechazado">❌ Rechazado</option>
                            </select>
                        </div>
                        <div class="col-xl-3 col-lg-12 col-md-6">
                            <label class="form-label fw-semibold text-transparent">.</label>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary flex-grow-1" id="limpiarFiltros">
                                    <i class="fas fa-eraser me-1"></i>Limpiar Filtros
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <span class="ms-3 text-muted small" id="resultadosCount">
                                Mostrando todos los documentos
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documentos Enviados en esta sesión -->
            @if(!empty($documentosEnviados))
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header text-white py-3" 
                     style="background: linear-gradient(135deg, #17a2b8, #20c997);">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-paper-plane me-2"></i>Documentos Enviados en esta Sesión
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="fw-semibold">Fase</th>
                                    <th class="fw-semibold">Unidad Productiva</th>
                                    <th class="fw-semibold">Descripción</th>
                                    <th class="fw-semibold">Archivo</th>
                                    <th class="fw-semibold">Fecha de Envío</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documentosEnviados as $doc)
                                <tr>
                                    <td>
                                        @php
                                            $fase = $fases->firstWhere('id', $doc['fase_id']);
                                        @endphp
                                        <span class="badge bg-success">
                                            Fase {{ $fase ? $fase->numero : $doc['fase_id'] }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">
                                        @php
                                            $unidadNombre = 'N/A';
                                            if (!empty($doc['unidad_id'])) {
                                                $u = $todasLasUnidades->firstWhere('id', $doc['unidad_id']);
                                                $unidadNombre = $u ? $u->nombre : 'N/A';
                                            } elseif(isset($unidadAsignada) && $unidadAsignada) {
                                                $unidadNombre = $unidadAsignada->nombre ?? 'N/A';
                                            }
                                        @endphp
                                        {{ $unidadNombre }}
                                    </td>
                                    <td>{{ $doc['descripcion'] }}</td>
                                    <td>
                                        <i class="fas fa-file-alt text-primary me-1"></i>
                                        {{ $doc['archivo_original'] }}
                                    </td>
                                    <td class="text-muted">
                                        @php
                                            // 🔧 MANEJO ROBUSTO DE FECHAS - SOLUCIÓN AL ERROR DE CARBON
                                            try {
                                                if (is_string($doc['fecha_subida'])) {
                                                    // Si es string, intentar diferentes formatos
                                                    if (strpos($doc['fecha_subida'], '/') !== false) {
                                                        // Formato d/m/Y H:i o d/m/Y H:i:s
                                                        if (substr_count($doc['fecha_subida'], ':') === 1) {
                                                            // Formato d/m/Y H:i
                                                            $fecha = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $doc['fecha_subida']);
                                                        } else {
                                                            // Formato d/m/Y H:i:s
                                                            $fecha = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $doc['fecha_subida']);
                                                        }
                                                    } else {
                                                        // Formato estándar Y-m-d H:i:s
                                                        $fecha = \Carbon\Carbon::parse($doc['fecha_subida']);
                                                    }
                                                } elseif ($doc['fecha_subida'] instanceof \Carbon\Carbon) {
                                                    // Si ya es Carbon
                                                    $fecha = $doc['fecha_subida'];
                                                } elseif ($doc['fecha_subida'] instanceof \DateTime) {
                                                    // Si es DateTime
                                                    $fecha = \Carbon\Carbon::instance($doc['fecha_subida']);
                                                } else {
                                                    // Intentar parsear como sea
                                                    $fecha = \Carbon\Carbon::parse($doc['fecha_subida']);
                                                }
                                            } catch (\Exception $e) {
                                                // Fallback: mostrar la fecha tal como viene
                                                $fecha = null;
                                            }
                                        @endphp
            
                                        @if($fecha)
                                            <i class="fas fa-calendar-alt me-1 text-success"></i>
                                            {{ $fecha->format('d/m/Y') }}
                                            <br>
                                            <small class="text-primary">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $fecha->format('H:i') }}
                                            </small>
                                        @else
                                            <i class="fas fa-calendar-alt me-1 text-success"></i>
                                            {{ $doc['fecha_subida'] }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Documentos Requeridos por Fase - Grid moderno CON MÁS COLUMNAS -->
            @foreach($fases as $fase)
                @php
                    $documentosFase = $documentosRequeridos->where('tipo_documento_id', $fase->id);
                    $esFaseActual = $faseActual && $faseActual->id == $fase->id;
                @endphp
                
                @if($documentosFase->count() > 0)
                <div class="card shadow-sm mb-4 border-0 fase-card {{ $esFaseActual ? 'border-success border-3' : '' }}" 
                     data-fase="{{ $fase->id }}" 
                     data-fase-numero="{{ $fase->numero }}">
                    <div class="card-header text-white py-4" 
                         style="background: linear-gradient(135deg, {{ $esFaseActual ? '#28a745, #20c997' : '#6c757d, #adb5bd' }});">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-layer-group fa-2x me-3"></i>
                                <div>
                                    <h5 class="mb-0 fw-bold">Fase {{ $fase->numero }}</h5>
                                    <small class="opacity-75">{{ $fase->nombre ?? 'Documentos de la fase' }}</small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                @if($esFaseActual)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-star me-1"></i>Fase Actual
                                    </span>
                                @endif
                                <span class="badge bg-light text-dark">
                                    <span class="documentos-count">{{ $documentosFase->count() }}</span> documentos
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <!-- GRID CON MÁS COLUMNAS PARA APROVECHAR EL ESPACIO COMPLETO -->
                        <div class="row g-4">
                            @foreach($documentosFase as $doc)
                            <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 documento-item" 
                                 data-estado="{{ $doc->estado ?? 'pendiente' }}" 
                                 data-fase="{{ $fase->id }}"
                                 data-unidad="{{ $doc->unidad_id ?? '' }}"
                                 data-nombre="{{ strtolower($doc->titulo ?? $doc->nombre) }}">
                                <div class="card h-100 border-0 shadow-sm">
                                    @php
                                        $estadoConfig = match($doc->estado ?? 'pendiente') {
                                            'aprobado' => ['gradient' => 'linear-gradient(135deg, #28a745, #20c997)', 'icon' => 'check-circle', 'text' => 'Aprobado'],
                                            'rechazado' => ['gradient' => 'linear-gradient(135deg, #dc3545, #fd7e14)', 'icon' => 'times-circle', 'text' => 'Rechazado'],
                                            'subido', 'en_revision' => ['gradient' => 'linear-gradient(135deg, #17a2b8, #20c997)', 'icon' => 'eye', 'text' => 'En Revisión'],
                                            default => ['gradient' => 'linear-gradient(135deg, #ffc107, #ffeb3b)', 'icon' => 'clock', 'text' => 'Pendiente']
                                        };
                                    @endphp
                                    
                                    <div class="card-header text-white py-3 border-0" 
                                         style="background: {{ $estadoConfig['gradient'] }};">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-bold">{{ $doc->titulo ?? $doc->nombre }}</h6>
                                            <i class="fas fa-{{ $estadoConfig['icon'] }} fa-lg"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="card-body p-3">
                                        <p class="text-muted small mb-3">{{ $doc->descripcion ?? 'Sin descripción disponible' }}</p>
                                        
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <small class="text-muted fw-semibold">Estado:</small>
                                                <span class="badge" style="background: {{ $estadoConfig['gradient'] }};">
                                                    <i class="fas fa-{{ $estadoConfig['icon'] }} me-1"></i>{{ $estadoConfig['text'] }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Comentarios del Revisor -->
                                        @if($doc->estado == 'aprobado' && $doc->comentarios_aprobacion)
                                            <div class="mb-3">
                                                <div class="alert alert-success py-2 mb-0">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    <strong>Comentarios de Aprobación:</strong><br>
                                                    {{ $doc->comentarios_aprobacion }}
                                                </div>
                                            </div>
                                        @elseif($doc->estado == 'rechazado' && $doc->comentarios_rechazo)
                                            <div class="mb-3">
                                                <div class="alert alert-danger py-2 mb-0">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    <strong>Motivo del Rechazo:</strong><br>
                                                    {{ $doc->comentarios_rechazo }}
                                                </div>
                                            </div>
                                        @elseif($doc->estado == 'en_revision')
                                            <div class="mb-3">
                                                <div class="alert alert-warning py-2 mb-0">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <strong>En revisión</strong><br>
                                                    Tu documento está siendo revisado por el administrador.
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <!-- Información del Revisor -->
                                        @if($doc->revisor)
                                            <div class="mb-3">
                                                <small class="text-muted fw-semibold">Revisado por:</small>
                                                <div class="text-info">
                                                    <i class="fas fa-user-check me-1"></i>
                                                    {{ $doc->revisor->name }}
                                                    @if($doc->fecha_revision)
                                                        <br><small class="text-muted">{{ $doc->fecha_revision->format('d/m/Y H:i') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if(!empty($doc->unidad_id))
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted fw-semibold">Unidad:</small>
                                                @php
                                                    $unidadSeleccionada = $todasLasUnidades->firstWhere('id', $doc->unidad_id);
                                                @endphp
                                                <span class="badge bg-info text-dark">
                                                    <i class="fas fa-building me-1"></i>{{ $unidadSeleccionada->nombre ?? 'Sin unidad' }}
                                                </span>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if(isset($doc->fecha_limite))
                                        <div class="mb-3">
                                            <small class="text-muted fw-semibold">Fecha límite:</small>
                                            <div class="text-{{ now() > $doc->fecha_limite ? 'danger' : 'success' }} fw-bold">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $doc->fecha_limite->format('d/m/Y') }}
                                                @if(now() > $doc->fecha_limite)
                                                    <span class="badge bg-danger ms-1">Vencido</span>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($doc->archivo_original)
                                        <div class="mb-3">
                                            <small class="text-muted fw-semibold">Archivo subido:</small>
                                            <div class="text-primary">
                                                <i class="fas fa-file-alt me-1"></i>
                                                {{ Str::limit($doc->archivo_original, 25) }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="card-footer bg-transparent border-0 p-3">
                                        <div class="d-grid gap-2">
                                            @if($doc->archivo_original)
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('aprendiz.documentos.descargar', $doc->id) }}" 
                                                       class="btn btn-outline-success btn-sm">
                                                        <i class="fas fa-download me-1"></i>Descargar
                                                    </a>
                                                    @if(($doc->estado ?? 'pendiente') == 'rechazado')
                                                        <button class="btn btn-outline-warning btn-sm" 
                                                                onclick="editarDocumentoRequerido('{{ $doc->id }}', '{{ $doc->titulo ?? $doc->nombre }}', '{{ $doc->descripcion ?? '' }}', '{{ $doc->archivo_original ?? '' }}')" 
                                                                data-bs-toggle="modal" data-bs-target="#modalEditarDocumentoRequerido">
                                                            <i class="fas fa-edit me-1"></i>Editar
                                                        </button>
                                                    @endif
                                                </div>
                                            @else
                                                <button class="btn btn-success btn-sm" 
                                                        onclick="abrirModalSubida('{{ $doc->id }}', '{{ $doc->titulo ?? $doc->nombre }}', '{{ $fase->id }}', 'Fase {{ $fase->numero }}')">
                                                    <i class="fas fa-upload me-1"></i>Subir Documento
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            @endforeach

            <!-- Sin documentos requeridos -->
            @if($documentosRequeridos->count() == 0)
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="fas fa-folder-open fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted mb-3">No hay documentos requeridos</h4>
                    <p class="text-muted">El superadministrador aún no ha asignado documentos requeridos.</p>
                    <div class="mt-4">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalSubirDocumento">
                            <i class="fas fa-plus me-2"></i>Subir Documento Voluntario
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal moderno para subir documento INTELIGENTE -->
<div class="modal fade" id="modalSubirDocumento" tabindex="-1" aria-labelledby="modalSubirDocumentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white py-4 border-0" 
                 style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="d-flex align-items-center">
                    <i class="fas fa-cloud-upload-alt fa-2x me-3"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalSubirDocumentoLabel">Subir Documento</h5>
                        <small class="opacity-75">Sube un documento requerido para tu proyecto</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('aprendiz.documentos-requeridos.subir') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="fase_id" class="form-label fw-semibold">
                                <i class="fas fa-layer-group text-success me-1"></i>Fase
                            </label>
                            <select class="form-select form-select-lg" id="fase_id" name="fase_id" required>
                                @foreach($fases as $fase)
                                    <option value="{{ $fase->id }}" {{ $faseActual && $faseActual->id == $fase->id ? 'selected' : '' }}>
                                        Fase {{ $fase->numero }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="unidad_id" class="form-label fw-semibold">
                                <i class="fas fa-building text-success me-1"></i>Unidad Productiva
                            </label>
                            <select class="form-select form-select-lg" id="unidad_id" name="unidad_id" required>
                                @foreach($todasLasUnidades as $unidad)
                                    <option value="{{ $unidad->id }}" 
                                        {{ $unidadAsignada && $unidadAsignada->id == $unidad->id ? 'selected' : '' }}>
                                        {{ $unidad->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="descripcion" class="form-label fw-semibold">
                                <i class="fas fa-edit text-success me-1"></i>Descripción del Documento
                            </label>
                            <textarea class="form-control form-control-lg" id="descripcion" name="descripcion" 
                                      rows="3" maxlength="255" placeholder="Describe brevemente el documento..." required></textarea>
                        </div>
                        <div class="col-12">
                            <label for="documento" class="form-label fw-semibold">
                                <i class="fas fa-file text-success me-1"></i>Archivo
                            </label>
                            <input type="file" class="form-control form-control-lg" id="documento" name="documento" 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Formatos permitidos: PDF, DOC, DOCX, XLS, XLSX.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-4">
                        <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-cloud-upload-alt me-2"></i>Subir Documento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para subir documento requerido específico -->
<div class="modal fade" id="modalSubidaDocumento" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white py-4 border-0" 
                 style="background: linear-gradient(135deg, #007bff, #0056b3);">
                <div class="d-flex align-items-center">
                    <i class="fas fa-upload fa-2x me-3"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Subir Documento Requerido</h5>
                        <small class="opacity-75">Sube el documento específico solicitado</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('aprendiz.documentos-requeridos.subir') }}" method="POST" enctype="multipart/form-data" id="formSubidaDocumento">
                @csrf
                <input type="hidden" name="documento_requerido_id" id="documentoRequeridoId">
                <input type="hidden" name="fase_id" id="faseId">
                
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0" style="background: rgba(23, 162, 184, 0.1);">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Documento:</strong> <span id="nombreDocumento"></span><br>
                        <strong>Fase:</strong> <span id="nombreFase"></span><br>
                        <strong>Unidad Productiva:</strong> {{ $unidadAsignada->nombre ?? 'N/A' }}
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-file text-primary me-1"></i>Archivo *
                        </label>
                        <input type="file" name="archivo" class="form-control form-control-lg" accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Formatos permitidos: PDF, DOC, DOCX, XLS, XLSX. Tamaño máximo: 10MB
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-comment text-primary me-1"></i>Comentarios (Opcional)
                        </label>
                        <textarea name="comentarios" class="form-control" rows="3" 
                                  placeholder="Agrega comentarios o descripción sobre el documento"></textarea>
                    </div>

                    <div class="alert alert-warning border-0" style="background: rgba(255, 193, 7, 0.1);">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Importante:</strong> Este documento será revisado por el superadministrador. 
                        Asegúrate de que cumpla con todos los requisitos especificados.
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-upload me-2"></i>Subir Documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver documento -->
<div class="modal fade" id="modalVerDocumento" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white py-4 border-0" 
                 style="background: linear-gradient(135deg, #17a2b8, #20c997);">
                <div class="d-flex align-items-center">
                    <i class="fas fa-eye fa-2x me-3"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Ver Documento</h5>
                        <small class="opacity-75">Vista previa del documento</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="contenidoDocumento">
                <!-- El contenido se cargará dinámicamente -->
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
                <a href="#" class="btn btn-success btn-lg" id="btnDescargarDocumento">
                    <i class="fas fa-download me-2"></i>Descargar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar documento requerido -->
<div class="modal fade" id="modalEditarDocumentoRequerido" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white py-4 border-0" 
                 style="background: linear-gradient(135deg, #ffc107, #ff8f00);">
                <div class="d-flex align-items-center">
                    <i class="fas fa-edit fa-2x me-3"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Editar Documento Requerido</h5>
                        <small class="opacity-75">Modifica el documento y su información</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarDocumentoRequerido" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="documento_requerido_id" id="editDocumentoRequeridoId">
                
                <div class="modal-body p-4">
                    <div class="alert alert-warning border-0" style="background: rgba(255, 193, 7, 0.1);">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Documento:</strong> <span id="editNombreDocumento"></span><br>
                        <strong>Archivo actual:</strong> <span id="editArchivoActual"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-file text-warning me-1"></i>Nuevo Archivo (Opcional)
                        </label>
                        <input type="file" name="archivo" class="form-control form-control-lg" accept=".pdf,.doc,.docx,.xls,.xlsx">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Deja vacío para mantener el archivo actual. Formatos permitidos: PDF, DOC, DOCX, XLS, XLSX. Tamaño máximo: 10MB
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-comment text-warning me-1"></i>Descripción/Comentarios
                        </label>
                        <textarea name="descripcion" id="editDescripcionDocumento" class="form-control" rows="3" 
                                  placeholder="Agrega una descripción o comentarios sobre el documento"></textarea>
                    </div>

                    <div class="alert alert-info border-0" style="background: rgba(23, 162, 184, 0.1);">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Nota:</strong> Solo puedes editar documentos que hayan sido rechazados. 
                        Si cambias el archivo, el documento volverá al estado "pendiente" para nueva revisión.
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning btn-lg">
                        <i class="fas fa-save me-2"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<!-- JAVASCRIPT DIRECTO EN EL HEAD PARA ASEGURAR QUE FUNCIONE -->
<script>
// ⏰ RELOJ EN TIEMPO REAL - JAVASCRIPT PURO
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('es-ES', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
    const dateString = now.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
    
    const timeDisplay = document.getElementById('timeDisplay');
    const currentDate = document.getElementById('currentDate');
    
    if (timeDisplay) timeDisplay.textContent = timeString;
    if (currentDate) currentDate.textContent = dateString;
}

// Iniciar reloj cuando cargue la página
document.addEventListener('DOMContentLoaded', function() {
    updateTime();
    setInterval(updateTime, 1000);
    
    // 🔍 FILTROS FUNCIONALES - JAVASCRIPT PURO
    const filtroFase = document.getElementById('filtroFase');
    const filtroUnidad = document.getElementById('filtroUnidad');
    const filtroEstado = document.getElementById('filtroEstado');
    const limpiarFiltros = document.getElementById('limpiarFiltros');
    
    function aplicarFiltros() {
        const faseSeleccionada = filtroFase ? filtroFase.value : '';
        const unidadSeleccionada = filtroUnidad ? filtroUnidad.value : '';
        const estadoSeleccionado = filtroEstado ? filtroEstado.value : '';
        
        let documentosVisibles = 0;
        const faseCards = document.querySelectorAll('.fase-card');
        
        faseCards.forEach(function(faseCard) {
            const faseId = faseCard.getAttribute('data-fase');
            let hayDocumentosVisibles = false;
            
            // Filtrar documentos dentro de cada fase
            const documentos = faseCard.querySelectorAll('.documento-item');
            documentos.forEach(function(documento) {
                const docFase = documento.getAttribute('data-fase');
                const docUnidad = documento.getAttribute('data-unidad');
                const docEstado = documento.getAttribute('data-estado');
                
                const cumpleFase = !faseSeleccionada || docFase == faseSeleccionada;
                const cumpleUnidad = !unidadSeleccionada || docUnidad == unidadSeleccionada;
                const cumpleEstado = !estadoSeleccionado || docEstado == estadoSeleccionado;
                
                const mostrar = cumpleFase && cumpleUnidad && cumpleEstado;
                
                if (mostrar) {
                    documento.style.display = 'block';
                    hayDocumentosVisibles = true;
                    documentosVisibles++;
                } else {
                    documento.style.display = 'none';
                }
            });
            
            // Mostrar/ocultar la card de fase completa
            if (hayDocumentosVisibles) {
                faseCard.style.display = 'block';
                // Actualizar contador de documentos visibles
                const documentosVisiblesEnFase = faseCard.querySelectorAll('.documento-item[style*="block"], .documento-item:not([style*="none"])').length;
                const contador = faseCard.querySelector('.documentos-count');
                if (contador) contador.textContent = documentosVisiblesEnFase;
            } else {
                faseCard.style.display = 'none';
            }
        });
        
        // Actualizar contador de resultados
        actualizarContadorResultados(documentosVisibles);
        
        // Actualizar estadísticas
        actualizarEstadisticas();
    }
    
    function actualizarContadorResultados(count) {
        const total = document.querySelectorAll('.documento-item').length;
        let mensaje = '';
        
        if (count === total) {
            mensaje = `Mostrando todos los ${total} documentos`;
        } else if (count === 0) {
            mensaje = 'No se encontraron documentos con los filtros aplicados';
        } else {
            mensaje = `Mostrando ${count} de ${total} documentos`;
        }
        
        const resultadosCount = document.getElementById('resultadosCount');
        const totalRequeridos = document.getElementById('totalRequeridos');
        
        if (resultadosCount) resultadosCount.textContent = mensaje;
        if (totalRequeridos) totalRequeridos.textContent = count || total;
    }
    
    function actualizarEstadisticas() {
        const documentosVisibles = document.querySelectorAll('.documento-item[style*="block"], .documento-item:not([style*="none"])');
        
        let pendientes = 0, revision = 0, aprobados = 0, rechazados = 0;
        
        documentosVisibles.forEach(function(doc) {
            const estado = doc.getAttribute('data-estado');
            switch(estado) {
                case 'pendiente':
                    pendientes++;
                    break;
                case 'subido':
                case 'en_revision':
                    revision++;
                    break;
                case 'aprobado':
                    aprobados++;
                    break;
                case 'rechazado':
                    rechazados++;
                    break;
            }
        });
        
        const pendientesCount = document.getElementById('pendientesCount');
        const revisionCount = document.getElementById('revisionCount');
        const aprobadosCount = document.getElementById('aprobadosCount');
        const rechazadosCount = document.getElementById('rechazadosCount');
        
        if (pendientesCount) pendientesCount.textContent = pendientes;
        if (revisionCount) revisionCount.textContent = revision;
        if (aprobadosCount) aprobadosCount.textContent = aprobados;
        if (rechazadosCount) rechazadosCount.textContent = rechazados;
    }
    
    // Event listeners para filtros
    if (filtroFase) filtroFase.addEventListener('change', aplicarFiltros);
    if (filtroUnidad) filtroUnidad.addEventListener('change', aplicarFiltros);
    if (filtroEstado) filtroEstado.addEventListener('change', aplicarFiltros);
    
    // Limpiar filtros
    if (limpiarFiltros) {
        limpiarFiltros.addEventListener('click', function() {
            if (filtroFase) filtroFase.value = '';
            if (filtroUnidad) filtroUnidad.value = '';
            if (filtroEstado) filtroEstado.value = '';
            aplicarFiltros();
            
            // Mostrar mensaje de limpieza
            this.innerHTML = '<i class="fas fa-check me-1"></i>Limpiado';
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-eraser me-1"></i>Limpiar Filtros';
            }, 1500);
        });
    }
    
    // Inicializar estadísticas
    actualizarEstadisticas();
});

// Función para abrir modal de subida específica
function abrirModalSubida(documentoId, nombreDocumento, faseId, nombreFase) {
    const documentoRequeridoId = document.getElementById('documentoRequeridoId');
    const faseIdInput = document.getElementById('faseId');
    const nombreDocumentoSpan = document.getElementById('nombreDocumento');
    const nombreFaseSpan = document.getElementById('nombreFase');
    
    if (documentoRequeridoId) documentoRequeridoId.value = documentoId;
    if (faseIdInput) faseIdInput.value = faseId;
    if (nombreDocumentoSpan) nombreDocumentoSpan.textContent = nombreDocumento;
    if (nombreFaseSpan) nombreFaseSpan.textContent = nombreFase;
    
    // Mostrar modal usando Bootstrap
    const modal = new bootstrap.Modal(document.getElementById('modalSubidaDocumento'));
    modal.show();
}

// Función para ver documento
function verDocumento(documentoId) {
    const contenidoDocumento = document.getElementById('contenidoDocumento');
    if (contenidoDocumento) {
        contenidoDocumento.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                <h5>Vista previa del documento</h5>
                <p class="text-muted">La vista previa se cargará aquí</p>
            </div>
        `;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('modalVerDocumento'));
    modal.show();
}

// Función para editar documento requerido
function editarDocumentoRequerido(docId, nombreDocumento, descripcion, archivoActual) {
    // Actualizar la acción del formulario
    document.getElementById('formEditarDocumentoRequerido').action = '{{ route("aprendiz.documentos-requeridos.update", "") }}/' + docId;
    
    // Llenar los campos del modal
    document.getElementById('editDocumentoRequeridoId').value = docId;
    document.getElementById('editNombreDocumento').textContent = nombreDocumento;
    document.getElementById('editArchivoActual').textContent = archivoActual || 'Sin archivo';
    document.getElementById('editDescripcionDocumento').value = descripcion;
}
</script>

<style>
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slide-up {
    animation: slideUp 0.6s ease-out forwards;
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.documento-item .card {
    border-radius: 12px;
    overflow: hidden;
}

.modal-content {
    border-radius: 15px;
    overflow: hidden;
}

.badge {
    border-radius: 8px;
}

.progress {
    border-radius: 10px;
}

.form-control, .form-select {
    border-radius: 8px;
}

.btn {
    border-radius: 8px;
}
.navbar {
    display: none !important;
}

/* ⏰ Reloj destacado */
#currentTime {
    font-family: 'Courier New', monospace;
    letter-spacing: 0.5px;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

/* 🔍 Efectos de filtros */
.documento-item {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

/* 📊 Contador de resultados */
#resultadosCount {
    font-style: italic;
    transition: color 0.3s ease;
}

/* Reloj más visible */
.text-end.me-3.p-2.bg-light.rounded {
    border: 2px solid #28a745;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);
}

/* 🎯 APROVECHAMIENTO MÁXIMO DEL ESPACIO */
.container-fluid {
    max-width: 100vw;
    padding: 0;
}

/* 📊 GRID OPTIMIZADO PARA PANTALLA COMPLETA */
@media (min-width: 1400px) {
    .col-xxl-2 {
        flex: 0 0 auto;
        width: 16.66666667%; /* 6 columnas en pantallas XXL */
    }
}

@media (min-width: 1200px) and (max-width: 1399px) {
    .col-xl-3 {
        flex: 0 0 auto;
        width: 25%; /* 4 columnas en pantallas XL */
    }
}

@media (min-width: 992px) and (max-width: 1199px) {
    .col-lg-4 {
        flex: 0 0 auto;
        width: 33.33333333%; /* 3 columnas en pantallas LG */
    }
}

/* 🎯 ESTADÍSTICAS MEJOR DISTRIBUIDAS */
@media (min-width: 1200px) {
    .col-xl-3 {
        flex: 0 0 auto;
        width: 25%; /* 4 estadísticas en una fila */
    }
}

/* 📱 RESPONSIVE MEJORADO */
@media (max-width: 768px) {
    .col-12 {
        padding: 1rem !important;
    }
    
    .d-flex.gap-2 {
        flex-wrap: wrap;
    }
    
    .card-body {
        padding: 1rem !important;
    }
}

/* 🎨 EFECTOS VISUALES MEJORADOS */
.fase-card {
    transition: all 0.3s ease;
}

.fase-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

/* 🔧 BOTONES MÁS ATRACTIVOS */
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* 📊 TABLAS RESPONSIVAS */
.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}

/* 🎯 FILTROS MEJOR ORGANIZADOS */
.form-label.text-transparent {
    color: transparent !important;
}
</style>