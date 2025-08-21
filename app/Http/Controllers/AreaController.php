<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::orderBy('nombre')->get();
        return view('superadmin.areas.index', compact('areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
        ]);

        $data = $validated;
        // Checkbox: si viene marcado existe en el request
        $data['activo'] = $request->has('activo');

        Area::create($data);

        return redirect()->back()->with('success', 'Área creada correctamente.');
    }

    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
        ]);

        $data = $validated;
        $data['activo'] = $request->has('activo');

        $area->update($data);

        return redirect()->back()->with('success', 'Área actualizada correctamente.');
    }

    public function destroy(Area $area)
    {
        $area->delete();
        return redirect()->back()->with('success', 'Área eliminada correctamente.');
    }

    // =================== MÉTODOS PARA ADMIN ===================
    
    public function adminIndex()
    {
        $areas = Area::orderBy('nombre')->get();
        return view('admin.areas.index', compact('areas'));
    }

    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
        ]);

        $data = $validated;
        $data['activo'] = $request->boolean('activo');

        Area::create($data);

        return redirect()->back()->with('success', 'Área creada correctamente.');
    }

    public function adminUpdate(Request $request, Area $area)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
        ]);

        $data = $validated;
        $data['activo'] = $request->boolean('activo');

        $area->update($data);

        return redirect()->back()->with('success', 'Área actualizada correctamente.');
    }

    public function adminDestroy(Area $area)
    {
        $area->delete();
        return redirect()->back()->with('success', 'Área eliminada correctamente.');
    }
}


