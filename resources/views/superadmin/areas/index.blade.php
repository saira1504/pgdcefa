@extends('layouts.superadmin')

@section('title', 'Áreas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1 text-success">
            <i class="fas fa-layer-group me-2"></i>Gestión de Áreas
        </h1>
        <p class="text-muted small mb-0">Crea y administra las áreas para filtrar documentos del Listado Maestro.</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearArea">➕ Nueva Área</button>
    
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

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
                        <th class="text-end">Acciones</th>
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
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditarArea{{ $area->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('superadmin.areas.destroy', $area) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta área?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Editar -->
                    <div class="modal fade" id="modalEditarArea{{ $area->id }}" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Editar Área</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <form action="{{ route('superadmin.areas.update', $area) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" name="nombre" class="form-control" value="{{ $area->nombre }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Descripción</label>
                                    <textarea name="descripcion" class="form-control" rows="3">{{ $area->descripcion }}</textarea>
                                </div>
                                <div class="form-check form-switch">
                                  <input class="form-check-input" type="checkbox" name="activo" id="activo{{ $area->id }}" {{ $area->activo ? 'checked' : '' }}>
                                  <label class="form-check-label" for="activo{{ $area->id }}">Activa</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">No hay áreas creadas</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
 </div>

<!-- Modal Crear -->
<div class="modal fade" id="modalCrearArea" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nueva Área</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('superadmin.areas.store') }}" method="POST">
        @csrf
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="activo" id="activoNuevo" checked>
              <label class="form-check-label" for="activoNuevo">Activa</label>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Crear</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection


