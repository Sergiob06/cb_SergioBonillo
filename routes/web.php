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

    Route::middleware('role:admin')->group(function () {
        Route::get('galerias/search', [GaleriaController::class, 'search'])->name('galerias.search');
        Route::get('productos/search', [ProductController::class, 'search'])->name('productos.search');
        Route::get('productos/{product}/solicitudes', [ProductoSolicitudController::class, 'index'])->name('productos.solicitudes.index');
        Route::get('solicitudes-productos/{solicitud}', [ProductoSolicitudController::class, 'show'])->name('productos.solicitudes.show');
        Route::patch('solicitudes-productos/{solicitud}/estado', [ProductoSolicitudController::class, 'updateEstado'])->name('productos.solicitudes.estado');

        Route::resource("equipos", EquipoController::class)->except(['index', 'show']);
        Route::resource("jugadores", JugadorController::class)->except(['index', 'show']);
        Route::get('partidos/create', [PartidoController::class, 'create'])->name('partidos.create');
        Route::post('partidos', [PartidoController::class, 'store'])->name('partidos.store');
        Route::get('partidos/{partido}/edit', [PartidoController::class, 'edit'])->name('partidos.edit');
        Route::match(['put', 'patch'], 'partidos/{partido}', [PartidoController::class, 'update'])->name('partidos.update');
        Route::delete('partidos/{partido}', [PartidoController::class, 'destroy'])->name('partidos.destroy');
        Route::delete('estadisticas/{estadistica}', [EstadisticaController::class, 'destroy'])->name('estadisticas.destroy');
        Route::resource("galerias", GaleriaController::class);
        Route::resource("productos", ProductController::class);
    });

    // Rutas deportivas: admin y entrenador pueden consultar e introducir datos.
    Route::middleware('role:admin,entrenador')->group(function () {
        Route::get('equipos/search', [EquipoController::class, 'search'])->name('equipos.search');
        Route::get('equipos/{equipo}/analisis', [EquipoController::class, 'analisis'])->name('equipos.analisis');
        Route::get('equipos', [EquipoController::class, 'index'])->name('equipos.index');
        Route::get('equipos/{equipo}', [EquipoController::class, 'show'])->name('equipos.show');

        Route::get('jugadores/search', [JugadorController::class, 'search'])->name('jugadores.search');
        Route::get('jugadores', [JugadorController::class, 'index'])->name('jugadores.index');
        Route::get('jugadores/{jugador}', [JugadorController::class, 'show'])->name('jugadores.show');

        Route::get('partidos/search', [PartidoController::class, 'search'])->name('partidos.search');
        Route::get('partidos', [PartidoController::class, 'index'])->name('partidos.index');
        Route::get('partidos/{partido}/estadisticas', [PartidoController::class, 'editEstadisticas'])->name('partidos.estadisticas.edit');
        Route::match(['put', 'patch'], 'partidos/{partido}/estadisticas', [PartidoController::class, 'updateEstadisticas'])->name('partidos.estadisticas.update');
        Route::get('partidos/{partido}', [PartidoController::class, 'show'])->name('partidos.show');

        Route::get('estadisticas/search', [EstadisticaController::class, 'search'])->name('estadisticas.search');
        Route::get('estadisticas', [EstadisticaController::class, 'index'])->name('estadisticas.index');
        Route::get('estadisticas/create', [EstadisticaController::class, 'create'])->name('estadisticas.create');
        Route::post('estadisticas', [EstadisticaController::class, 'store'])->name('estadisticas.store');
        Route::get('estadisticas/{estadistica}', [EstadisticaController::class, 'show'])->name('estadisticas.show');
        Route::get('estadisticas/{estadistica}/edit', [EstadisticaController::class, 'edit'])->name('estadisticas.edit');
        Route::match(['put', 'patch'], 'estadisticas/{estadistica}', [EstadisticaController::class, 'update'])->name('estadisticas.update');
    });
});
