<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Logo;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::orderBy('orden', 'asc')->get();
        $categorias = Categoria::orderBy('orden', 'asc')->get();
        $logo = Logo::where('seccion', 'dashboard')->first();
        return inertia('Admin/Productos', [
            'productos' => $productos,
            'categorias' => $categorias,
            'logo' => $logo
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orden' => 'required|string|max:255',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria_id' => 'required',
            'ficha' => 'nullable|mimes:pdf|max:2048',
        ]);
        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }
        $fichaPath = null;
        if ($request->hasFile('ficha')) {
            $ficha = $request->file('ficha');
            $fichaName = uniqid() . '.' . $ficha->getClientOriginalExtension();
            $fichaPath = $ficha->storeAs('fichas', $fichaName, 'public');
        }
        $producto = Producto::create([
            'orden' => $request->orden,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'categoria_id' => $request->categoria_id,
            'ficha' => $fichaPath
        ]);

        
        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('productos.dashboard')->with('message', 'Producto creado exitosamente');
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'orden' => 'required|string|max:255',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria_id' => 'required',
            'ficha' => 'nullable|mimes:pdf|max:2048',
        ]);
        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }
        $producto = Producto::find($id);

        if ($request->hasFile('ficha')) {
            if ($producto->ficha && Storage::disk('public')->exists($producto->ficha)) {
                Storage::disk('public')->delete($producto->ficha);
            }

            $ficha = $request->file('ficha');
            $fichaName = uniqid() . '.' . $ficha->getClientOriginalExtension();
            $fichaPath = $ficha->storeAs('fichas', $fichaName, 'public');
            $producto->ficha = $fichaPath;
        }

        // Solo se actualizan los campos si el request contiene un valor
        $producto->update(array_filter([
            'orden' => $request->orden,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'categoria_id' => $request->categoria_id,
        ]));

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('productos.dashboard')->with('message', 'Producto actualizado exitosamente');
    }

    public function destroy($id)
    {
        // Find the Porducto by id
        $producto = Producto::findOrFail($id);
        // Eliminar la imagen del almacenamiento si es necesario
        if ($producto->ficha && Storage::disk('public')->exists($producto->ficha)) {
            Storage::disk('public')->delete($producto->ficha);
        }
        // Eliminar el registro de la base de datos
        $producto->delete();

        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('productos.dashboard')->with('message', 'Producto eliminado exitosamente');
    }
}
