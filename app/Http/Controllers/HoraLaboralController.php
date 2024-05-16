<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\HoraLaboral;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert; 

class HoraLaboralController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contratos = Contrato::all();
        $horas = HoraLaboral::all();

        $title = "Eliminar horas";
        $text = "¿Está seguro de eliminar la hora?";
        confirmDelete($title, $text);

        return view('horaslaborales.index', compact('horas', 'contratos'));
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

        $request -> validate([
               'contrato' => 'required',
               'horasdiamin' => 'required|regex:/^\d{1,3}$/', 
               'horasdiamax' => 'required|regex:/^\d{1,3}$/', 
               'horasmenmin' => 'required|regex:/^\d{1,3}$/', 
               'horasmenmax' => 'required|regex:/^\d{1,3}$/', 
        ]);

        $hora = HoraLaboral::create([
            'contract_id' => $request->contrato,
            'dh_min' => $request->horasdiamin,
            'dh_max' => $request->horasdiamax,
            'mh_min' => $request->horasmenmin,
            'mh_max' => $request->horasmenmax
        ]);

        // Mensaje de sesión para informar que se eliminó la condición
        Alert::toast('Se creó la hora ' . $request->name . ' exitosamente', 'success');

        return redirect()->route('horas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(HoraLaboral $hora)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HoraLaboral $hora)
    {
        $contratos = Contrato::all();

        return view('horaslaborales.edit', compact('hora', 'contratos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HoraLaboral $hora)
    {
        $hora->update([
            'contract_id' => $request->contrato,
            'dh_min' => $request->horasdiamin,
            'dh_max' => $request->horasdiamax,
            'mh_min' => $request->horasmenmin,
            'mh_max' => $request->horasmenmax
        ]);

        // Mensaje de sesión para informar que se eliminó la hora
        Alert::toast('Se editó la hora ' . $request->name . ' exitosamente', 'info');

        return redirect()->route('horas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HoraLaboral $hora)
    {
        $hora->delete();

        // Mensaje de sesión para informar que se eliminó la hora
        Alert::toast('Se eliminó la hora ' . $hora->name . ' exitosamente', 'warning');

        return redirect()->route('horas.index');
    }
}
