<?php

namespace App\Http\Controllers;

use App\Models\Phase;
use Illuminate\Http\Request;

class PhaseController extends Controller
{
    public function index()
    {
        $phases = Phase::orderBy('numero', 'asc')->get();
        
        // Determinar qué vista usar basado en la ruta
        if (request()->routeIs('superadmin.phases.index')) {
            return view('superadmin.phases.index', compact('phases'));
        } else {
            return view('aprendiz.phases.index', compact('phases'));
        }
    }

    public function create()
    {
        return view('superadmin.phases.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|integer|min:1',           // ✅ NUMERO
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        Phase::create($request->only(['numero', 'fecha_inicio', 'fecha_fin']));

        return redirect()->route('superadmin.phases.index')
                        ->with('success', 'Fase creada exitosamente.');
    }

    public function edit(Phase $phase)
    {
        return view('superadmin.phases.edit', compact('phase'));
    }

    public function update(Request $request, Phase $phase)
    {
        $request->validate([
            'numero' => 'required|integer|min:1',           // ✅ NUMERO
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $phase->update($request->only(['numero', 'fecha_inicio', 'fecha_fin']));

        return redirect()->route('superadmin.phases.index')
                        ->with('success', 'Fase actualizada exitosamente.');
    }

    public function destroy(Phase $phase)
    {
        $phase->delete();

        return redirect()->route('superadmin.phases.index')
                        ->with('success', 'Fase eliminada exitosamente.');
    }

    public function show(Phase $phase)
    {
        // Determinar qué vista usar basado en la ruta
        if (request()->routeIs('superadmin.phases.show')) {
            return view('superadmin.phases.show', compact('phase'));
        } else {
            return view('aprendiz.phases.show', compact('phase'));
        }
    }
}