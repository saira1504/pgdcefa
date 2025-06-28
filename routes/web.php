<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AprendizController;

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
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rutas del Superadmin (mantener las existentes)
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/', function () {
        return view('superadmin.dashboard');
    })->name('dashboard');
    
    Route::get('/unidades-productivas', function () {
        return view('superadmin.unidades-productivas.index');
    })->name('unidades-productivas');
    
    Route::post('/unidades-productivas', 'UnidadProductivaController@store')->name('unidades-productivas.store');
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
});
