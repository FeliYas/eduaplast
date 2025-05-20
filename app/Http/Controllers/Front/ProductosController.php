<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Metadato;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('orden', 'asc')->get();
        $metadatos = Metadato::where('seccion', 'productos')->first();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefono')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;
        return view('front.categorias', compact('categorias', 'metadatos', 'logos', 'contactos', 'whatsapp'));
    }
    public function productos($id)
    {
        $productos = Producto::where('categoria_id', $id)->orderBy('orden', 'asc')->get();
        $categorias = Categoria::orderBy('orden', 'asc')->get();
        $categoria = Categoria::findOrFail($id);
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefono')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;
        return view('front.productos', compact('productos', 'categorias', 'categoria', 'logos', 'contactos', 'whatsapp'));
    }
    public function producto($id, $producto)
    {
        $categoria = Categoria::findOrFail($id);
        $producto = Producto::findOrFail($producto);

        // Obtener productos relacionados (misma categoría, excluyendo el producto actual)
        $productosRelacionados = Producto::where('categoria_id', $producto->categoria_id)
            ->where('id', '!=', $producto->id)
            ->take(3)  // Limitamos a 3 productos relacionados
            ->get();

        $categorias = Categoria::orderBy('orden', 'asc')->get();
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefono')->get();
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;

        return view('front.producto', compact(
            'producto',
            'categorias',
            'categoria',
            'logos',
            'contactos',
            'productosRelacionados',
            'whatsapp'
        ));
    }
}
