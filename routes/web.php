<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
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

Route::middleware(['auth', 'role:aprendiz'])->group(function () {
    Route::get('/aprendiz', function () {
        return view('aprendiz.dashboard');
    })->name('aprendiz.dashboard');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Rutas del Superadmin
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