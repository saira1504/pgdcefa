@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">👑 Dashboard Super Admin</h2>
                </div>
                <div class="card-body">
                    <!-- Notificaciones de documentos pendientes -->
                    @php
                        $documentosPendientes = \App\Models\ListadoMaestro::where('estado', 'pendiente')->count();
                    @endphp
                    
                    @if($documentosPendientes > 0)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-bell fa-2x me-3"></i>
                                <div>
                                    <h5 class="alert-heading">🔔 Notificación Importante</h5>
                                    <p class="mb-0">Tienes <strong>{{ $documentosPendientes }} documento(s)</strong> pendiente(s) de revisión en el listado maestro.</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('superadmin.listado_maestro.index') }}" class="btn btn-warning">
                                    📋 Revisar Documentos
                                </a>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">📋 Listado Maestro</h5>
                                    <p class="card-text">
                                        @if($documentosPendientes > 0)
                                            <span class="badge bg-warning">{{ $documentosPendientes }} pendiente(s)</span>
                                        @else
                                            Sin documentos pendientes
                                        @endif
                                    </p>
                                    <a href="{{ route('superadmin.listado_maestro.index') }}" class="btn btn-light">
                                        @if($documentosPendientes > 0)
                                            🔍 Revisar Pendientes
                                        @else
                                            Ver Listado
                                        @endif
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">🏭 Unidades Productivas</h5>
                                    <p class="card-text">Administrar unidades del sistema</p>
                                    <a href="{{ route('superadmin.unidades-productivas.index') }}" class="btn btn-light">Acceder</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">📄 Documentos</h5>
                                    <p class="card-text">Revisar documentos de aprendices</p>
                                    <a href="{{ route('superadmin.documentos.index') }}" class="btn btn-light">Acceder</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">📊 Reportes</h5>
                                    <p class="card-text">Ver estadísticas del sistema</p>
                                    <a href="{{ route('superadmin.reportes') }}" class="btn btn-light">Acceder</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas rápidas -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>📈 Estadísticas Rápidas</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="border rounded p-3">
                                                <h3 class="text-primary">{{ \App\Models\ListadoMaestro::where('estado', 'pendiente')->count() }}</h3>
                                                <p class="text-muted mb-0">Pendientes</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="border rounded p-3">
                                                <h3 class="text-success">{{ \App\Models\ListadoMaestro::where('estado', 'aprobado')->count() }}</h3>
                                                <p class="text-muted mb-0">Aprobados</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="border rounded p-3">
                                                <h3 class="text-danger">{{ \App\Models\ListadoMaestro::where('estado', 'rechazado')->count() }}</h3>
                                                <p class="text-muted mb-0">Rechazados</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="border rounded p-3">
                                                <h3 class="text-info">{{ \App\Models\ListadoMaestro::count() }}</h3>
                                                <p class="text-muted mb-0">Total</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-5px);
}
.alert {
    border-left: 4px solid #ffc107;
}
</style>
@endsection