<!-- Modal Crear Unidad -->
<div class="modal fade" id="createUnidadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear Nueva Unidad Productiva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.unidades-productivas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre de la Unidad</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       placeholder="Ej: Unidad 4 - Piscicultura" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo de Unidad</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="">Seleccionar tipo</option>
                                    <option value="avicola">Avícola</option>
                                    <option value="ganaderia">Ganadería</option>
                                    <option value="agricultura">Agricultura</option>
                                    <option value="piscicultura">Piscicultura</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="proyecto" class="form-label">Descripción del Proyecto</label>
                        <textarea class="form-control" id="proyecto" name="proyecto" rows="3" 
                                  placeholder="Describe el proyecto de la unidad productiva" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gestor_id" class="form-label">Gestor Asignado</label>
                                <select class="form-select" id="gestor_id" name="gestor_id" required>
                                    <option value="">Seleccionar gestor</option>
                                    @if(isset($gestores))
                                        @foreach($gestores as $gestor)
                                            <option value="{{ $gestor->id }}">{{ $gestor->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_fin" class="form-label">Fecha de Finalización</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado Inicial</label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="iniciando">Iniciando</option>
                                    <option value="proceso">En proceso</option>
                                    <option value="pausado">Pausado</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="objetivos" class="form-label">Objetivos</label>
                        <textarea class="form-control" id="objetivos" name="objetivos" rows="2" 
                                  placeholder="Objetivos principales de la unidad productiva"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Crear Unidad
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
