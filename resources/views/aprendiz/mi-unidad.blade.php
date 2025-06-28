@extends('layouts.app')

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

            <!-- Documentos requeridos -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Documentos Requeridos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Documento</th>
                                    <th>Estado</th>
                                    <th>Fecha Límite</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documentosRequeridos as $doc)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-pdf text-danger me-2"></i>
                                            <div>
                                                <strong>{{ $doc->nombre }}</strong>
                                                @if($doc->obligatorio)
                                                    <span class="badge bg-warning ms-2">Obligatorio</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $miDoc = $misDocumentos->where('tipo_documento_id', $doc->id)->first();
                                        @endphp
                                        @if($miDoc)
                                            @if($miDoc->estado == 'aprobado')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Aprobado
                                                </span>
                                            @elseif($miDoc->estado == 'rechazado')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i>Rechazado
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i>En revisión
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-upload me-1"></i>Pendiente
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($doc->fecha_limite)
                                            <span class="text-danger">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($doc->fecha_limite)->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-muted">Sin fecha límite</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($miDoc)
                                            @if($miDoc->estado == 'aprobado')
                                                <button class="btn btn-success btn-sm" disabled>
                                                    <i class="fas fa-check me-1"></i>Completado
                                                </button>
                                            @elseif($miDoc->estado == 'rechazado')
                                                <a href="{{ route('aprendiz.documentos') }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-redo me-1"></i>Resubir
                                                </a>
                                            @else
                                                <button class="btn btn-info btn-sm" disabled>
                                                    <i class="fas fa-clock me-1"></i>En revisión
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('aprendiz.documentos') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-upload me-1"></i>Subir
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
