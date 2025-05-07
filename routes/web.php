<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\MetadatoController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\NovedadeController;
use App\Http\Controllers\SectoreController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\UsuarioController;
use App\Models\Slider;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/adm', function () {
        return Inertia::render('Admin/Dashboard', [
            'logo' => \App\Models\Logo::where('seccion', 'dashboard')->first()
        ]);
    })->name('dashboard');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/home/slider', [SliderController::class, 'index'])->name('admin.slider');    
    Route::post('/admin/home/slider/store', [SliderController::class, 'store'])->name('slider.store');
    Route::put('/admin/home/slider/update/{id}', [SliderController::class, 'update'])->name('slider.update');
    Route::delete('/admin/home/slider/delete/{id}', [SliderController::class, 'destroy'])->name('slider.destroy');



    Route::get('/admin/productos/categorias', [CategoriaController::class, 'index'])->name('categorias.dashboard');
    Route::post('/admin/productos/categorias/store', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::put('/admin/productos/categorias/update/{id}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/admin/productos/categorias/destroy/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');


    //rutas de las sectores del dashboard
    Route::get('/admin/sectores', [SectoreController::class, 'index'])->name('sectores.dashboard');
    Route::post('/admin/sectores/store', [SectoreController::class, 'store'])->name('sectores.store');
    Route::put('/admin/sectores/update/{id}', [SectoreController::class, 'update'])->name('sectores.update');
    Route::delete('/admin/sectores/destroy/{id}', [SectoreController::class, 'destroy'])->name('sectores.destroy');


    //rutas de las clientes del dashboard
    Route::get('/admin/clientes', [ClienteController::class, 'index'])->name('clientes.dashboard');
    Route::post('/admin/clientes/store', [ClienteController::class, 'store'])->name('clientes.store');
    Route::put('/admin/clientes/update/{id}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/admin/clientes/destroy/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');

    //rutas de las novedades del dashboard
    Route::get('/admin/novedades', [NovedadeController::class, 'index'])->name('novedades.dashboard');
    Route::post('/admin/novedades/store', [NovedadeController::class, 'store'])->name('novedades.store');
    Route::put('/admin/novedades/update/{id}', [NovedadeController::class, 'update'])->name('novedades.update');
    Route::delete('/admin/novedades/destroy/{id}', [NovedadeController::class, 'destroy'])->name('novedades.destroy');

    //rutas de los logos del dashboard
    Route::get('/admin/logos', [LogoController::class, 'index'])->name('logos.dashboard');
    Route::put('/admin/logos/update/{id}', [LogoController::class, 'update'])->name('logos.update');

    //rutas del newsletter del dashboard
    Route::get('/admin/newsletter', [NewsletterController::class, 'index'])->name('newsletter.dashboard');
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.store');
    Route::delete('/admin/newsletter/destroy/{id}', [NewsletterController::class, 'destroy'])->name('newsletter.destroy');

    //rutas de usuarios del dashboard
    Route::get('/admin/usuarios', [UsuarioController::class, 'index'])->name('usuarios.dashboard');
    Route::post('/admin/usuarios/create', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::put('/admin/usuarios/edit/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/admin/usuarios/delete/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    //rutas de los metadatos
    Route::get('/admin/metadatos', [MetadatoController::class, 'index'])->name('metadatos.dashboard');
    Route::put('/admin/metadatos/update/{id}', [MetadatoController::class, 'update'])->name('metadatos.update');

});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
