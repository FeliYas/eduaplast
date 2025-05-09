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
            return $this->error_response($validator->messages()->first());
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

        // Redirigir con mensaje de éxito
        return $this->success_response('Novedad creada exitosamente.');
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
            return $this->error_response($validator->messages()->first());
        }

        // Obtener el registro de Novedades
        $novedades = Novedade::findOrFail($id);

        // Manejar la actualización de la imagen principal (input "path")
        if ($request->hasFile('path')) {
            if ($novedades->path && Storage::disk('public')->exists($novedades->path)) {
                Storage::disk('public')->delete($novedades->path);
            }

            $file = $request->file('path');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('images', $fileName, 'public');
        } else {
            $filePath = $novedades->path; // Mantener la imagen anterior si no se sube una nueva
        }
        $novedades->orden              = $request->orden;
        $novedades->epigrafe           = $request->epigrafe;
        $novedades->titulo             = $request->titulo;
        $novedades->descripcion        = $request->descripcion;
        $novedades->path               = $filePath;
        // Guardar los cambios en Novedades
        $novedades->save();

        return $this->success_response('Novedad actualizada exitosamente.');
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

        // Redirect or return response
        return $this->success_response('Novedad eliminada exitosamente.');
    }
}
