<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Contacto;
use App\Models\Contenido;
use App\Models\Logo;
use App\Models\Novedade;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $logos = Logo::whereIn('seccion', ['home', 'navbar', 'footer'])->get();
        $sliders = Slider::orderBy('orden', 'asc')->get();
        $categorias = Categoria::where('destacado', 1)->orderBy('orden', 'asc')->get();
        $contenido = Contenido::first();
        $novedades = Novedade::orderBy('orden', 'asc')->take(3)->get();
        $contactos = Contacto::select('direccion', 'email', 'telefono')->get();
        $clientes = Cliente::orderBy('orden', 'asc')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;
        return view('front.home', compact('logos', 'sliders', 'categorias', 'contenido', 'novedades', 'contactos', 'clientes', 'whatsapp'));

    }
    
}
