<?php

namespace App\Http\Controllers;

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
            return $this->error_response($validator->messages()->first());
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

        // Redirigir con mensaje de éxito
        return $this->success_response('Sector creado exitosamente.');
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
            return $this->error_response($validator->messages()->first());
        }

        // Obtener el registro de sector
        $sector = Sectore::findOrFail($id);

        // Manejar la actualización de la imagen principal (input "path")
        if ($request->hasFile('path')) {
            if ($sector->path && Storage::disk('public')->exists($sector->path)) {
                Storage::disk('public')->delete($sector->path);
            }

            $file = $request->file('path');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('images', $fileName, 'public');
        } else {
            $filePath = $sector->path; // Mantener la imagen anterior si no se sube una nueva
        }
        $sector->orden              = $request->orden;
        $sector->titulo             = $request->titulo;
        $sector->path               = $filePath;
        // Guardar los cambios en sector
        $sector->save();

        return $this->success_response('Sector actualizado exitosamente.');
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

        // Redirect or return response
        return $this->success_response('Sector eliminado exitosamente.');
    }
}
