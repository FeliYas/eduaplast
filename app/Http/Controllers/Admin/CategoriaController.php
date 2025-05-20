<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('orden', 'asc')->get();
        $logo = Logo::where('seccion', 'dashboard')->first();
        return inertia('Admin/Categorias', [
            'categorias' => $categorias,
            'logo' => $logo
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orden' => 'required|string|max:255',
            'titulo' => 'required|string|max:255',
            'path' => 'mimes:jpeg,png,jpg,gif,svg,mp4,avi,mov|max:2048'
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
        // Crear la categoria con los datos validados
        $categoria = Categoria::create([
            'orden'              => $request->orden,
            'titulo'             => $request->titulo,
            'path'               => $imageName,
        ]);

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('categorias.dashboard')->with('message', 'Categoria creada exitosamente');
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'orden' => 'nullable|string|max:255',
            'titulo' => 'nullable|string|max:255',
            'path' => 'nullable|mimes:jpeg,png,jpg,gif,svg,mp4,avi,mov|max:2048'
        ]);
        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }
        $categoria = Categoria::findOrFail($id);

        $categoria->titulo = $request->titulo;
        $categoria->orden = $request->orden;

        if ($request->hasFile('path')) {
            if ($categoria->path && Storage::disk('public')->exists($categoria->path)) {
                Storage::disk('public')->delete($categoria->path);
            }
            // Generar un nombre único para la nueva imagen
            $imageName = uniqid() . '.' . $request->file('path')->getClientOriginalExtension();

            // Mover la imagen a la carpeta public/storage/images y obtener el nombre relativo
            $filePath = $request->file('path')->storeAs('images', $imageName, 'public');

            // Actualizar la ruta de la imagen
            $categoria->path = 'images/' . $imageName; // Guardamos la ruta relativa de la imagen
        }
        $categoria->save();

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('categorias.dashboard')->with('message', 'Categoria actualizada exitosamente');
    }
    public function destroy($id)
    {
        // Find the Categoria by id
        $categoria = Categoria::findOrFail($id);

        // Eliminar la imagen del almacenamiento si es necesario
        if ($categoria->path && Storage::disk('public')->exists($categoria->path)) {
            Storage::disk('public')->delete($categoria->path);
        }

        // Eliminar el registro de la base de datos
        $categoria->delete();

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('categorias.dashboard')->with('message', 'Categoria eliminada exitosamente');
    }
    public function toggleDestacado(Request $request)
    {
        $categoria = Categoria::findOrFail($request->id);
        $categoria->destacado = $request->destacado ? 1 : 0;
        $categoria->save();

        return response()->json(['success' => true]);
    }
}
