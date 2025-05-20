<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Metadato;
use App\Models\Sectore;
use Illuminate\Http\Request;

class SectoresController extends Controller
{
    public function index()
    {
        $sectores = Sectore::orderby('orden', 'asc')->get();
        $metadatos = Metadato::where('seccion', 'sectores')->first();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefono')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;
        return view('front.sectores', compact('sectores', 'metadatos', 'logos', 'contactos', 'whatsapp'));
    }
}
