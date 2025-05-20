<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use App\Models\Producto;
use App\Models\ProductoImg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductoImgController extends Controller
{
    public function index($id)
    {
        $producto = Producto::findOrFail($id);
        $imagenes = ProductoImg::where('producto_id', $id)->orderBy('orden', 'asc')->get();
        $logo = Logo::where('seccion', 'dashboard')->first();
        return inertia('Admin/Imagenes', [
            'imagenes' => $imagenes,
            'producto' => $producto,
            'logo' => $logo
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'producto_id' => 'required|exists:productos,id',
            'path' => 'required|mimes:jpeg,png,jpg,gif|max:2048',
            'orden' => 'required|string|max:255',
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
        $imagen = ProductoImg::create([
            'orden'              => $request->orden,
            'path'               => $imageName,
            'producto_id'        => $request->producto_id,
        ]);
        
        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('imagenes.dashboard', ['id' => $request->producto_id])->with('message', 'Imagen agregada exitosamente');
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
            'orden' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }
        $productoImg = ProductoImg::findOrFail($id);
        if ($request->hasFile('path')) {
            if ($productoImg->path && Storage::disk('public')->exists($productoImg->path)) {
                Storage::disk('public')->delete($productoImg->path);
            }
            $file = $request->file('path');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $imageName = $file->storeAs('images', $fileName, 'public');
            $productoImg->path = $imageName;
        }
        // Solo se actualizan los campos si el request contiene un valor
        $productoImg->update(array_filter([
            'orden' => $request->orden,
        ]));
        
        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('imagenes.dashboard', ['id' => $productoImg->producto_id])->with('message', 'Imagen actualizada exitosamente');
    }
    public function destroy($id)
    {
        $productoImg = ProductoImg::findOrFail($id);
        if ($productoImg->path && Storage::disk('public')->exists($productoImg->path)) {
            Storage::disk('public')->delete($productoImg->path);
        }
        $productoImg->delete();

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('imagenes.dashboard', ['id' => $productoImg->producto_id])->with('message', 'Imagen eliminada exitosamente');
    }
}
