<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use App\Models\Sede;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SedeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Variable para contar el total de sedes
        $totalsedes = Sede::count('id');

        // Redireccionar a la vista preferida
        return view('sedes.index', compact('totalsedes'));
    }

    public function listar()
    {
        $j = [];

        try {
            $sedes = Sede::select('id', 'name', 'adress', 'environment_capacity', 'floors','state')->get();
            $j['success'] = true;
            $j['message'] = 'Consulta exitosa';
            $j['data'] = $sedes;
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $j = [];
        $request->validate([
            'nombre' => 'required|min:4|max:20|regex:/^[a-zA-ZÀ-ÿ\s]{4,20}$/',
            'direccion' => 'required|min:4|max:100|regex:/^[a-zA-ZÀ-ÿ\s0-9-#]{4,100}$/',
            'ambientes' => 'required|min:1|max:4|regex:/^\d{1,4}$/'
        ]);
        try {
            $url = route('sedes.index');
            $sede = DB::table('headquarters')->where('name', $request->input('nombre'))->exists();

            if ($sede) {
                $j['success'] = false;
                $j['message'] = 'La sede ya existe';
                $j['code'] = 505;
            } else {
                $sede = Sede::create([
                    'name' => $request->input('nombre'),
                    'adress' => $request->input('direccion'),
                    'environment_capacity' => $request->input('ambientes'),
                    'floors' => $request->input('pisos')
                ]);

                Alert::toast('Se creó la sede ' . $request->nombre . ' exitosamente', 'success');
                $j['success'] = true;
                $j['message'] = 'Se creó la sede exitosamente';
                $j['code'] = 200;
                $j['url'] = $url;
            }
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $j = [];

        try {
            $sedes = DB::table('headquarters')
                ->select(
                    'id as id',
                    'name as name',
                    'adress as adress',
                    'environment_capacity as environment',
                    'floors as floors'
                )
                ->where('id', $id)
                ->get();

            $j['success'] = true;
            $j['data'] = $sedes;
            $j['message'] = 'Consulta exitosa';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sede $sede)
    {


        $request->validate([
            'nombre' => 'required|min:4|max:20|regex:/^[a-zA-ZÀ-ÿ\s]{4,20}$/',
            'direccion' => 'required|min:4|max:100|regex:/^[a-zA-ZÀ-ÿ\s0-9-#]{4,100}$/',
            'ambientes' => 'required|min:1|max:4|regex:/^\d{1,4}$/'
        ]);

        try {
            $url = route('sedes.index');

            $sede = Sede::findOrFail($request->input('id'));
            $count = DB::table('environments')->where('headquarter_id', $request->input('id'))->count();

            if (Sede::where('name', $request->input('nombre'))
                ->where('id', '!=', $sede->id)
                ->exists()
            ) {
                $j = [
                    'success' => false,
                    'message' => 'La sede ya existe',
                    'code' => 400,
                ];
            } else if ($request->input('ambientes') < $count) {
                $j = [
                    'success' => false,
                    'message' => 'El numero de ambientes no puede ser menor al conteo de ambientes ya registrados en esta sede',
                    'code' => 400,
                ];
            } else {
                $sede->update([
                    'name' => $request->input('nombre'),
                    'adress' => $request->input('direccion'),
                    'environment_capacity' => $request->input('ambientes'),
                ]);
                Alert::toast('Se editó la sede ' . $request->nombre . ' exitosamente', 'info');
                $j = [
                    'success' => true,
                    'message' => 'Sede actualizada',
                    'url' => $url,
                    'code' => 200,
                ];
            }
        } catch (\Throwable $th) {
            $j = [
                'success' => false,
                'message' => $th->getMessage(),
                'code' => 500,
            ];
        }
        return response()->json($j);
    }

    /* STATE */

    public function state($id)
    {
        $j = [];

        try {
            $lastState = trim(DB::table('headquarters')->where('id', $id)->value('state'));

            if ($lastState == 'activo') {
                DB::table('headquarters')->where('id', $id)->update(['state' => 'deshabilitado']);
            } else if ($lastState == 'deshabilitado') {
                DB::table('headquarters')->where('id', $id)->update(['state' => 'activo']);
            }

            $state = $lastState === "activo" ? 'Deshabilitada' : 'Habilitada' ;

            // Message
            Alert::toast('Se cambio el estado exitosamente', 'success');
            $j['icon'] = 'success';
            $j['title'] = 'Sede ' . $state;
            $j['message'] = 'El cambio de la sede a ' . strtolower ($state) . " fue exitoso";
            $j['code'] = 200;
            $j['success'] = true;

        } catch (\Throwable $th) {
            $j['icon'] = 'error';
            $j['title'] = 'Hubo error';
            //$j['message'] = 'Por favor, contactese con soporte, hubo un error en la eliminación del ambiente';
            Log::error($th->getMessage());
            $j['message'] = $th->getMessage();
            $j['success'] = false;
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $j = [];

        try {
            $sede = Sede::findOrFail($id);
            // $name = Rol::select('name')->where('id', '=', $id);
            $sede->delete();
            Alert::toast('Se eliminó la sede', 'warning');
            $j['success'] = true;
            $j['message'] = 'Sede eliminada';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }
}
