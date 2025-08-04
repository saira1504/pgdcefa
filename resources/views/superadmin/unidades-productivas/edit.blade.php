@extends('layouts.superadmin')

@section('title', 'Editar Unidad Productiva - ' . ($unidad->nombre ?? 'N/A'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="display-5 fw-bold mb-2">Editar Unidad Productiva: {{ $unidad->nombre ?? 'N/A' }}</h1>
        <p class="text-muted fs-5">Modifica la información de la unidad productiva</p>
    </div>
    <a href="{{ route('superadmin.unidades-productivas.index') }}" class="btn btn-secondary btn-lg btn-custom">
        <i class="fas fa-arrow-left me-2"></i>Volver a Unidades
    </a>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Formulario de Edición</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.unidades-productivas.update', $unidad->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre de la Unidad</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="{{ old('nombre', $unidad->nombre) }}" required maxlength="20">
                                <div class="invalid-feedback" id="nombre-feedback"></div>
                                @error('nombre')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Áreas</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="">Seleccionar Área</option>
                                    <option value="administrativa" {{ old('tipo', $unidad->tipo) == 'administrativa' ? 'selected' : '' }}>Administrativa</option>
                                    <option value="investigacion" {{ old('tipo', $unidad->tipo) == 'investigacion' ? 'selected' : '' }}>Investigación</option>
                                    <option value="comercializacion" {{ old('tipo', $unidad->tipo) == 'comercializacion' ? 'selected' : '' }}>Comercialización</option>
                                    <option value="produccion" {{ old('tipo', $unidad->tipo) == 'produccion' ? 'selected' : '' }}>Producción</option>
                                    <option value="pecuaria" {{ old('tipo', $unidad->tipo) == 'pecuaria' ? 'selected' : '' }}>Pecuaria</option>
                                    <option value="agricola" {{ old('tipo', $unidad->tipo) == 'agricola' ? 'selected' : '' }}>Agrícola</option>
                                    <option value="ambiental" {{ old('tipo', $unidad->tipo) == 'ambiental' ? 'selected' : '' }}>Ambiental</option>
                                    <option value="ventas" {{ old('tipo', $unidad->tipo) == 'ventas' ? 'selected' : '' }}>Ventas</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="proyecto" class="form-label">Descripción del Proyecto</label>
                        <textarea class="form-control" id="proyecto" name="proyecto" rows="3" 
                                  required>{{ old('proyecto', $unidad->proyecto) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gestor_id" class="form-label">Encargado Asignado</label>
                                <select class="form-select" id="gestor_id" name="gestor_id" required>
                                    <option value="">Seleccionar Encargado</option>
                                    @foreach($gestores as $gestor)
                                        <option value="{{ $gestor->id }}" {{ old('gestor_id', $unidad->admin_principal_id) == $gestor->id ? 'selected' : '' }}>
                                            {{ $gestor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                                       value="{{ old('fecha_inicio', $unidad->fecha_inicio ? $unidad->fecha_inicio->format('Y-m-d') : '') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_fin" class="form-label">Fecha de Finalización</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                                       value="{{ old('fecha_fin', $unidad->fecha_fin ? $unidad->fecha_fin->format('Y-m-d') : '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="iniciando" {{ old('estado', $unidad->estado) == 'iniciando' ? 'selected' : '' }}>Iniciando</option>
                                    <option value="proceso" {{ old('estado', $unidad->estado) == 'proceso' ? 'selected' : '' }}>En proceso</option>
                                    <option value="pausado" {{ old('estado', $unidad->estado) == 'pausado' ? 'selected' : '' }}>Pausado</option>
                                    <option value="completado" {{ old('estado', $unidad->estado) == 'completado' ? 'selected' : '' }}>Completado</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="objetivos" class="form-label">Objetivos</label>
                        <textarea class="form-control" id="objetivos" name="objetivos" rows="2" 
                                  placeholder="Objetivos principales de la unidad productiva">{{ old('objetivos', $unidad->objetivos) }}</textarea>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nombreInput = document.getElementById('nombre');
        const nombreFeedback = document.getElementById('nombre-feedback');
        const editUnidadForm = document.querySelector('form'); // Assuming this is the only form or select by ID if available

        function validateNombre() {
            const nombre = nombreInput.value;
            const regex = /^[a-zA-Z\s]*$/;
            let isValid = true;
            let message = '';

            if (nombre.length > 20) {
                isValid = false;
                message = 'El nombre no debe tener más de 20 caracteres.';
            } else if (!regex.test(nombre)) {
                isValid = false;
                message = 'El nombre solo puede contener letras y espacios. No se permiten números ni caracteres especiales.';
            }

            if (isValid) {
                nombreInput.classList.remove('is-invalid');
                nombreFeedback.textContent = '';
            } else {
                nombreInput.classList.add('is-invalid');
                nombreFeedback.textContent = message;
            }
            return isValid;
        }

        nombreInput.addEventListener('input', validateNombre);

        editUnidadForm.addEventListener('submit', function(event) {
            if (!validateNombre()) {
                event.preventDefault(); // Prevent form submission if validation fails
                event.stopPropagation();
            }
        });
    });
</script>
@endpush 