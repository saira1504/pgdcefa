<!-- Modal Subir Documento Mejorado -->
<div class="modal fade modal-modern" id="uploadDocumentModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4 class="modal-title mb-2">
                        <i class="fas fa-cloud-upload-alt me-3"></i>Subir Nuevo Documento
                    </h4>
                    <p class="mb-0 opacity-90">Sube documentos importantes para las unidades productivas</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('superadmin.documentos.store') }}" method="POST" enctype="multipart/form-data" id="uploadDocumentForm">
                @csrf
                <div class="modal-body">
                    <!-- Información del documento -->
                    <div class="mb-4">
                        <h6 class="text-uppercase fw-bold mb-3" style="color: var(--primary-color); letter-spacing: 1px;">
                            <i class="fas fa-file-alt me-2"></i>Información del Documento
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="titulo" class="form-label form-label-modern">
                                    <i class="fas fa-heading me-2"></i>Título del Documento
                                </label>
                                <input type="text" 
                                       class="form-control form-control-modern" 
                                       id="titulo" 
                                       name="titulo" 
                                       placeholder="Ej: Manual de Procedimientos - Piscicultura"
                                       required>
                            </div>
                            <div class="col-md-4">
                                <label for="categoria" class="form-label form-label-modern">
                                    <i class="fas fa-tags me-2"></i>Categoría
                                </label>
                                <select class="form-select form-control-modern" id="categoria" name="categoria" required>
                                    <option value="">Seleccionar</option>
                                    @if(isset($categoriasDocumento))
                                        @foreach($categoriasDocumento as $categoria)
                                            <option value="{{ $categoria }}">📁 {{ $categoria }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Zona de subida de archivos mejorada -->
                    <div class="mb-4">
                        <label for="archivo" class="form-label form-label-modern">
                            <i class="fas fa-paperclip me-2"></i>Archivo
                        </label>
                        <div class="upload-zone" 
                             style="border: 3px dashed var(--accent-color); 
                                    border-radius: 20px; 
                                    padding: 3rem 2rem; 
                                    text-align: center; 
                                    background: linear-gradient(135deg, rgba(116, 198, 157, 0.05) 0%, rgba(183, 228, 199, 0.05) 100%);
                                    transition: all 0.3s ease;
                                    cursor: pointer;"
                             onclick="document.getElementById('archivo').click()">
                            
                            <div class="upload-content">
                                <div class="upload-icon mb-3">
                                    <i class="fas fa-cloud-upload-alt fa-4x" style="color: var(--accent-color); opacity: 0.7;"></i>
                                </div>
                                <h5 class="mb-3" style="color: var(--primary-color);">
                                    Arrastra tu archivo aquí o haz clic para seleccionar
                                </h5>
                                <p class="text-muted mb-3">
                                    Formatos soportados: PDF, DOCX, XLSX<br>
                                    Tamaño máximo: 10MB
                                </p>
                                <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill" 
                                     style="background: var(--gradient-secondary); color: var(--dark-green);">
                                    <i class="fas fa-mouse-pointer"></i>
                                    <span class="fw-semibold">Seleccionar Archivo</span>
                                </div>
                            </div>
                            
                            <input type="file" 
                                   class="d-none" 
                                   id="archivo" 
                                   name="archivo" 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx" 
                                   required>
                        </div>
                        
                        <!-- Información del archivo seleccionado -->
                        <div id="file-info" class="mt-3 d-none">
                            <div class="d-flex align-items-center gap-3 p-3 rounded-3" 
                                 style="background: rgba(82, 183, 136, 0.1); border: 1px solid rgba(82, 183, 136, 0.2);">
                                <div class="file-icon">
                                    <i class="fas fa-file fa-2x" style="color: var(--secondary-color);"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold" id="file-name"></div>
                                    <small class="text-muted" id="file-size"></small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFile()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Destino y descripción -->
                    <div class="mb-4">
                        <h6 class="text-uppercase fw-bold mb-3" style="color: var(--primary-color); letter-spacing: 1px;">
                            <i class="fas fa-map-marker-alt me-2"></i>Destino y Detalles
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="destino" class="form-label form-label-modern">
                                    <i class="fas fa-bullseye me-2"></i>Unidad Destino
                                </label>
                                <select class="form-select form-control-modern" id="destino" name="destino" required>
                                    <option value="">Seleccionar unidad</option>
                                    @if(isset($unidadesProductivas))
                                        @foreach($unidadesProductivas as $unidad)
                                            <option value="{{ $unidad->id }}">
                                                🏭 {{ $unidad->nombre }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-modern">
                                    <i class="fas fa-info-circle me-2"></i>Estado del Documento
                                </label>
                                <div class="d-flex align-items-center gap-2 p-3 rounded-3" 
                                     style="background: rgba(82, 183, 136, 0.1);">
                                    <i class="fas fa-check-circle" style="color: var(--secondary-color);"></i>
                                    <span class="fw-semibold" style="color: var(--dark-green);">Activo</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="descripcion" class="form-label form-label-modern">
                            <i class="fas fa-comment-alt me-2"></i>Descripción (Opcional)
                        </label>
                        <textarea class="form-control form-control-modern" 
                                  id="descripcion" 
                                  name="descripcion" 
                                  rows="3" 
                                  placeholder="Agrega una descripción detallada del documento..."></textarea>
                    </div>

                    <!-- Progreso de subida -->
                    <div id="upload-progress" class="d-none">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <i class="fas fa-upload" style="color: var(--secondary-color);"></i>
                            <span class="fw-semibold">Subiendo documento...</span>
                        </div>
                        <div class="progress progress-modern">
                            <div class="progress-bar progress-bar-modern" 
                                 role="progressbar" 
                                 style="width: 0%" 
                                 id="progress-bar"></div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-modern btn-modern" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary-modern btn-modern" id="submit-btn">
                        <i class="fas fa-cloud-upload-alt me-2"></i>Subir Documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadZone = document.querySelector('.upload-zone');
    const fileInput = document.getElementById('archivo');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const uploadProgress = document.getElementById('upload-progress');
    const progressBar = document.getElementById('progress-bar');
    const submitBtn = document.getElementById('submit-btn');

    // Drag and drop functionality
    uploadZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadZone.style.borderColor = 'var(--secondary-color)';
        uploadZone.style.background = 'rgba(82, 183, 136, 0.1)';
    });

    uploadZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadZone.style.borderColor = 'var(--accent-color)';
        uploadZone.style.background = 'linear-gradient(135deg, rgba(116, 198, 157, 0.05) 0%, rgba(183, 228, 199, 0.05) 100%)';
    });

    uploadZone.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadZone.style.borderColor = 'var(--accent-color)';
        uploadZone.style.background = 'linear-gradient(135deg, rgba(116, 198, 157, 0.05) 0%, rgba(183, 228, 199, 0.05) 100%)';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect();
        }
    });

    // File input change
    fileInput.addEventListener('change', handleFileSelect);

    function handleFileSelect() {
        const file = fileInput.files[0];
        if (file) {
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            fileInfo.classList.remove('d-none');
            
            // Cambiar el ícono según el tipo de archivo
            const fileIcon = fileInfo.querySelector('.file-icon i');
            const extension = file.name.split('.').pop().toLowerCase();
            
            switch(extension) {
                case 'pdf':
                    fileIcon.className = 'fas fa-file-pdf fa-2x';
                    fileIcon.style.color = '#dc3545';
                    break;
                case 'doc':
                case 'docx':
                    fileIcon.className = 'fas fa-file-word fa-2x';
                    fileIcon.style.color = '#2b579a';
                    break;
                case 'xls':
                case 'xlsx':
                    fileIcon.className = 'fas fa-file-excel fa-2x';
                    fileIcon.style.color = '#217346';
                    break;
                default:
                    fileIcon.className = 'fas fa-file fa-2x';
                    fileIcon.style.color = 'var(--secondary-color)';
            }
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    window.clearFile = function() {
        fileInput.value = '';
        fileInfo.classList.add('d-none');
    }

    // Form submission with progress
    document.getElementById('uploadDocumentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Mostrar progreso
        uploadProgress.classList.remove('d-none');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Subiendo...';
        submitBtn.disabled = true;

        // Simular progreso (en producción, usar XMLHttpRequest para progreso real)
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;
            progressBar.style.width = progress + '%';
        }, 200);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            clearInterval(progressInterval);
            progressBar.style.width = '100%';
            
            if (response.ok) {
                setTimeout(() => {
                    $('#uploadDocumentModal').modal('hide');
                    location.reload();
                }, 1000);
            } else {
                throw new Error('Error en la subida');
            }
        })
        .catch(error => {
            clearInterval(progressInterval);
            alert('Error al subir el documento. Por favor, inténtalo de nuevo.');
            console.error('Error:', error);
        })
        .finally(() => {
            submitBtn.innerHTML = '<i class="fas fa-cloud-upload-alt me-2"></i>Subir Documento';
            submitBtn.disabled = false;
            uploadProgress.classList.add('d-none');
            progressBar.style.width = '0%';
        });
    });

    // Limpiar formulario al cerrar modal
    $('#uploadDocumentModal').on('hidden.bs.modal', function() {
        document.getElementById('uploadDocumentForm').reset();
        fileInfo.classList.add('d-none');
        uploadProgress.classList.add('d-none');
        progressBar.style.width = '0%';
    });
});
</script>
@endpush