<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Metadato;
use App\Models\Nosotro;
use Illuminate\Http\Request;

class NosotrosController extends Controller
{
    public function index()
    {
        $nosotros = Nosotro::first();
        $metadatos = Metadato::where('seccion', 'nosotros')->first();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefono')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;
        return view('front.nosotros', compact('nosotros', 'metadatos', 'logos', 'contactos', 'whatsapp'));
    }
}
