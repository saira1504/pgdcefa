<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AprendizController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\MaestroController;
use App\Http\Controllers\AreaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación protegidas por el middleware centralizado
Auth::routes(['middleware' => 'role.redirect']);

// =================== RUTAS ADMIN ===================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/unidades-productivas', [App\Http\Controllers\AdminController::class, 'unidadesProductivas'])->name('admin.unidades-productivas');
    Route::post('/admin/asignar-aprendiz', [App\Http\Controllers\AdminController::class, 'asignarAprendiz'])->name('admin.asignar-aprendiz');

    // Rutas para revisión de documentos de aprendices
    Route::prefix('admin/documentos')->name('admin.documentos.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminDocumentoController::class, 'index'])->name('index');
        Route::get('/{documento}', [App\Http\Controllers\AdminDocumentoController::class, 'show'])->name('show');
        Route::post('/{documento}/aprobar', [App\Http\Controllers\AdminDocumentoController::class, 'aprobar'])->name('aprobar');
        Route::post('/{documento}/rechazar', [App\Http\Controllers\AdminDocumentoController::class, 'rechazar'])->name('rechazar');
        Route::post('/{documento}/en-revision', [App\Http\Controllers\AdminDocumentoController::class, 'enRevision'])->name('en-revision');
        Route::get('/{documento}/descargar', [App\Http\Controllers\AdminDocumentoController::class, 'descargar'])->name('descargar');
        Route::get('/{documento}/preview', [App\Http\Controllers\AdminDocumentoController::class, 'preview'])->name('preview');
        Route::get('/estadisticas', [App\Http\Controllers\AdminDocumentoController::class, 'estadisticas'])->name('estadisticas');
    });

    // =================== LISTADO MAESTRO ===================
    Route::get('/admin/listado_maestro', [MaestroController::class, 'adminIndex'])->name('admin.listado_maestro');
    Route::post('/admin/listado_maestro', [MaestroController::class, 'store'])->name('admin.listado_maestro.store');
    Route::put('/admin/listado_maestro/{id}', [MaestroController::class, 'update'])->name('admin.listado_maestro.update');
    Route::delete('/admin/listado_maestro/{id}', [MaestroController::class, 'destroy'])->name('admin.listado_maestro.destroy');
    
    // =================== AREAS (Admin) ===================
    Route::get('/admin/areas', [AreaController::class, 'adminIndex'])->name('admin.areas.index');
    Route::post('/admin/areas', [AreaController::class, 'adminStore'])->name('admin.areas.store');
    Route::put('/admin/areas/{area}', [AreaController::class, 'adminUpdate'])->name('admin.areas.update');
    Route::delete('/admin/areas/{area}', [AreaController::class, 'adminDestroy'])->name('admin.areas.destroy');
    
    // =================== NOTIFICACIONES ===================
    Route::post('/admin/notifications/{id}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('admin.notifications.mark-as-read');
    
    Route::post('/admin/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('admin.notifications.mark-all-as-read');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/maestro', [MaestroController::class, 'index'])->name('maestro.index');
    Route::post('/maestro', [MaestroController::class, 'store'])->name('maestro.store');

    Route::middleware('role:superadmin')->group(function () {
        Route::post('/maestro/{id}/aprobar', [MaestroController::class, 'aprobar'])->name('maestro.aprobar');
        Route::post('/maestro/{id}/rechazar', [MaestroController::class, 'rechazar'])->name('maestro.rechazar');
    });

});

// =================== RUTAS APRENDIZ ===================
Route::middleware(['auth', 'role:aprendiz'])->group(function () {
    // Ruta principal del aprendiz
    Route::get('/aprendiz', [AprendizController::class, 'dashboard'])->name('aprendiz.dashboard');

    // Rutas adicionales del aprendiz
    Route::get('/aprendiz/mi-unidad', [AprendizController::class, 'miUnidad'])->name('aprendiz.mi-unidad');
    Route::get('/aprendiz/unidades-disponibles', [AprendizController::class, 'unidadesDisponibles'])->name('aprendiz.unidades-disponibles');
    Route::get('/aprendiz/documentos', [AprendizController::class, 'documentos'])->name('aprendiz.documentos');
    Route::post('/aprendiz/documentos/subir', [AprendizController::class, 'subirDocumento'])->name('aprendiz.documentos.subir');
    Route::put('/aprendiz/documentos/{id}', [AprendizController::class, 'actualizarDocumento'])->name('aprendiz.documentos.update');
    Route::get('/aprendiz/progreso', [AprendizController::class, 'progreso'])->name('aprendiz.progreso');

    // Rutas para documentos requeridos
    Route::get('/aprendiz/documentos-requeridos', [AprendizController::class, 'documentosRequeridos'])->name('aprendiz.documentos-requeridos');
    Route::post('/aprendiz/documentos-requeridos/subir', [AprendizController::class, 'subirDocumentoRequerido'])->name('aprendiz.documentos-requeridos.subir');
    Route::put('/aprendiz/documentos-requeridos/{id}', [AprendizController::class, 'actualizarDocumentoRequerido'])->name('aprendiz.documentos-requeridos.update');
    Route::get('/aprendiz/documentos/descargar/{documentoId}', [AprendizController::class, 'descargarDocumento'])->name('aprendiz.documentos.descargar');

    // Rutas de phases para el aprendiz
    Route::get('/aprendiz/phases', [PhaseController::class, 'index'])->name('aprendiz.phases.index');
    Route::get('/aprendiz/phases/{phase}', [PhaseController::class, 'show'])->name('aprendiz.phases.show');
});


// =================== RUTA HOME ===================
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// =================== RUTAS SUPERADMIN ===================
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/', function () {
        return view('superadmin.dashboard');
    })->name('dashboard');

    Route::get('/unidades-productivas', [App\Http\Controllers\UnidadProductivaController::class, 'index'])->name('unidades-productivas.index');
    Route::post('/unidades-productivas', [App\Http\Controllers\UnidadProductivaController::class, 'store'])->name('unidades-productivas.store');
    Route::get('/unidades-productivas/{unidad}', [App\Http\Controllers\UnidadProductivaController::class, 'show'])->name('unidades-productivas.show');
    Route::get('/unidades-productivas/{unidad}/edit', [App\Http\Controllers\UnidadProductivaController::class, 'edit'])->name('unidades-productivas.edit');
    Route::put('/unidades-productivas/{unidad}', [App\Http\Controllers\UnidadProductivaController::class, 'update'])->name('unidades-productivas.update');
    Route::delete('/unidades-productivas/{unidad}', [App\Http\Controllers\UnidadProductivaController::class, 'destroy'])->name('unidades-productivas.destroy');

    Route::get('/lista', function () {
        return view('superadmin.lista');
    })->name('lista');

    Route::get('/resultados', function () {
        return view('superadmin.resultados');
    })->name('resultados');

    Route::get('/reportes', function () {
        return view('superadmin.reportes');
    })->name('reportes');

    Route::resource('phases', PhaseController::class);

    Route::get('/phases', [PhaseController::class, 'index'])->name('phases.index');

    // Rutas para revisión de documentos de aprendices (superadmin)
    Route::prefix('documentos')->name('documentos.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminDocumentoController::class, 'index'])->name('index');
        Route::get('/{documento}', [App\Http\Controllers\AdminDocumentoController::class, 'show'])->name('show');
        Route::post('/{documento}/aprobar', [App\Http\Controllers\AdminDocumentoController::class, 'aprobar'])->name('aprobar');
        Route::post('/{documento}/rechazar', [App\Http\Controllers\AdminDocumentoController::class, 'rechazar'])->name('rechazar');
        Route::post('/{documento}/en-revision', [App\Http\Controllers\AdminDocumentoController::class, 'enRevision'])->name('en-revision');
        Route::get('/{documento}/descargar', [App\Http\Controllers\AdminDocumentoController::class, 'descargar'])->name('descargar');
        Route::get('/{documento}/preview', [App\Http\Controllers\AdminDocumentoController::class, 'preview'])->name('preview');
        Route::get('/estadisticas', [App\Http\Controllers\AdminDocumentoController::class, 'estadisticas'])->name('estadisticas');
    });

    // =================== LISTADO MAESTRO SUPERADMIN ===================
    Route::get('/listado_maestro', [MaestroController::class, 'superadminIndex'])->name('listado_maestro.index');
    Route::put('/listado_maestro/{id}', [MaestroController::class, 'update'])->name('listado_maestro.update');
    Route::delete('/listado_maestro/{id}', [MaestroController::class, 'destroy'])->name('listado_maestro.destroy');
    Route::post('/listado_maestro/{id}/aprobar', [MaestroController::class, 'aprobar'])->name('listado_maestro.aprobar');
    Route::post('/listado_maestro/{id}/rechazar', [MaestroController::class, 'rechazar'])->name('listado_maestro.rechazar');

    // =================== AREAS (Superadmin) ===================
    Route::get('/areas', [AreaController::class, 'index'])->name('areas.index');
    Route::post('/areas', [AreaController::class, 'store'])->name('areas.store');
    Route::put('/areas/{area}', [AreaController::class, 'update'])->name('areas.update');
    Route::delete('/areas/{area}', [AreaController::class, 'destroy'])->name('areas.destroy');
});