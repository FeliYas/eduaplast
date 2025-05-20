<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Matriceria;
use App\Models\Metadato;
use Illuminate\Http\Request;

class MatriceriaController extends Controller
{
    public function index()
    {
        $matriceria = Matriceria::first();
        $metadatos = Metadato::where('seccion', 'matriceria')->first();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefono')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;
        return view('front.matriceria', compact('matriceria', 'metadatos', 'logos', 'contactos', 'whatsapp'));
    }
}
