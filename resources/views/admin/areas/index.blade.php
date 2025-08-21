@extends('layouts.admin')

@section('title', 'Áreas - Administrador')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 p-4">
    <div>
        <h1 class="h3 fw-bold mb-1 text-primary">
            <i class="fas fa-layer-group me-2"></i>Gestión de Áreas
        </h1>
        <p class="text-muted small mb-0">Visualiza las áreas disponibles para organizar documentos del Listado Maestro</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="mx-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light border-0">
            <h6 class="mb-0"><i class="fas fa-list me-2"></i>Áreas creadas</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($areas as $area)
                        <tr>
                            <td>{{ $area->nombre }}</td>
                            <td>{{ $area->descripcion }}</td>
                            <td>
                                <span class="badge bg-{{ $area->activo ? 'success' : 'secondary' }}">
                                    {{ $area->activo ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">No hay áreas creadas</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
     </div>
</div>
@endsection
