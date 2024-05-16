<?php

namespace App\Http\Controllers;

use App\Models\Competencia;
use App\Models\Componente;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CompetenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalcompetencias = Competencia::count('id');
        $competencias = Competencia::orderBy('id', 'asc')->paginate(5);
        $componentes = Componente::all();

        return view('competencias.index', compact('totalcompetencias', 'competencias', 'componentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|min:1|max:10|regex:/^\d{1,10}$/|',
            'name' => 'required|min:4|max:100|regex:/^[a-zA-ZÀ-ÿ\s]{4,100}$/|unique:skills',
            'descripcion' => 'required|min:4|max:200|regex:/^[a-zA-ZÀ-ÿ\s]{4,200}$/',
            'componente' => 'required'
        ]);

        $competencia = Competencia::create([
            'number' => $request->numero,
            'name' => $request->name,
            'description' => $request->descripcion,
            'component_id' => $request->componente
        ]);

        // Mensaje de sesión para informar que se creó la condición
        Alert::toast('Se creó la competencia' . $request->name . ' exitosamente', 'success');

        return redirect()->route('competencias.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Competencia $competencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Competencia $competencia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Competencia $competencia)
    {
        $request->validate([
            'numero' => 'required|min:1|max:10|regex:/^\d{1,10}$/|',
            'name' => 'required|min:4|max:100|regex:/^[a-zA-ZÀ-ÿ\s]{4,100}$/|unique:skills',
            'descripcion' => 'required|min:4|max:200|regex:/^[a-zA-ZÀ-ÿ\s]{4,200}$/',
            'componente' => 'required'
        ]);

        $competencia->update([
            'number' => $request->numero,
            'name' => $request->name,
            'description' => $request->descripcion,
            'component_id' => $request->componente
        ]);

        // Mensaje de sesión para informar que se editó la competencia
        Alert::toast('Se editó la competencia' . $request->name . ' exitosamente', 'success');

        return redirect()->route('competencias.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Competencia $competencia)
    {
        $competencia->delete();

        // Mensaje de sesión para informar que se eliminó la competencia
        Alert::toast('Se eliminó la competencia' . $competencia->name, 'warning');

        return redirect()->route('competencias.index');
    }
}
