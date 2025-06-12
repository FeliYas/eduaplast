<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use App\Models\Novedade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NovedadeController extends Controller
{
    public function index()
    {
        $novedades = Novedade::orderby('orden', 'asc')->get();
        $logo = Logo::where('seccion', 'dashboard')->first();
        return inertia('Admin/Novedades', [
            'novedades' => $novedades,
            'logo' => $logo
        ]);
    }
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'orden'                => 'required|string|max:255',
            'epigrafe'             => 'required|string|max:255',
            'titulo'               => 'required|string|max:255',
            'descripcion'          => 'required|string',
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
        // Crear la novedad con los datos validados
        $novedad = Novedade::create([
            'orden'              => $request->orden,
            'epigrafe'           => $request->epigrafe,
            'titulo'             => $request->titulo,
            'descripcion'        => $request->descripcion,
            'path'               => $imageName,
        ]);

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('novedades.dashboard')->with('message', 'Novedad creada exitosamente');
    }
    public function update(Request $request, $id)
    {

        // Validar los campos del formulario
        $validator = Validator::make($request->all(), [
            'orden'                => 'nullable|string|max:255',
            'epigrafe'             => 'nullable|string|max:255',
            'titulo'               => 'nullable|string|max:255',
            'descripcion'          => 'nullable|string',
            'path'                 => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ]);
        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }

        // Obtener el registro de Novedades
        $novedades = Novedade::findOrFail($id);

        if ($request->hasFile('path')) {
            if ($novedades->path && Storage::disk('public')->exists($novedades->path)) {
                Storage::disk('public')->delete($novedades->path);
            }
            // Generar un nombre único para la nueva imagen
            $imageName = uniqid() . '.' . $request->file('path')->getClientOriginalExtension();

            // Mover la imagen a la carpeta public/storage/images y obtener el nombre relativo
            $filePath = $request->file('path')->storeAs('images', $imageName, 'public');

            // Actualizar la ruta de la imagen
            $novedades->path = 'images/' . $imageName; // Guardamos la ruta relativa de la imagen
        }
        $novedades->orden              = $request->orden;
        $novedades->epigrafe           = $request->epigrafe;
        $novedades->titulo             = $request->titulo;
        $novedades->descripcion        = $request->descripcion;
        // Guardar los cambios en Novedades
        $novedades->save();

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('novedades.dashboard')->with('message', 'Novedad actualizada exitosamente');
    }

    public function destroy($id)
    {
        // Find the Novedades by id
        $novedades = Novedade::findOrFail($id);

        // Eliminar la imagen del almacenamiento si es necesario
        if ($novedades->path && Storage::disk('public')->exists($novedades->path)) {
            Storage::disk('public')->delete($novedades->path);
        }

        // Eliminar el registro de la base de datos
        $novedades->delete();

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('novedades.dashboard')->with('message', 'Novedad eliminada exitosamente');
    }
}
