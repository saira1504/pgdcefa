<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Previa - {{ $documento->titulo }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        
        .pdf-container {
            position: relative;
            width: 100%;
            height: 100vh;
            background: white;
        }
        
        .pdf-iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .floating-actions {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        .floating-actions .btn {
            margin-bottom: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: white;
        }
        
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
        
        .error-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: white;
            text-align: center;
        }
        
        .error-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
        
        .header-info {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 10px 20px;
            z-index: 999;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-info h5 {
            margin: 0;
            font-size: 1rem;
        }
        
        .header-info .badge {
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <!-- Header con información del documento -->
    <div class="header-info">
        <div>
            <h5>
                <i class="fas fa-file-pdf text-danger"></i>
                {{ $documento->titulo }}
            </h5>
            <small class="text-muted">
                {{ $documento->aprendiz->name }} - {{ $documento->unidad->nombre }}
            </small>
        </div>
        <div>
            <span class="badge bg-{{ $documento->estado == 'pendiente' ? 'warning' : ($documento->estado == 'en_revision' ? 'info' : ($documento->estado == 'aprobado' ? 'success' : 'danger')) }}">
                {{ ucfirst(str_replace('_', ' ', $documento->estado)) }}
            </span>
        </div>
    </div>

    <!-- Contenedor del PDF -->
    <div class="pdf-container">
        <!-- Estado de carga -->
        <div id="loadingState" class="loading-container">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-3 text-muted">Cargando documento PDF...</p>
        </div>

        <!-- Estado de error -->
        <div id="errorState" class="error-container" style="display: none;">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h4 class="text-danger">Error al cargar el PDF</h4>
            <p class="text-muted">No se pudo cargar el documento. Verifica que el archivo exista y sea válido.</p>
            <div class="mt-3">
                <a href="{{ route('superadmin.documentos.show', $documento) }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <a href="{{ route('superadmin.documentos.descargar', $documento) }}" class="btn btn-primary">
                    <i class="fas fa-download"></i> Descargar
                </a>
            </div>
        </div>

        <!-- Iframe del PDF -->
        <iframe id="pdfIframe" 
                class="pdf-iframe" 
                src="{{ route('superadmin.documentos.descargar', $documento) }}"
                style="display: none;"
                onload="onPdfLoad()"
                onerror="onPdfError()">
        </iframe>
    </div>

    <!-- Botones flotantes de acción -->
    <div class="floating-actions">
        <a href="{{ route('superadmin.documentos.show', $documento) }}" 
           class="btn btn-secondary" 
           title="Volver al detalle">
            <i class="fas fa-arrow-left"></i>
        </a>
        
        <a href="{{ route('superadmin.documentos.descargar', $documento) }}" 
           class="btn btn-primary" 
           title="Descargar documento">
            <i class="fas fa-download"></i>
        </a>
        
        @if($documento->estado == 'pendiente')
            <button type="button" 
                    class="btn btn-warning" 
                    title="Marcar en revisión"
                    onclick="marcarEnRevision()">
                <i class="fas fa-search"></i>
            </button>
        @endif
        
        @if(in_array($documento->estado, ['pendiente', 'en_revision']))
            <button type="button" 
                    class="btn btn-success" 
                    title="Aprobar documento"
                    onclick="aprobarDocumento()">
                <i class="fas fa-check"></i>
            </button>
            
            <button type="button" 
                    class="btn btn-danger" 
                    title="Rechazar documento"
                    onclick="rechazarDocumento()">
                <i class="fas fa-times"></i>
            </button>
        @endif
    </div>

    <!-- Modal para Aprobar -->
    <div class="modal fade" id="aprobarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-check text-success"></i> Aprobar Documento
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('superadmin.documentos.aprobar', $documento) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>¿Estás seguro de que quieres aprobar este documento?</p>
                        <div class="mb-3">
                            <label for="comentarios" class="form-label">Comentarios (opcional):</label>
                            <textarea name="comentarios" id="comentarios" class="form-control" rows="3" 
                                      placeholder="Comentarios adicionales sobre la aprobación..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Aprobar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Rechazar -->
    <div class="modal fade" id="rechazarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-times text-danger"></i> Rechazar Documento
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('superadmin.documentos.rechazar', $documento) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>¿Estás seguro de que quieres rechazar este documento?</p>
                        <div class="mb-3">
                            <label for="comentarios_rechazo" class="form-label">Motivo del rechazo: <span class="text-danger">*</span></label>
                            <textarea name="comentarios_rechazo" id="comentarios_rechazo" class="form-control" rows="3" 
                                      placeholder="Explica el motivo del rechazo..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times"></i> Rechazar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Formulario oculto para marcar en revisión -->
    <form id="formEnRevision" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Función para manejar la carga exitosa del PDF
        function onPdfLoad() {
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('pdfIframe').style.display = 'block';
        }

        // Función para manejar errores en la carga del PDF
        function onPdfError() {
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('errorState').style.display = 'flex';
        }

        // Función para marcar en revisión
        function marcarEnRevision() {
            if (confirm('¿Estás seguro de que quieres marcar este documento como "En Revisión"?')) {
                const form = document.getElementById('formEnRevision');
                form.action = "{{ route('superadmin.documentos.en-revision', $documento) }}";
                form.submit();
            }
        }

        // Función para aprobar documento
        function aprobarDocumento() {
            const modal = new bootstrap.Modal(document.getElementById('aprobarModal'));
            modal.show();
        }

        // Función para rechazar documento
        function rechazarDocumento() {
            const modal = new bootstrap.Modal(document.getElementById('rechazarModal'));
            modal.show();
        }

        // Ocultar loading después de un tiempo máximo (fallback)
        setTimeout(function() {
            const loadingState = document.getElementById('loadingState');
            const pdfIframe = document.getElementById('pdfIframe');
            const errorState = document.getElementById('errorState');
            
            if (loadingState.style.display !== 'none' && pdfIframe.style.display !== 'block') {
                loadingState.style.display = 'none';
                errorState.style.display = 'flex';
            }
        }, 10000); // 10 segundos de timeout
    </script>
</body>
</html>
