@extends('layouts.superadmin')

@section('title', 'Detalles de Unidad Productiva - ' . ($unidad->nombre ?? 'N/A'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="display-5 fw-bold mb-2">{{ $unidad->nombre ?? 'Unidad Productiva' }}</h1>
        <p class="text-muted fs-5">Detalles completos de la unidad productiva</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('superadmin.unidades-productivas.index') }}" class="btn btn-secondary btn-lg btn-custom">
            <i class="fas fa-arrow-left me-2"></i>Volver a Unidades
        </a>
        <form action="{{ route('superadmin.unidades-productivas.destroy', $unidad->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta unidad productiva? Esta acción es irreversible.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-lg btn-custom">
                <i class="fas fa-trash-alt me-2"></i>Eliminar Unidad
            </button>
        </form>
    </div>
</div>

<div class="row">
    <!-- INFORMACIÓN PRINCIPAL - OCUPA TODO EL ANCHO -->
    <div class="col-12">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información General</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Descripción:</strong> {{ $unidad->proyecto ?? 'N/A' }}</p>
                        <p><strong>Tipo:</strong> {{ $unidad->tipo ?? 'N/A' }}</p>
                        <p><strong>Gestor Principal:</strong> {{ $unidad->adminPrincipal->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Estado:</strong> 
                            @php
                                $estadoConfig = match($unidad->estado ?? 'iniciando') {
                                    'iniciando' => ['class' => 'bg-warning text-dark', 'text' => 'INICIANDO'],
                                    'proceso' => ['class' => 'bg-primary text-white', 'text' => 'EN PROCESO'],
                                    'pausado' => ['class' => 'bg-secondary text-white', 'text' => 'PAUSADO'],
                                    'completado' => ['class' => 'bg-success text-white', 'text' => 'COMPLETADO'],
                                    default => ['class' => 'bg-secondary text-white', 'text' => 'SIN ESTADO']
                                };
                            @endphp
                            <span class="badge {{ $estadoConfig['class'] }}">{{ $estadoConfig['text'] }}</span>
                        </p>
                        <p><strong>Fecha de Inicio:</strong> {{ $unidad->fecha_inicio ? $unidad->fecha_inicio->format('d/m/Y') : 'N/A' }}</p>
                        <p><strong>Fecha de Finalización:</strong> {{ $unidad->fecha_fin ? $unidad->fecha_fin->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <p><strong>Objetivos:</strong> {{ $unidad->objetivos ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- APRENDICES ASIGNADOS -->
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Aprendices con Documentos Aprobados ({{ $aprendicesConDocumentosAprobados->count() }})</h5>
            </div>
            <div class="card-body">
                @if($aprendicesConDocumentosAprobados->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay aprendices con documentos aprobados en esta unidad.</p>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($aprendicesConDocumentosAprobados as $aprendiz)
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <strong>{{ $aprendiz->name }}</strong>
                                    <br><small class="text-muted">{{ $aprendiz->email }}</small>
                                    <br><small class="text-success">{{ $aprendiz->documentos_aprobados_count }} documento(s) aprobado(s)</small>
                                </div>
                                <span class="badge bg-success">Aprobado</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- DOCUMENTOS DE LA UNIDAD -->
    <div class="col-md-6">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Documentos de la Unidad ({{ $unidad->documentosAprendiz->count() }})</h5>
            </div>
            <div class="card-body">
                @if($unidad->documentosAprendiz->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-file-slash fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay documentos asociados a esta unidad.</p>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($unidad->documentosAprendiz->take(5) as $documento)
                            <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div>
                                    <strong>{{ $documento->titulo ?? 'Documento sin título' }}</strong>
                                    <br><small class="text-muted">Por: {{ $documento->aprendiz->name ?? 'N/A' }}</small>
                                </div>
                                @php
                                    $estadoDoc = match($documento->estado ?? 'pendiente') {
                                        'aprobado' => ['class' => 'bg-success', 'text' => 'Aprobado'],
                                        'rechazado' => ['class' => 'bg-danger', 'text' => 'Rechazado'],
                                        'subido', 'en_revision' => ['class' => 'bg-info', 'text' => 'En Revisión'],
                                        default => ['class' => 'bg-warning text-dark', 'text' => 'Pendiente']
                                    };
                                @endphp
                                <span class="badge {{ $estadoDoc['class'] }}">{{ $estadoDoc['text'] }}</span>
                            </div>
                        @endforeach
                        @if($unidad->documentosAprendiz->count() > 5)
                            <div class="text-center mt-2">
                                <small class="text-muted">Y {{ $unidad->documentosAprendiz->count() - 5 }} documentos más...</small>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ESTADÍSTICAS RÁPIDAS -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Estadísticas de la Unidad</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="p-3">
                            <h3 class="text-info">{{ $unidad->documentosAprendiz->count() }}</h3>
                            <p class="text-muted mb-0">Total Documentos</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <h3 class="text-success">{{ $unidad->documentosAprendiz->where('estado', 'aprobado')->count() }}</h3>
                            <p class="text-muted mb-0">Documentos Aprobados</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <h3 class="text-warning">{{ $unidad->documentosAprendiz->where('estado', 'pendiente')->count() }}</h3>
                            <p class="text-muted mb-0">Documentos Pendientes</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <h3 class="text-danger">{{ $unidad->documentosAprendiz->where('estado', 'rechazado')->count() }}</h3>
                            <p class="text-muted mb-0">Documentos Rechazados</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.btn-custom {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.card {
    border-radius: 12px;
    border: none;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
    font-weight: 600;
}

.list-group-item {
    transition: background-color 0.2s ease;
}

.list-group-item:hover {
    background-color: rgba(0,0,0,0.02);
}

.badge {
    border-radius: 6px;
    font-weight: 500;
}
</style>