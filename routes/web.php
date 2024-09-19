<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\TrendController;
use App\Http\Controllers\ServicioImageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\CategoriaController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/





Route::get('/login', [SessionsController::class, 'create'])->name('login.index');
Route::post('/login', [SessionsController::class, 'store'])->name('login.store');
Route::get('/logout', [SessionsController::class, 'destroy'])->name('login.destroy');

Auth::routes();

// Ruta para crear una reserva accesible sin autenticación
Route::post('reservas', [ReservaController::class, 'store'])->name('reservas.store');
Route::post('reservas/check-availability', [ReservaController::class, 'checkAvailability'])->name('reservas.checkAvailability');
Route::post('/reservas/filtrar-servicios', [ReservaController::class, 'filtrarServicios'])->name('reservas.filtrarServicios');


// Rutas que requieren autenticación
Route::middleware('auth')->group(function () {
    Route::get('reservas', [ReservaController::class, 'index'])->name('reservas.index');
    Route::get('reservas/{reserva}/edit', [ReservaController::class, 'edit'])->name('reservas.edit');
    Route::put('reservas/{reserva}', [ReservaController::class, 'update'])->name('reservas.update');
    Route::delete('reservas/{reserva}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
    Route::post('reservas/{reserva}/confirm', [ReservaController::class, 'confirm'])->name('reservas.confirm');
    Route::post('reservas/{reserva}/cancel', [ReservaController::class, 'cancel'])->name('reservas.cancel');
});

// Ruta para visualizar el formulario de creación de reservas sin autenticación
Route::get('reservas/create', [ReservaController::class, 'create'])->name('reservas.create');

Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Rutas de CRUD completas para admin
    Route::middleware(['auth.admin'])->group(function () {
        Route::resource('admin', ServicioController::class)->names([
            'create'  => 'servicios.create',
            'store'   => 'servicios.store',
            'edit'    => 'servicios.edit',
            'update'  => 'servicios.update',
            'destroy' => 'servicios.destroy',
            'index'   => 'servicios.index',
        ]);
        Route::get('categorias', [CategoriaController::class, 'index'])->name('categorias.index');

        Route::get('categorias/create', [CategoriaController::class, 'create'])->name('categorias.create');
        Route::post('categorias', [CategoriaController::class, 'store'])->name('categorias.store');
        Route::get('categorias/{categoria}', [CategoriaController::class, 'show'])->name('categorias.show');
        Route::get('categorias/{categoria}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit');
        Route::put('categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
        Route::delete('categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
       
        
    });
    Route::get('/', [CategoriaController::class, 'show'])->name('categorias.show');
    Route::get('servicios/{id}', [ServicioController::class, 'show'])->name('servicios.show');
    Route::get('/servicios/categoria/{categoriaN}', [ServicioController::class, 'showServicios'])->name('servicios.showServicios');

//Rota del calendario.

Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');

// Ruta tendencias.

Route::middleware(['auth.admin'])->group(function () {
    Route::resource('trends', TrendController::class);
    Route::get('trends/create', [TrendController::class, 'create'])->name('trends.create');
    Route::post('trends', [TrendController::class, 'store'])->name('trends.store');
    Route::get('trends/{trend}/edit', [TrendController::class, 'edit'])->name('trends.edit');
    Route::put('trends/{trend}', [TrendController::class, 'update'])->name('trends.update');
    Route::delete('trends/{trend}', [TrendController::class, 'destroy'])->name('trends.destroy');
    
});

Route::get('/showTendencias', [TrendController::class, 'show'])->name('trends.show');

Route::middleware(['auth.admin'])->group(function () {
    Route::get('galeria', [ImageController::class, 'index'])->name('galeria.index');
    Route::get('galeria/create', [ImageController::class, 'create'])->name('galeria.create');
    Route::post('galeria', [ImageController::class, 'store'])->name('galeria.store');
    Route::delete('galeria/{image}', [ImageController::class, 'destroy'])->name('galeria.destroy');
});

Route::get('imagenes/show', [ImageController::class, 'show'])->name('galeria.show');

Route::middleware(['auth.admin'])->prefix('servicios/{servicio}/imagenes')->group(function () {
    Route::get('/', [ServicioImageController::class, 'index'])->name('serviciosImagen.index');
    Route::get('/create', [ServicioImageController::class, 'create'])->name('serviciosImagen.create');
    Route::post('/', [ServicioImageController::class, 'store'])->name('serviciosImagen.store');
    Route::get('/{image}/edit', [ServicioImageController::class, 'edit'])->name('serviciosImagen.edit');
    Route::put('/{image}', [ServicioImageController::class, 'update'])->name('serviciosImagen.update');
    Route::delete('/{image}', [ServicioImageController::class, 'destroy'])->name('serviciosImagen.destroy');
});


Route::get('imagenes/{servicio}/imagenes/show', [ServicioImageController::class, 'show'])->name('serviciosImagen.show');









