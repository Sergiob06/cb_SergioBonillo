<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\JugadorController;
use App\Http\Controllers\EstadisticaController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\PartidoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\MerchandisingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductoSolicitudController;
use App\Http\Controllers\PurchaseController;
use App\Models\Equipo;
use App\Models\Estadistica;
use App\Models\Galeria;
use App\Models\Jugador;
use App\Models\Partido;
use App\Models\Product;

// --- 1. RUTAS PÚBLICAS (Ahora gestionadas por BasketController) ---
Route::get('/', [BasketController::class, 'inicio'])->name('basket.inicio');
Route::get('equipos', [EquipoController::class, 'index'])->name('basket.equipos');
Route::get('equipos/{equipo}', [EquipoController::class, 'show'])->name('basket.equipos.show');
Route::redirect('equipo', 'equipos', 301);
Route::get('merchandising', [MerchandisingController::class, 'index'])->name('basket.merchandising');
Route::get('merchandising/{product}/comprar', [PurchaseController::class, 'create'])->name('basket.merchandising.buy');
Route::post('merchandising/{product}/comprar', [PurchaseController::class, 'store'])->name('basket.merchandising.buy.store');
Route::get('estadisticas', [BasketController::class, 'estadisticas'])->name('basket.estadisticas');
Route::get('partidos', [BasketController::class, 'partidos'])->name('basket.partidos');
Route::get('partidos/{partido}', [BasketController::class, 'partido'])->name('basket.partidos.show');
Route::get('galeria', [BasketController::class, 'galeria'])->name('basket.galeria');
Route::get('contacto', [BasketController::class, 'contacto'])->name('basket.contacto');

// --- 2. AUTENTICACIÓN (Solo accesibles si NO estás logueado) ---
Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// --- 3. PANEL DE ADMINISTRACIÓN (PROTEGIDO) ---
// Todo lo que esté aquí dentro requiere estar logueado
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    Route::get('/', function () {
        return view('admin.dashboard', [
            'resumenAdmin' => [
                'equipos' => Equipo::count(),
                'jugadores' => Jugador::count(),
                'partidos' => Partido::count(),
                'estadisticas' => Estadistica::count(),
                'galerias' => Galeria::count(),
                'productos' => Product::count(),
            ],
        ]);
    })->name('dashboard');

    // RUTAS DE BÚSQUEDA (SIEMPRE antes de los resource)
    Route::get('equipos/search', [EquipoController::class, 'search'])->name('equipos.search');
    Route::get('equipos/{equipo}/analisis', [EquipoController::class, 'analisis'])->name('equipos.analisis');
    Route::get('jugadores/search', [JugadorController::class, 'search'])->name('jugadores.search');
    Route::get('partidos/search', [PartidoController::class, 'search'])->name('partidos.search');
    Route::get('estadisticas/search', [EstadisticaController::class, 'search'])->name('estadisticas.search');
    Route::get('galerias/search', [GaleriaController::class, 'search'])->name('galerias.search');
    Route::get('productos/search', [ProductController::class, 'search'])->name('productos.search');
    Route::get('productos/{product}/solicitudes', [ProductoSolicitudController::class, 'index'])->name('productos.solicitudes.index');
    Route::get('solicitudes-productos/{solicitud}', [ProductoSolicitudController::class, 'show'])->name('productos.solicitudes.show');
    Route::patch('solicitudes-productos/{solicitud}/estado', [ProductoSolicitudController::class, 'updateEstado'])->name('productos.solicitudes.estado');

    // Route::resource ya crea las 7 rutas (index, create, store, show, edit, update, destroy)
    Route::resource("equipos", EquipoController::class);
    Route::resource("jugadores", JugadorController::class);
    Route::resource("partidos", PartidoController::class);
    Route::resource("estadisticas", EstadisticaController::class);
    Route::resource("galerias", GaleriaController::class);
    Route::resource("productos", ProductController::class);
});
