<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LogoController extends Controller
{
    public function index()
    {
        $logos = Logo::all();
        $logo = Logo::where('seccion', 'dashboard')->first();

        return inertia('Admin/Logo', [
            'logos' => $logos,
            'logo' => $logo,
        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048', // Cambiado 'image' por 'file'
            'seccion' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->witherrors($validator->messages()->first());
        }
        $logo = Logo::find($id);
        if ($request->hasFile('path')) {
            if ($logo->path && Storage::disk('public')->exists($logo->path)) {
                Storage::disk('public')->delete($logo->path);
            }

            $file = $request->file('path');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('images', $fileName, 'public');
        } else {
            $filePath = $logo->path; // Mantener el path existente si no se envía un archivo
        }
        $logo->path = $filePath;
        $logo->seccion = $request->seccion ?? $logo->seccion;
        $logo->save();
        
        // Redireccionar al index con un mensaje de éxito
        return redirect()->route('logos.dashboard')->with('message', 'Logo actualizado exitosamente');
    }
}
