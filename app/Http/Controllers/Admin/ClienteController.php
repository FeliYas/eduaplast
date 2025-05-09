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
            return $this->error_response($validator->messages()->first());
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

        // Redirigir con mensaje de éxito
        return $this->success_response('Cliente creado exitosamente.');
    }
    public function update(Request $request, $id)
    {
        
        // Validar los campos del formulario
        $validator = Validator::make($request->all(), [
            'orden'                => 'nullable|string|max:255',
            'path'                 => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ]);
        if ($validator->fails()) {
            return $this->error_response($validator->messages()->first());
        }

        // Obtener el registro de cliente
        $cliente = Cliente::findOrFail($id);

        // Manejar la actualización de la imagen principal (input "path")
        if ($request->hasFile('path')) {
            if ($cliente->path && Storage::disk('public')->exists($cliente->path)) {
                Storage::disk('public')->delete($cliente->path);
            }

            $file = $request->file('path');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('images', $fileName, 'public');
        } else {
            $filePath = $cliente->path; // Mantener la imagen anterior si no se sube una nueva
        }
        $cliente->orden              = $request->orden;
        $cliente->path               = $filePath;
        // Guardar los cambios en cliente
        $cliente->save();

        return $this->success_response('Cliente actualizado exitosamente.');
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

        // Redirect or return response
        return $this->success_response('Cliente eliminado exitosamente.');
    }
}
