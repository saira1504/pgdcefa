@extends('layouts.superadmin')

@section('title', 'Editar Fase - Superadmin')

@section('content')
<div class="container-fluid">
    <!-- Header moderno -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1 text-primary">
                <i class="fas fa-edit me-2"></i>Editar Fase {{ $phase->numero }}
            </h1>
            <p class="text-muted small mb-0">Modifica los datos de la fase existente</p>
        </div>
        <a href="{{ route('superadmin.phases.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Volver
        </a>
    </div>

    <!-- Formulario moderno -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <!-- Header del card -->
                <div class="card-header text-white py-4" 
                     style="background: linear-gradient(135deg, #007bff, #0056b3);">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-layer-group fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0 fw-bold">Editar Información de la Fase</h5>
                            <small class="opacity-75">Modifica los datos según sea necesario</small>
                        </div>
                    </div>
                </div>

                <!-- Body del formulario -->
                <div class="card-body p-4">
                    <form action="{{ route('superadmin.phases.update', $phase) }}" method="POST" id="phaseEditForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Número de fase -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="numero" class="form-label fw-semibold">
                                    <i class="fas fa-hashtag text-primary me-2"></i>Número de la Fase
                                </label>
                                <input type="number" 
                                       class="form-control form-control-lg @error('numero') is-invalid @enderror" 
                                       id="numero" 
                                       name="numero" 
                                       value="{{ old('numero', $phase->numero) }}" 
                                       min="1" 
                                       max="100"
                                       placeholder="Ej: 1, 2, 3..."
                                       required>
                                @error('numero')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>Número único para identificar la fase
                                </div>
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="fecha_inicio" class="form-label fw-semibold">
                                    <i class="fas fa-calendar-plus text-primary me-2"></i>Fecha de Inicio
                                </label>
                                <input type="date" 
                                       class="form-control form-control-lg @error('fecha_inicio') is-invalid @enderror" 
                                       id="fecha_inicio" 
                                       name="fecha_inicio" 
                                       value="{{ old('fecha_inicio', $phase->fecha_inicio ? $phase->fecha_inicio->format('Y-m-d') : '') }}">
                                @error('fecha_inicio')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>Fecha cuando inicia la fase (opcional)
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="fecha_fin" class="form-label fw-semibold">
                                    <i class="fas fa-calendar-check text-success me-2"></i>Fecha de Fin
                                </label>
                                <input type="date" 
                                       class="form-control form-control-lg @error('fecha_fin') is-invalid @enderror" 
                                       id="fecha_fin" 
                                       name="fecha_fin" 
                                       value="{{ old('fecha_fin', $phase->fecha_fin ? $phase->fecha_fin->format('Y-m-d') : '') }}">
                                @error('fecha_fin')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>Fecha cuando termina la fase (opcional)
                                </div>
                            </div>
                        </div>

                        <!-- Duración calculada -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info {{ ($phase->fecha_inicio && $phase->fecha_fin) ? '' : 'd-none' }}" id="duracionAlert">
                                    <i class="fas fa-clock me-2"></i>
                                    <strong>Duración calculada:</strong> 
                                    <span id="duracionTexto">
                                        @if($phase->fecha_inicio && $phase->fecha_fin)
                                            {{ $phase->fecha_inicio->diffInDays($phase->fecha_fin) }} días
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="bg-light rounded p-3">
                                    <h6 class="fw-bold mb-2">
                                        <i class="fas fa-info-circle text-info me-2"></i>Información de la Fase
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">Creada:</small>
                                            <div class="fw-semibold">{{ $phase->created_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">Última modificación:</small>
                                            <div class="fw-semibold">{{ $phase->updated_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                            <a href="{{ route('superadmin.phases.index') }}" 
                               class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Actualizar Fase
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Calcular duración cuando cambien las fechas
    function calcularDuracion() {
        const fechaInicio = $('#fecha_inicio').val();
        const fechaFin = $('#fecha_fin').val();
        
        if (fechaInicio && fechaFin) {
            const inicio = new Date(fechaInicio);
            const fin = new Date(fechaFin);
            
            if (fin >= inicio) {
                const diffTime = Math.abs(fin - inicio);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                $('#duracionTexto').text(diffDays + ' días');
                $('#duracionAlert').removeClass('d-none alert-danger').addClass('alert-info');
            } else {
                $('#duracionTexto').text('La fecha de fin debe ser posterior a la fecha de inicio');
                $('#duracionAlert').removeClass('d-none alert-info').addClass('alert-danger');
            }
        } else {
            $('#duracionAlert').addClass('d-none');
        }
    }
    
    // Event listeners para las fechas
    $('#fecha_inicio, #fecha_fin').on('change', calcularDuracion);
    
    // Validación del formulario
    $('#phaseEditForm').on('submit', function(e) {
        const fechaInicio = $('#fecha_inicio').val();
        const fechaFin = $('#fecha_fin').val();
        
        if (fechaInicio && fechaFin) {
            const inicio = new Date(fechaInicio);
            const fin = new Date(fechaFin);
            
            if (fin < inicio) {
                e.preventDefault();
                alert('La fecha de fin debe ser posterior a la fecha de inicio');
                return false;
            }
        }
    });
});
</script>
@endpush
@endsection