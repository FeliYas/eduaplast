<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\ContactoController;
use App\Http\Controllers\Admin\ContenidoController;
use App\Http\Controllers\Admin\LogoController;
use App\Http\Controllers\Admin\MatriceriaController;
use App\Http\Controllers\Admin\MetadatoController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\NosotroController;
use App\Http\Controllers\Admin\NovedadeController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\ProductoImgController;
use App\Http\Controllers\Admin\SectoreController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Front\ClientesaController;
use App\Http\Controllers\Front\ContactoController as FrontContactoController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\MatriceriaController as FrontMatriceriaController;
use App\Http\Controllers\Front\NosotrosController;
use App\Http\Controllers\Front\NovedadesController;
use App\Http\Controllers\Front\PresupuestoController;
use App\Http\Controllers\Front\ProductosController;
use App\Http\Controllers\Front\SectoresController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/nosotros', [NosotrosController::class, 'index'])->name('nosotros');
Route::get('/productos', [ProductosController::class, 'index'])->name('categorias');
Route::get('/productos/{id}', [ProductosController::class, 'productos'])->name('productos');
Route::get('/productos/{id}/{producto}', [ProductosController::class, 'producto'])->name('producto');
Route::get('/sectores', [SectoresController::class, 'index'])->name('sectores');
Route::get('/matriceria-propia', [FrontMatriceriaController::class, 'index'])->name('matriceria');
Route::get('/clientes', [ClientesaController::class, 'index'])->name('clientes');
Route::get('/novedades', [NovedadesController::class, 'index'])->name('novedades');
Route::get('/novedades/{id}', [NovedadesController::class, 'show'])->name('novedad');
Route::get('/contacto', [FrontContactoController::class, 'index'])->name('contacto');
Route::post('/contacto/enviar', [FrontContactoController::class, 'enviar'])->name('contacto.enviar');
Route::get('/presupuesto', [PresupuestoController::class, 'index'])->name('presupuesto');
Route::post('/presupuesto/agregar', [PresupuestoController::class, 'store'])->name('presupuesto.store');
Route::post('/presupuesto/enviar', [PresupuestoController::class, 'enviar'])->name('presupuesto.enviar');
Route::delete('/presupuesto/eliminar/{id}', [PresupuestoController::class, 'delete'])->name('presupuesto.delete');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/adm', function () {
        return Inertia::render('Admin/Dashboard', [
            'logo' => \App\Models\Logo::where('seccion', 'dashboard')->first()
        ]);
    })->name('dashboard');
});
Route::middleware(['auth', 'verified'])->group(function () {
    //rutas de los sliders del dashboard
    Route::get('/admin/home/slider', [SliderController::class, 'index'])->name('admin.slider');
    Route::post('/admin/home/slider/store', [SliderController::class, 'store'])->name('slider.store');
    Route::post('/admin/home/slider/update/{id}', [SliderController::class, 'update'])->name('slider.update');
    Route::delete('/admin/home/slider/delete/{id}', [SliderController::class, 'destroy'])->name('slider.destroy');
    //rutas de los contenidos del dashboard
    Route::get('/admin/home/contenido', [ContenidoController::class, 'index'])->name('contenido.dashboard');
    Route::post('/admin/home/contenido/update/{id}', [ContenidoController::class, 'update'])->name('contenido.update');

    //rutas de las nosotros del dashboard
    Route::get('/admin/nosotros', [NosotroController::class, 'index'])->name('nosotros.dashboard');
    Route::post('/admin/nosotros/update/{id}', [NosotroController::class, 'update'])->name('nosotros.update');
    Route::post('/admin/nosotros/{id}/tarjeta/{num}/update', [NosotroController::class, 'updateCard'])->name('tarjetanos.update');

    //rutas de los categorias del dashboard
    Route::get('/admin/productos/categorias', [CategoriaController::class, 'index'])->name('categorias.dashboard');
    Route::post('/admin/productos/categorias/store', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::put('/admin/productos/categorias/update/{id}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/admin/productos/categorias/destroy/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
    Route::post('/admin/productos/categorias/destacado', [CategoriaController::class, 'toggleDestacado'])->name('categorias.toggleDestacado');

    //rutas de los productos del dashboard
    Route::get('/admin/productos/productos', [ProductoController::class, 'index'])->name('productos.dashboard');
    Route::post('/admin/productos/productos/store', [ProductoController::class, 'store'])->name('productos.store');
    Route::put('/admin/productos/productos/update/{id}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/admin/productos/productos/delete/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    Route::get('/admin/productos/productos/imagenes/{id}', [ProductoImgController::class, 'index'])->name('imagenes.dashboard');
    Route::post('/admin/productos/productos/imagenes/store', [ProductoImgController::class, 'store'])->name('imagenes.store');
    Route::put('/admin/productos/productos/imagenes/update/{id}', [ProductoImgController::class, 'update'])->name('imagenes.update');
    Route::delete('/admin/productos/productos/imagenes/delete/{id}', [ProductoImgController::class, 'destroy'])->name('imagenes.destroy');

    //rutas de las sectores del dashboard
    Route::get('/admin/sectores', [SectoreController::class, 'index'])->name('sectores.dashboard');
    Route::post('/admin/sectores/store', [SectoreController::class, 'store'])->name('sectores.store');
    Route::put('/admin/sectores/update/{id}', [SectoreController::class, 'update'])->name('sectores.update');
    Route::delete('/admin/sectores/destroy/{id}', [SectoreController::class, 'destroy'])->name('sectores.destroy');

    //rutas de las matricerias del dashboard
    Route::get('/admin/matriceria', [MatriceriaController::class, 'index'])->name('matriceria.dashboard');
    Route::post('/admin/matriceria/update/{id}', [MatriceriaController::class, 'update'])->name('matriceria.update');

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

    //rutas del contacto del dashboard
    Route::get('/admin/contacto', [ContactoController::class, 'index'])->name('contacto.dashboard');
    Route::put('/admin/contacto/update/{id}', [ContactoController::class, 'update'])->name('contacto.update');

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
