<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\PresupuestoMail;
use App\Models\Contacto;
use App\Models\Logo;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'telefono' => 'required|string|max:255',
                'empresa' => 'nullable|string|max:255',
                'mensaje' => 'required|string',
                'archivo' => 'nullable|file|max:2048|mimes:pdf,xlsx,xls,csv,png,jpg,jpeg',
                'g-recaptcha-response' => 'required'
            ], [
                'required' => 'El campo :attribute es obligatorio',
                'email' => 'El campo debe ser un email válido',
                'file' => 'El archivo debe ser válido',
            ]);

            // Verificar el token de reCAPTCHA
            $recaptcha = $this->verificarRecaptcha($request->input('g-recaptcha-response'));

            if (!$recaptcha['success']) {
                return redirect()->back()
                    ->withErrors(['recaptcha' => 'La verificación de seguridad ha fallado. Por favor, inténtalo de nuevo.'])
                    ->withInput();
            }

            // Si el score es muy bajo (posible bot), puedes rechazar la solicitud
            if ($recaptcha['score'] < 0.7) {
                return redirect()->back()
                    ->withErrors(['recaptcha' => 'La verificación de seguridad ha detectado actividad sospechosa. Por favor, inténtalo de nuevo más tarde.'])
                    ->withInput();
            }
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

            $contacto = Contacto::first()->email;

            if (!$contacto) {
                return redirect()->back()->with('error', 'No se encontró un contacto con el tipo "email".');
            }

            Mail::to($contacto)->send(new PresupuestoMail($mailData, $carrito, $archivoPath));

            session()->forget('carrito_consulta');

            return redirect()->back()->with('success', '¡Tu consulta fue enviada correctamente! Te contactaremos pronto.');
        } catch (\Exception $e) {
            Log::error('Error al enviar correo de presupuesto: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Hubo un problema al enviar tu consulta. Por favor, intenta nuevamente más tarde.');
        }
    }
    private function verificarRecaptcha($token)
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $token,
            'remoteip' => request()->ip()
        ]);

        return $response->json();
    }
}
