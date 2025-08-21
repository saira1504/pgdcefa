<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Previa - {{ $documento->titulo }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .pdf-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 1200px;
        }
        
        .pdf-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }
        
        .pdf-content {
            padding: 20px;
            min-height: 600px;
        }
        
        .pdf-iframe {
            width: 100%;
            height: 600px;
            border: none;
            border-radius: 4px;
        }
        
        .action-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        .btn-floating {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-floating:hover {
            transform: scale(1.1);
        }
        
        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 600px;
            flex-direction: column;
        }
        
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
        
        .error-message {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .document-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .document-info h6 {
            color: #495057;
            margin-bottom: 10px;
        }
        
        .document-info p {
            margin-bottom: 5px;
            font-size: 0.9em;
        }
        
        .badge {
            font-size: 0.8em;
        }
        
    </style>
</head>
<body>
    <!-- Botones de Acción Flotantes -->
    <div class="action-buttons">
        <a href="{{ route('admin.documentos.show', $documento) }}" 
           class="btn btn-primary btn-floating" 
           title="Volver al documento">
            <i class="fas fa-arrow-left"></i>
        </a>
        
        <a href="{{ route('admin.documentos.descargar', $documento) }}" 
           class="btn btn-success btn-floating" 
           title="Descargar documento">
            <i class="fas fa-download"></i>
        </a>
        
        <a href="{{ route('admin.documentos.index') }}" 
           class="btn btn-secondary btn-floating" 
           title="Volver a la lista">
            <i class="fas fa-list"></i>
        </a>
    </div>

    <div class="container-fluid">
        <div class="pdf-container">
            <!-- Header -->
            <div class="pdf-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2">
                            <i class="fas fa-file-pdf me-2"></i>{{ $documento->titulo }}
                        </h2>
                        <p class="mb-0 opacity-75">
                            Vista previa del documento subido por {{ $documento->aprendiz->name }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="badge bg-light text-dark fs-6">
                            {{ $documento->estado_texto }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Información del Documento -->
            <div class="pdf-content">
                <div class="document-info">
                    <div class="row">
                        <div class="col-md-3">
                            <h6><i class="fas fa-user me-2"></i>Aprendiz</h6>
                            <p><strong>{{ $documento->aprendiz->name }}</strong></p>
                            <p class="text-muted">{{ $documento->aprendiz->email }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6><i class="fas fa-industry me-2"></i>Unidad</h6>
                            <p><strong>{{ $documento->unidad->nombre }}</strong></p>
                            <p class="text-muted">{{ $documento->unidad->proyecto ?: 'Sin proyecto' }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6><i class="fas fa-layer-group me-2"></i>Fase</h6>
                            <p><strong>Fase {{ $documento->tipoDocumento->numero ?? 'N/A' }}</strong></p>
                            <p class="text-muted">{{ $documento->tipoDocumento->nombre ?? 'Sin nombre' }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6><i class="fas fa-calendar me-2"></i>Fecha</h6>
                            <p><strong>{{ $documento->fecha_subida->format('d/m/Y') }}</strong></p>
                            <p class="text-muted">{{ $documento->fecha_subida->format('H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Vista Previa del PDF -->
                <div id="pdfViewer">
                    <div class="loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-3 text-muted">Cargando vista previa del documento...</p>
                    </div>
                </div>

                <!-- Mensaje de Error (se muestra si hay problemas) -->
                <div id="errorMessage" class="error-message" style="display: none;">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5>No se pudo cargar la vista previa</h5>
                    <p>El documento podría estar dañado o no ser un PDF válido.</p>
                    <a href="{{ route('admin.documentos.descargar', $documento) }}" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i>Descargar Documento
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pdfViewer = document.getElementById('pdfViewer');
            const errorMessage = document.getElementById('errorMessage');
            const pdfUrl = "{{ $url }}";
            
            // Crear el iframe para mostrar el PDF
            const iframe = document.createElement('iframe');
            iframe.src = pdfUrl;
            iframe.className = 'pdf-iframe';
            iframe.title = '{{ $documento->titulo }}';
            
            // Manejar la carga del PDF
            iframe.onload = function() {
                // Ocultar el loading
                pdfViewer.innerHTML = '';
                pdfViewer.appendChild(iframe);
            };
            
            // Manejar errores
            iframe.onerror = function() {
                pdfViewer.style.display = 'none';
                errorMessage.style.display = 'block';
            };
            
            // Timeout para detectar si el PDF no carga
            setTimeout(function() {
                if (pdfViewer.querySelector('.loading')) {
                    // Si después de 10 segundos sigue mostrando loading, mostrar error
                    pdfViewer.style.display = 'none';
                    errorMessage.style.display = 'block';
                }
            }, 10000);
            
            // Intentar cargar el PDF
            try {
                pdfViewer.appendChild(iframe);
            } catch (error) {
                console.error('Error al cargar el PDF:', error);
                pdfViewer.style.display = 'none';
                errorMessage.style.display = 'block';
            }
        });
        
        // Función para cerrar la ventana (si se abrió en popup)
        function closeWindow() {
            if (window.opener) {
                window.close();
            } else {
                window.history.back();
            }
        }
        
        // Atajo de teclado para cerrar (ESC)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeWindow();
            }
        });
    </script>
</body>
</html>
