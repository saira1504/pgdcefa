@extends('layouts.aprendiz')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 px-0">
            @include('partials.sidebar_aprendiz')
        </div>
        
        <div class="col-md-9 col-lg-10 p-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('aprendiz.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Mi Unidad Productiva</li>
                </ol>
            </nav>

            <!-- Header de la unidad -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-0">{{ $unidadAsignada->nombre }}</h4>
                            <p class="mb-0 opacity-75">{{ $unidadAsignada->descripcion }}</p>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-light text-success fs-6">Asignada</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Información General</h6>
                            <ul class="list-unstyled">
                                <li><strong>Gestor:</strong> {{ $unidadAsignada->gestor_nombre ?? 'Por asignar' }}</li>
                                <li><strong>Fecha de asignación:</strong> {{ $unidadAsignada->fecha_asignacion ?? 'N/A' }}</li>
                                <li><strong>Estado:</strong> <span class="badge bg-success">Activa</span></li>
                                <li><strong>Tipo:</strong> {{ $unidadAsignada->tipo ?? 'Proyecto productivo' }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Mi Progreso</h6>
                            <div class="progress mb-2" style="height: 25px;">
                                <div class="progress-bar bg-success progress-bar-striped" 
                                     style="width: {{ $progresoReal }}%">
                                    {{ $progresoReal }}%
                                </div>
                            </div>
                            <small class="text-muted">{{ $documentosCompletados }} de {{ $totalDocumentos }} documentos completados</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documentos requeridos reales -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Mis Documentos Subidos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Documento</th>
                                    <th>Estado</th>
                                    <th>Fecha de Subida</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($misDocumentos as $doc)
                                <tr>
                                    <td>
                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                        <strong>{{ $doc->titulo }}</strong>
                                        <div class="text-muted small">{{ $doc->descripcion }}</div>
                                    </td>
                                    <td>
                                        @if($doc->estado == 'aprobado')
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Aprobado</span>
                                        @elseif($doc->estado == 'rechazado')
                                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Rechazado</span>
                                        @elseif($doc->estado == 'subido' || $doc->estado == 'en_revision')
                                            <span class="badge bg-info"><i class="fas fa-clock me-1"></i>En Revisión</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="fas fa-exclamation me-1"></i>Pendiente</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $doc->fecha_subida ? $doc->fecha_subida->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td>
                                        <a href="{{ Storage::url($doc->archivo_path) }}" class="btn btn-outline-success btn-sm" target="_blank">
                                            <i class="fas fa-download"></i> Descargar
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No has subido documentos aún.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
