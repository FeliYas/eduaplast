<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use App\Models\Sectore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SectoreController extends Controller
{
    public function index()
    {
        $sectores = Sectore::orderby('orden', 'asc')->get();
        $logo = Logo::where('seccion', 'dashboard')->first();
        return inertia('Admin/Sectores', [
            'sectores' => $sectores,
            'logo' => $logo
        ]);
    }
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'orden'                => 'required|string|max:255',
            'titulo'               => 'required|string|max:255',
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
        // Crear la sector con los datos validados
        $sector = Sectore::create([
            'orden'              => $request->orden,
            'titulo'             => $request->titulo,
            'path'               => $imageName,
        ]);

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('sectores.dashboard')->with('message', 'Sector creado exitosamente');
    }
    public function update(Request $request, $id)
    {

        // Validar los campos del formulario
        $validator = Validator::make($request->all(), [
            'orden'                => 'nullable|string|max:255',
            'titulo'               => 'nullable|string|max:255',
            'path'                 => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ]);
        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }

        // Obtener el registro de sector
        $sector = Sectore::findOrFail($id);

         if ($request->hasFile('path')) {
            if ($sector->path && Storage::disk('public')->exists($sector->path)) {
                Storage::disk('public')->delete($sector->path);
            }
            // Generar un nombre único para la nueva imagen
            $imageName = uniqid() . '.' . $request->file('path')->getClientOriginalExtension();

            // Mover la imagen a la carpeta public/storage/images y obtener el nombre relativo
            $filePath = $request->file('path')->storeAs('images', $imageName, 'public');

            // Actualizar la ruta de la imagen
            $sector->path = 'images/' . $imageName; // Guardamos la ruta relativa de la imagen
        }
        $sector->orden              = $request->orden;
        $sector->titulo             = $request->titulo;
        // Guardar los cambios en sector
        $sector->save();

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('sectores.dashboard')->with('message', 'Sector actualizado exitosamente');
    }

    public function destroy($id)
    {
        // Find the sector by id
        $sector = Sectore::findOrFail($id);

        // Eliminar la imagen del almacenamiento si es necesario
        if ($sector->path && Storage::disk('public')->exists($sector->path)) {
            Storage::disk('public')->delete($sector->path);
        }

        // Eliminar el registro de la base de datos
        $sector->delete();

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('sectores.dashboard')->with('message', 'Sector eliminado exitosamente');
    }
}
