<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\PresupuestoMail;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PresupuestoController extends Controller
{
    public function index(Request $request)
    {
        $logos = Logo::whereIn('seccion', ['navbar', 'footer'])->get();
        $contactos = Contacto::select('direccion', 'email', 'telefono')->get();
        $productoId = $request->query('producto');
        $whatsapp = Contacto::select('whatsapp')->first()->whatsapp;

        $carrito = session()->get('carrito_consulta', []);

        if ($productoId) {
            $producto = Producto::find($productoId);
            if ($producto) {
                $carrito[$producto->id] = $producto;
                session(['carrito_consulta' => $carrito]);
            }
        }
        return view('front.presupuesto', compact('logos', 'contactos', 'carrito', 'whatsapp'));
    }

    public function store(Request $request)
    {
        $productoId = $request->input('producto_id');

        $producto = Producto::find($productoId);
        if ($producto) {
            $carrito = session()->get('carrito_consulta', []);
            $carrito[$producto->id] = $producto;
            session(['carrito_consulta' => $carrito]);
        }

        return redirect()->route('presupuesto');
    }

    public function delete($id)
    {
        $carrito = session()->get('carrito_consulta', []);

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session(['carrito_consulta' => $carrito]);
        }

        return redirect()->route('presupuesto');
    }

    public function enviar(Request $request)
    {
        if ($request->input('carrito_vacio') == 1) {
            return redirect()->back()->with('error', 'No hay productos seleccionados en el carrito. Seleccioná productos para enviar la consulta.');
        }

        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'telefono' => 'required|string|max:255',
                'empresa' => 'nullable|string|max:255',
                'mensaje' => 'required|string',
                'archivo' => 'nullable|file|max:2048|mimes:pdf,xlsx,xls,csv,png,jpg,jpeg',
            ], [
                'required' => 'El campo :attribute es obligatorio',
                'email' => 'El campo debe ser un email válido',
                'file' => 'El archivo debe ser válido',
            ]);

            $carrito = session('carrito_consulta', []);

            $mailData = [
                'nombre' => $validated['nombre'],
                'email' => $validated['email'],
                'telefono' => $validated['telefono'],
                'empresa' => $validated['empresa'] ?? 'No especificada',
                'mensaje' => $validated['mensaje'],
                'productos' => $carrito,
            ];

            $archivoPath = null;
            if ($request->hasFile('archivo')) {
                $archivoPath = $request->file('archivo')->store('presupuestos', 'public');
            }
            
            Mail::to(config('mail.from.address'))->send(new PresupuestoMail($mailData, $carrito, $archivoPath));

            session()->forget('carrito_consulta');

            return redirect()->back()->with('success', '¡Tu consulta fue enviada correctamente! Te contactaremos pronto.');
        } catch (\Exception $e) {
            Log::error('Error al enviar correo de presupuesto: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Hubo un problema al enviar tu consulta. Por favor, intenta nuevamente más tarde.');
        }
    }
}
