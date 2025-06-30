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
    <div class="col-md-8">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información General</h5>
            </div>
            <div class="card-body">
                <p><strong>Descripción:</strong> {{ $unidad->proyecto ?? 'N/A' }}</p>
                <p><strong>Tipo:</strong> {{ $unidad->tipo ?? 'N/A' }}</p>
                <p><strong>Gestor Principal:</strong> {{ $unidad->adminPrincipal->name ?? 'N/A' }}</p>
                <p><strong>Estado:</strong> <span class="badge bg-{{ $unidad->estado_color ?? 'secondary' }}">{{ $unidad->estado ?? 'N/A' }}</span></p>
                <p><strong>Fecha de Inicio:</strong> {{ $unidad->fecha_inicio ? $unidad->fecha_inicio->format('d/m/Y') : 'N/A' }}</p>
                <p><strong>Fecha de Finalización:</strong> {{ $unidad->fecha_fin ? $unidad->fecha_fin->format('d/m/Y') : 'N/A' }}</p>
                <p><strong>Objetivos:</strong> {{ $unidad->objetivos ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Aprendices Asignados ({{ $unidad->aprendices->count() }})</h5>
            </div>
            <div class="card-body">
                @if($unidad->aprendices->isEmpty())
                    <p>No hay aprendices asignados a esta unidad.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($unidad->aprendices as $aprendiz)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $aprendiz->name }} ({{ $aprendiz->email }})
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Tareas de la Unidad ({{ $unidad->tareas->count() }})</h5>
            </div>
            <div class="card-body">
                @if($unidad->tareas->isEmpty())
                    <p>No hay tareas asociadas a esta unidad.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($unidad->tareas as $tarea)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $tarea->titulo }} <span class="badge bg-secondary">{{ $tarea->estado }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Documentos de la Unidad ({{ $unidad->documentos->count() }})</h5>
            </div>
            <div class="card-body">
                @if($unidad->documentos->isEmpty())
                    <p>No hay documentos asociados a esta unidad.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($unidad->documentos as $documento)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $documento->nombre }} <span class="badge bg-light text-dark">{{ $documento->tipo }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Progreso General</h5>
            </div>
            <div class="card-body text-center">
                <h2 class="display-4 fw-bold text-success">{{ $unidad->progreso ?? 0 }}%</h2>
                <p class="text-muted">Progreso total de la unidad</p>
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $unidad->progreso ?? 0 }}%;" aria-valuenow="{{ $unidad->progreso ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 