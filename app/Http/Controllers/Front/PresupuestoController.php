<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
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

        $carrito = session()->get('carrito_consulta', []);

        if ($productoId) {
            $producto = Producto::find($productoId);
            if ($producto) {
                $carrito[$producto->id] = $producto;
                session(['carrito_consulta' => $carrito]);
            }
        }
        return view('front.presupuesto', compact('logos', 'contactos', 'carrito'));

    }

    public function agregarProductoConsulta(Request $request)
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

    public function eliminarProductoConsulta($id)
    {
        $carrito = session()->get('carrito_consulta', []);

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session(['carrito_consulta' => $carrito]);
        }

        return redirect()->route('presupuesto');
    }

    public function enviarConsulta(Request $request)
    {
        if ($request->input('carrito_vacio') == 1) {
            return redirect()->back()->with('error', 'No hay productos seleccionados en el carrito. Seleccioná productos para enviar la consulta.');
        }

        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'telefono' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'provincia' => 'required|string|max:255',
                'localidad' => 'required|string|max:255',
                'categoria' => 'required|string|max:255',
                'g-recaptcha-response' => 'required',
            ], [
                'required' => 'El campo :attribute es obligatorio',
                'email' => 'El campo debe ser un email válido',
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

            $cantidades = $request->input('cantidades', []);

            $mailData = [
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'provincia' => $request->provincia,
                'localidad' => $request->localidad,
                'categoria' => $request->categoria,
                'mensaje' => $request->mensaje,
                'productos' => [],
                'fecha' => now()->format('d/m/Y H:i:s'),
            ];


            foreach ($carrito as $producto) {
                $cantidad = isset($cantidades[$producto->id]) ? $cantidades[$producto->id] : 1;

                $mailData['productos'][] = [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'imagen' => $producto->imagen,
                    'cantidad' => $cantidad,
                ];
            }

            Mail::to(config('felipe3456lk@gmail.com'))->send(new ConsultaProductos($mailData));

            session()->forget('carrito_consulta');

            return redirect()->back()->with('success', '¡Tu consulta fue enviada correctamente! Te contactaremos pronto.');
        } catch (\Exception $e) {
            Log::error('Error al enviar correo de consulta: ' . $e->getMessage());

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                throw $e;
            }

            return redirect()->back()->with('error', 'Hubo un problema al enviar tu consulta. Por favor, intenta nuevamente más tarde.')
                ->withInput();
        }
    }
}
