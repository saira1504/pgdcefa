<!-- Modal Crear Unidad Mejorado -->
<div class="modal fade modal-modern" id="createUnidadModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4 class="modal-title mb-2">
                        <i class="fas fa-plus-circle me-3"></i>Crear Nueva Unidad Productiva
                    </h4>
                    <p class="mb-0 opacity-90">Completa la información para crear una nueva unidad</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form id="createUnidadForm" action="{{ route('superadmin.unidades-productivas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Información básica -->
                    <div class="mb-4">
                        <h6 class="text-uppercase fw-bold mb-3" style="color: var(--primary-color); letter-spacing: 1px;">
                            <i class="fas fa-info-circle me-2"></i>Información Básica
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label form-label-modern">
                                    <i class="fas fa-tag me-2"></i>Nombre de la Unidad
                                </label>
                                <input type="text" 
                                       class="form-control form-control-modern" 
                                       id="nombre" 
                                       name="nombre" 
                                       placeholder="Ej: Unidad Piscicultura"
                                       required 
                                       maxlength="20">
                                <div class="invalid-feedback" id="nombre-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="tipo" class="form-label form-label-modern">
                                    <i class="fas fa-layer-group me-2"></i>Tipo de Unidad
                                </label>
                                <select class="form-select form-control-modern" id="tipo" name="tipo" required>
                                    <option value="">Seleccionar tipo</option>
                                    <option value="avicola">🐔 Avícola</option>
                                    <option value="ganaderia">🐄 Ganadería</option>
                                    <option value="agricultura">🌾 Agricultura</option>
                                    <option value="piscicultura">🐟 Piscicultura</option>
                                    <option value="otro">📦 Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción del proyecto -->
                    <div class="mb-4">
                        <label for="proyecto" class="form-label form-label-modern">
                            <i class="fas fa-file-alt me-2"></i>Descripción del Proyecto
                        </label>
                        <textarea class="form-control form-control-modern" 
                                  id="proyecto" 
                                  name="proyecto" 
                                  rows="3" 
                                  placeholder="Describe detalladamente el proyecto de la unidad productiva..."
                                  required></textarea>
                    </div>

                    <!-- Gestión y fechas -->
                    <div class="mb-4">
                        <h6 class="text-uppercase fw-bold mb-3" style="color: var(--primary-color); letter-spacing: 1px;">
                            <i class="fas fa-calendar-alt me-2"></i>Gestión y Fechas
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="gestor_id" class="form-label form-label-modern">
                                    <i class="fas fa-user-tie me-2"></i>Gestor Asignado
                                </label>
                                <select class="form-select form-control-modern" id="gestor_id" name="gestor_id" required>
                                    <option value="">Seleccionar gestor</option>
                                    @if(isset($gestores))
                                        @foreach($gestores as $gestor)
                                            <option value="{{ $gestor->id }}">
                                                👤 {{ $gestor->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_inicio" class="form-label form-label-modern">
                                    <i class="fas fa-play-circle me-2"></i>Fecha de Inicio
                                </label>
                                <input type="date" 
                                       class="form-control form-control-modern" 
                                       id="fecha_inicio" 
                                       name="fecha_inicio" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="fecha_fin" class="form-label form-label-modern">
                                <i class="fas fa-flag-checkered me-2"></i>Fecha de Finalización
                            </label>
                            <input type="date" 
                                   class="form-control form-control-modern" 
                                   id="fecha_fin" 
                                   name="fecha_fin">
                        </div>
                        <div class="col-md-6">
                            <label for="estado" class="form-label form-label-modern">
                                <i class="fas fa-traffic-light me-2"></i>Estado Inicial
                            </label>
                            <select class="form-select form-control-modern" id="estado" name="estado" required>
                                <option value="iniciando">🌱 Iniciando</option>
                                <option value="proceso">🚀 En proceso</option>
                                <option value="pausado">⏸️ Pausado</option>
                            </select>
                        </div>
                    </div>

                    <!-- Objetivos -->
                    <div class="mb-4">
                        <label for="objetivos" class="form-label form-label-modern">
                            <i class="fas fa-bullseye me-2"></i>Objetivos
                        </label>
                        <textarea class="form-control form-control-modern" 
                                  id="objetivos" 
                                  name="objetivos" 
                                  rows="3" 
                                  placeholder="Define los objetivos principales de la unidad productiva..."></textarea>
                    </div>

                    <!-- Indicador de progreso -->
                    <div class="text-center">
                        <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill" 
                             style="background: rgba(82, 183, 136, 0.1); color: var(--secondary-color);">
                            <i class="fas fa-info-circle"></i>
                            <small class="fw-semibold">Todos los campos marcados son obligatorios</small>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-modern btn-modern" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary-modern btn-modern">
                        <i class="fas fa-plus me-2"></i>Crear Unidad
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nombreInput = document.getElementById('nombre');
    const nombreFeedback = document.getElementById('nombre-feedback');
    const createUnidadForm = document.getElementById('createUnidadForm');

    // Validación en tiempo real del nombre
    function validateNombre() {
        const nombre = nombreInput.value.trim();
        const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/;
        let isValid = true;
        let message = '';

        if (nombre.length === 0) {
            isValid = false;
            message = 'El nombre es obligatorio.';
        } else if (nombre.length > 20) {
            isValid = false;
            message = 'El nombre no debe tener más de 20 caracteres.';
        } else if (!regex.test(nombre)) {
            isValid = false;
            message = 'El nombre solo puede contener letras y espacios.';
        }

        // Aplicar estilos
        if (isValid) {
            nombreInput.classList.remove('is-invalid');
            nombreInput.classList.add('is-valid');
            nombreFeedback.textContent = '';
        } else {
            nombreInput.classList.remove('is-valid');
            nombreInput.classList.add('is-invalid');
            nombreFeedback.textContent = message;
        }
        
        return isValid;
    }

    // Validar mientras escribe
    nombreInput.addEventListener('input', function() {
        validateNombre();
    });

    // Validar fechas
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');

    fechaInicio.addEventListener('change', function() {
        if (fechaFin.value && fechaInicio.value > fechaFin.value) {
            fechaFin.value = '';
            alert('La fecha de finalización debe ser posterior a la fecha de inicio.');
        }
        fechaFin.min = fechaInicio.value;
    });

    // Validación del formulario
    createUnidadForm.addEventListener('submit', function(event) {
        if (!validateNombre()) {
            event.preventDefault();
            event.stopPropagation();
            nombreInput.focus();
        }
    });

    // Limpiar formulario al cerrar modal
    $('#createUnidadModal').on('hidden.bs.modal', function() {
        createUnidadForm.reset();
        $('.form-control').removeClass('is-valid is-invalid');
        $('.invalid-feedback').text('');
    });

    // Animación de entrada del modal
    $('#createUnidadModal').on('shown.bs.modal', function() {
        nombreInput.focus();
    });
});
</script>
@endpush