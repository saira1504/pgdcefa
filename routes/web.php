<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AprendizController;
use App\Http\Controllers\PhaseController;

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

// Rutas protegidas por roles
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::get('/admin/unidades-productivas', [App\Http\Controllers\AdminController::class, 'unidadesProductivas'])->name('admin.unidades-productivas');
    Route::post('/admin/asignar-aprendiz', [App\Http\Controllers\AdminController::class, 'asignarAprendiz'])->name('admin.asignar-aprendiz');
});

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin', function () {
        return view('superadmin.dashboard');
    })->name('superadmin.dashboard');
});

// ===== RUTAS DEL APRENDIZ - NUEVAS =====
Route::middleware(['auth', 'role:aprendiz'])->group(function () {
    // Ruta principal del aprendiz
    Route::get('/aprendiz', [AprendizController::class, 'dashboard'])->name('aprendiz.dashboard');
    
    // Rutas adicionales del aprendiz
    Route::get('/aprendiz/mi-unidad', [AprendizController::class, 'miUnidad'])->name('aprendiz.mi-unidad');
    Route::get('/aprendiz/documentos', [AprendizController::class, 'documentos'])->name('aprendiz.documentos');
    Route::post('/aprendiz/documentos/subir', [AprendizController::class, 'subirDocumento'])->name('aprendiz.documentos.subir');
    Route::get('/aprendiz/progreso', [AprendizController::class, 'progreso'])->name('aprendiz.progreso');
    
                // Rutas para documentos requeridos
            Route::get('/aprendiz/documentos-requeridos', [AprendizController::class, 'documentosRequeridos'])->name('aprendiz.documentos-requeridos');
            Route::post('/aprendiz/documentos-requeridos/subir', [AprendizController::class, 'subirDocumentoRequerido'])->name('aprendiz.documentos-requeridos.subir');
            Route::get('/aprendiz/documentos/descargar/{documentoId}', [AprendizController::class, 'descargarDocumento'])->name('aprendiz.documentos.descargar');
    
    // Rutas de phases para el aprendiz
    Route::get('/aprendiz/phases', [PhaseController::class, 'index'])->name('aprendiz.phases.index');
    Route::get('/aprendiz/phases/{phase}', [PhaseController::class, 'show'])->name('aprendiz.phases.show');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rutas del Superadmin (mantener las existentes)
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
    
    Route::post('/documentos', 'DocumentoController@store')->name('documentos.store');
    
    Route::get('/lista', function () {
        return view('superadmin.lista');
    })->name('lista');
    
    Route::get('/documentos', function () {
        return view('superadmin.documentos');
    })->name('documentos');
    
    Route::get('/resultados', function () {
        return view('superadmin.resultados');
    })->name('resultados');
    
    Route::get('/reportes', function () {
        return view('superadmin.reportes');
    })->name('reportes');

    Route::resource('phases', PhaseController::class);

    Route::get('/phases', [PhaseController::class, 'index'])->name('phases.index');
    
});