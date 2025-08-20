@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">🔍 Listado Maestro - Super Admin</h2>
            <p class="text-muted mb-4">Revisa y aprueba los documentos enviados por los administradores.</p>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filtros y contadores -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">⏳ Pendientes</h5>
                    <h3>{{ $documentos->where('estado', 'pendiente')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">✅ Aprobados</h5>
                    <h3>{{ $documentos->where('estado', 'aprobado')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">❌ Rechazados</h5>
                    <h3>{{ $documentos->where('estado', 'rechazado')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">📊 Total</h5>
                    <h3>{{ $documentos->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtro por estado -->
    <div class="mb-3">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-secondary active" onclick="filtrarPorEstado('todos')">Todos</button>
            <button type="button" class="btn btn-outline-warning" onclick="filtrarPorEstado('pendiente')">Pendientes</button>
            <button type="button" class="btn btn-outline-success" onclick="filtrarPorEstado('aprobado')">Aprobados</button>
            <button type="button" class="btn btn-outline-danger" onclick="filtrarPorEstado('rechazado')">Rechazados</button>
        </div>
    </div>

    <!-- Tabla de documentos -->
    <table id="tablaDocumentos" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Tipo Proceso</th>
                <th>Nombre Proceso</th>
                <th>Subproceso/SIG/Subsistema</th>
                <th>Documentos</th>
                <th>Nº</th>
                <th>Responsable</th>
                <th>Tipo Documento</th>
                <th>Nombre Documento</th>
                <th>Código</th>
                <th>Versión</th>
                <th>Fecha Creación</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documentos as $doc)
            <tr data-estado="{{ $doc->estado }}">
                <td>{{ $doc->tipo_proceso }}</td>
                <td>{{ $doc->nombre_proceso }}</td>
                <td>{{ $doc->subproceso_sig_subsistema }}</td>
                <td>
                    @if($doc->documentos)
                        <a href="{{ asset('uploads/' . $doc->documentos) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            📄 Ver Documento
                        </a>
                    @else
                        <span class="text-muted">No disponible</span>
                    @endif
                </td>
                <td>{{ $doc->numero_doc }}</td>
                <td>{{ $doc->responsable }}</td>
                <td>{{ $doc->tipo_documento }}</td>
                <td>{{ $doc->nombre_documento }}</td>
                <td>{{ $doc->codigo }}</td>
                <td>{{ $doc->version }}</td>
                <td>{{ \Carbon\Carbon::parse($doc->fecha_creacion)->format('d-m-Y') }}</td>
                <td>
                    <span class="badge bg-{{ $doc->estado === 'pendiente' ? 'warning' : ($doc->estado === 'aprobado' ? 'success' : 'danger') }}">
                        @if($doc->estado === 'pendiente')
                            ⏳ Pendiente
                        @elseif($doc->estado === 'aprobado')
                            ✅ Aprobado
                        @else
                            ❌ Rechazado
                        @endif
                    </span>
                </td>
                <td>
                    @if($doc->estado === 'pendiente')
                        <div class="btn-group" role="group">
                            <form action="{{ route('superadmin.listado_maestro.aprobar', $doc->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-success btn-sm" onclick="return confirm('¿Estás seguro de que quieres aprobar este documento?')">
                                    ✅ Aprobar
                                </button>
                            </form>
                            <form action="{{ route('superadmin.listado_maestro.rechazar', $doc->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres rechazar este documento?')">
                                    ❌ Rechazar
                                </button>
                            </form>
                        </div>
                    @else
                        <span class="text-muted">
                            @if($doc->estado === 'aprobado')
                                Aprobado el {{ \Carbon\Carbon::parse($doc->aprobacion_fecha)->format('d/m/Y') }}
                            @else
                                Rechazado
                            @endif
                        </span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Scripts -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    $('#tablaDocumentos').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        columnDefs: [
            { orderable: false, targets: -1 }
        ]
    });
});

// Función para filtrar por estado
function filtrarPorEstado(estado) {
    // Actualizar botones activos
    $('.btn-group .btn').removeClass('active');
    event.target.classList.add('active');
    
    if (estado === 'todos') {
        $('#tablaDocumentos tbody tr').show();
    } else {
        $('#tablaDocumentos tbody tr').hide();
        $('#tablaDocumentos tbody tr[data-estado="' + estado + '"]').show();
    }
    
    // Redibujar DataTable
    $('#tablaDocumentos').DataTable().draw();
}
</script>
@endsection
