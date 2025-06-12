<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use App\Models\Matriceria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MatriceriaController extends Controller
{
    public function index()
    {
        $matriceria = Matriceria::first();
        $logo = Logo::where('seccion', 'dashboard')->first();
        return inertia('Admin/Matriceria', [
            'matriceria' => $matriceria,
            'logo' => $logo
        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'nullable|mimes:jpeg,png,jpg,gif,svg,mp4,avi,mov|max:100000',
            'titulo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|required',
        ]);
        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }
        $matriceria = Matriceria::findOrFail($id);

        $matriceria->titulo = $request->titulo;
        $matriceria->descripcion = $request->descripcion;

        if ($request->hasFile('path')) {
            if ($matriceria->path && Storage::disk('public')->exists($matriceria->path)) {
                Storage::disk('public')->delete($matriceria->path);
            }
            // Generar un nombre único para la nueva imagen
            $imageName = uniqid() . '.' . $request->file('path')->getClientOriginalExtension();

            // Mover la imagen a la carpeta public/storage/images y obtener el nombre relativo
            $filePath = $request->file('path')->storeAs('images', $imageName, 'public');

            // Actualizar la ruta de la imagen
            $matriceria->path = 'images/' . $imageName; // Guardamos la ruta relativa de la imagen
        }
        $matriceria->save();

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('matriceria.dashboard')->with('message', 'Matriceria actualizada exitosamente');
    }
}
