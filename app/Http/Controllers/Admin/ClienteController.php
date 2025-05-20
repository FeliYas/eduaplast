<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderby('orden', 'asc')->get();
        $logo = Logo::where('seccion', 'dashboard')->first();
        return inertia('Admin/Clientes', [
            'clientes' => $clientes,
            'logo' => $logo
        ]);
    }
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'orden'                => 'required|string|max:255',
            'path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }

        $imageName = null;
        if ($request->hasFile('path')) {
            $file = $request->file('path');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $imageName = $file->storeAs('images', $fileName, 'public');
        }
        // Crear la cliente con los datos validados
        $cliente = Cliente::create([
            'orden'              => $request->orden,
            'path'               => $imageName,
        ]);

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('clientes.dashboard')->with('message', 'Cliente creado exitosamente');
    }
    public function update(Request $request, $id)
    {

        // Validar los campos del formulario
        $validator = Validator::make($request->all(), [
            'orden'                => 'nullable|string|max:255',
            'path'                 => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ]);
        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }

        // Obtener el registro de cliente
        $cliente = Cliente::findOrFail($id);

        if ($request->hasFile('path')) {
            if ($cliente->path && Storage::disk('public')->exists($cliente->path)) {
                Storage::disk('public')->delete($cliente->path);
            }
            // Generar un nombre único para la nueva imagen
            $imageName = uniqid() . '.' . $request->file('path')->getClientOriginalExtension();

            // Mover la imagen a la carpeta public/storage/images y obtener el nombre relativo
            $filePath = $request->file('path')->storeAs('images', $imageName, 'public');

            // Actualizar la ruta de la imagen
            $cliente->path = 'images/' . $imageName; // Guardamos la ruta relativa de la imagen
        }
        $cliente->orden              = $request->orden;
        // Guardar los cambios en cliente
        $cliente->save();

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('clientes.dashboard')->with('message', 'Cliente actualizado exitosamente');
    }

    public function destroy($id)
    {
        // Find the cliente by id
        $cliente = Cliente::findOrFail($id);

        // Eliminar la imagen del almacenamiento si es necesario
        if ($cliente->path && Storage::disk('public')->exists($cliente->path)) {
            Storage::disk('public')->delete($cliente->path);
        }

        // Eliminar el registro de la base de datos
        $cliente->delete();

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('clientes.dashboard')->with('message', 'Cliente eliminado exitosamente');
    }
}
