<!-- Modal Subir Documento -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subir nuevo documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.documentos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título del documento</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" 
                               placeholder="Ingrese un título descriptivo" required>
                    </div>

                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria" name="categoria" required>
                            <option value="">Seleccionar categoría</option>
                            <option value="documento">Documento</option>
                            <option value="informe">Informe</option>
                            <option value="cronograma">Cronograma</option>
                            <option value="manual">Manual</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="archivo" class="form-label">Archivo</label>
                        <div class="border border-2 border-dashed rounded p-4 text-center" 
                             style="border-color: #dee2e6 !important;">
                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-2">Arrastra y suelta tu archivo aquí</p>
                            <input type="file" class="form-control" id="archivo" name="archivo" 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                            <small class="text-muted">Formatos: PDF, DOCX, XLSX</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="destino" class="form-label">Destino</label>
                        <select class="form-select" id="destino" name="destino[]" multiple required>
                            <option value="todas">Todas las unidades</option>
                            @if(isset($unidadesProductivas))
                                @foreach($unidadesProductivas as $unidad)
                                    <option value="{{ $unidad->id }}">{{ $unidad->nombre }}</option>
                                @endforeach
                            @else
                                <option value="1">Unidad 1 - Avícola</option>
                                <option value="2">Unidad 2 - Ganadería</option>
                                <option value="3">Unidad 3 - Agricultura</option>
                            @endif
                        </select>
                        <small class="text-muted">Mantén Ctrl presionado para seleccionar múltiples unidades</small>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción (opcional)</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload me-2"></i>Subir Documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
