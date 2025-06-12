<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Metadato;
use App\Models\Novedade;
use Illuminate\Http\Request;

class NovedadesController extends Controller
{
    public function index()
    {
        $novedades = Novedade::orderby('orden', 'asc')->get();
        $metadatos = Metadato::where('seccion', 'novedades')->first();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefono')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;
        return view('front.novedades', compact('novedades', 'metadatos', 'logos', 'contactos', 'whatsapp'));
    }
    public function show($id)
    {
        $novedad = Novedade::findOrFail($id);
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefono')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;
        return view('front.novedad', compact('novedad', 'logos', 'contactos', 'whatsapp'));
    }
}
