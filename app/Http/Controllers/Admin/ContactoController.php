<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ContactoController extends Controller
{
    /**
     * Mostrar la página principal de contacto
     */
    public function index()
    {
        $contacto = Contacto::first();
        $logo = Logo::where('seccion', 'dashboard')->first();
        $mapa = $contacto->iframe;

        return Inertia::render('Admin/Contacto', [
            'contacto' => $contacto,
            'logo' => $logo,
            'mapa' => $mapa
        ]);
    }

    /**
     * Actualizar la información de contacto
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'iframe' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }

        $contacto = Contacto::findOrFail($id);

        $contacto->update([
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'iframe' => $request->iframe,
        ]);
        
        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('contacto.dashboard')->with('message', 'Contacto actualizado exitosamente');
    }
}
