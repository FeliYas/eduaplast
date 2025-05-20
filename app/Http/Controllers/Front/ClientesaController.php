<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Metadato;
use Illuminate\Http\Request;

class ClientesaController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderby('orden', 'asc')->get();
        $metadatos = Metadato::where('seccion', 'clientes')->first();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefono')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;
        return view('front.clientes', compact('clientes', 'metadatos', 'logos', 'contactos', 'whatsapp'));
    }
}
