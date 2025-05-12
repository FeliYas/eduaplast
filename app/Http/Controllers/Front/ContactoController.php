<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Metadato;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    public function index()
    {
        $contacto = Contacto::first();
        $mapa = $contacto->iframe;
        $metadatos = Metadato::where('seccion', 'contacto')->first();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefono')->get();
        return view('front.contacto', compact('metadatos', 'mapa', 'logos', 'contactos'));
    }
}
