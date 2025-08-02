<!-- Modal Crear Unidad - Estilo WireUI -->
<div class="modal fade modal-wireui" id="createUnidadModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-1">
                        <i class="fas fa-plus-circle mr-2 text-green-600"></i>
                        Crear Nueva Unidad Productiva
                    </h4>
                    <p class="text-sm text-gray-600">Completa la información para crear una nueva unidad</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form id="createUnidadForm" action="{{ route('superadmin.unidades-productivas.store') }}" method="POST">
                @csrf
                <div class="modal-body space-y-6">
                    <!-- Información básica -->
                    <div>
                        <h6 class="text-sm font-medium text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Información Básica
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nombre" class="label-wireui">
                                    <i class="fas fa-tag mr-1"></i>
                                    Nombre de la Unidad
                                </label>
                                <input type="text" 
                                       class="input-wireui" 
                                       id="nombre" 
                                       name="nombre" 
                                       placeholder="Ej: Unidad Piscicultura"
                                       required 
                                       maxlength="20">
                                <div class="text-red-600 text-sm mt-1 hidden" id="nombre-feedback"></div>
                            </div>
                            <div>
                                <label for="tipo" class="label-wireui">
                                    <i class="fas fa-layer-group mr-1"></i>
                                    Área
                                </label>
                                <select class="input-wireui" id="tipo" name="tipo" required>
                                    <option value="">Seleccionar área</option>
                                    <option value="administrativa">Administrativa</option>
                                    <option value="investigacion">Investigación</option>
                                    <option value="comercializacion">Comercialización</option>
                                    <option value="produccion">Producción</option>
                                    <option value="pecuaria">Pecuaria</option>
                                    <option value="agricola">Agrícola</option>
                                    <option value="ambiental">Ambiental</option>
                                    <option value="ventas">Ventas</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label for="proyecto" class="label-wireui">
                            <i class="fas fa-file-alt mr-1"></i>
                            Descripción de la Unidad Productiva
                        </label>
                        <textarea class="input-wireui" 
                                  id="proyecto" 
                                  name="proyecto" 
                                  rows="3" 
                                  placeholder="Describe detalladamente la unidad productiva..."
                                  required></textarea>
                    </div>

                    <!-- Gestión y fechas -->
                    <div>
                        <h6 class="text-sm font-medium text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>
                            Gestión y Fechas
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="gestor_id" class="label-wireui">
                                    <i class="fas fa-user-tie mr-1"></i>
                                    Gestor Asignado
                                </label>
                                <select class="input-wireui" id="gestor_id" name="gestor_id" required>
                                    <option value="">Seleccionar gestor</option>
                                    @if(isset($gestores))
                                        @foreach($gestores as $gestor)
                                            <option value="{{ $gestor->id }}">{{ $gestor->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div>
                                <label for="fecha_inicio" class="label-wireui">
                                    <i class="fas fa-play-circle mr-1"></i>
                                    Fecha de Inicio
                                </label>
                                <input type="date" 
                                       class="input-wireui" 
                                       id="fecha_inicio" 
                                       name="fecha_inicio" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="fecha_fin" class="label-wireui">
                                <i class="fas fa-flag-checkered mr-1"></i>
                                Fecha de Finalización
                            </label>
                            <input type="date" 
                                   class="input-wireui" 
                                   id="fecha_fin" 
                                   name="fecha_fin">
                        </div>
                        <div>
                            <label for="estado" class="label-wireui">
                                <i class="fas fa-traffic-light mr-1"></i>
                                Estado Inicial
                            </label>
                            <select class="input-wireui" id="estado" name="estado" required>
                                <option value="iniciando">🌱 Iniciando</option>
                                <option value="proceso">🚀 En proceso</option>
                                <option value="pausado">⏸️ Pausado</option>
                            </select>
                        </div>
                    </div>

                    <!-- Objetivos -->
                    <div>
                        <label for="objetivos" class="label-wireui">
                            <i class="fas fa-bullseye mr-1"></i>
                            Objetivos
                        </label>
                        <textarea class="input-wireui" 
                                  id="objetivos" 
                                  name="objetivos" 
                                  rows="3" 
                                  placeholder="Define los objetivos principales de la unidad productiva..."></textarea>
                    </div>

                    <!-- Nota informativa -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-center text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span class="text-sm font-medium">Todos los campos marcados son obligatorios</span>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer flex justify-end space-x-3">
                    <button type="button" class="btn-wireui btn-secondary-wireui" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn-wireui btn-primary-wireui">
                        <i class="fas fa-plus"></i>
                        Crear Unidad
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nombreInput = document.getElementById('nombre');
    const nombreFeedback = document.getElementById('nombre-feedback');
    const createUnidadForm = document.getElementById('createUnidadForm');

    // Validación en tiempo real
    function validateNombre() {
        const nombre = nombreInput.value.trim();
        const regex = /^[a-zA-ZÀ-ÿ\u00f1\u00d1\s]*$/;
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

        if (isValid) {
            nombreInput.classList.remove('border-red-300');
            nombreInput.classList.add('border-green-300');
            nombreFeedback.classList.add('hidden');
        } else {
            nombreInput.classList.remove('border-green-300');
            nombreInput.classList.add('border-red-300');
            nombreFeedback.textContent = message;
            nombreFeedback.classList.remove('hidden');
        }
        
        return isValid;
    }

    nombreInput.addEventListener('input', validateNombre);

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
        nombreInput.classList.remove('border-red-300', 'border-green-300');
        nombreFeedback.classList.add('hidden');
    });
});
</script>
