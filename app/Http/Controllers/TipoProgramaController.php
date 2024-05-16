<?php

namespace App\Http\Controllers;

use App\Models\TipoPrograma;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class TipoProgramaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totaltipos = TipoPrograma::count('id');

        return view('tipoprogramas.index', compact('totaltipos'));
    }

    public function listar()
    {
        $j = [];

        try {
            $tiposp = DB::table('program_type')->select('id', 'name', 'state')->get();
            $j['success'] = true;
            $j['message'] = 'Consulta exitosa';
            $j['data'] = $tiposp;
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
        ]);
        try {
            $url = route('tiposprograma.index');
            $tiposp = DB::table('program_type')->where('name', $request->input('nombre'))->exists();

            if ($tiposp) {
                $j['success'] = false;
                $j['message'] = 'El tipo de programa ya existe';
                $j['code'] = 505;
            } else {
                $tiposp = TipoPrograma::create([
                    'name' => $request->input('nombre')
                ]);

                Alert::toast('Se creó el tipo programa ' . $request->nombre . ' exitosamente', 'success');
                $j['success'] = true;
                $j['message'] = 'Se creó el tipo de componente exitosamente';
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
    public function show(TipoPrograma $tipoPrograma)
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
            $tiposp = DB::table('program_type')
                ->select('id', 'name')
                ->where('id', $id)
                ->get();

            $j['success'] = true;
            $j['data'] = $tiposp;
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
    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required|min:4|max:20|regex:/^[a-zA-ZÀ-ÿ\s]{4,20}$/',
        ]);

        try {
            $url = route('tiposprograma.index');

            $tipoPrograma = TipoPrograma::findOrFail($request->input('id'));

            if (TipoPrograma::where('name', $request->input('nombre'))
                ->where('id', '!=', $tipoPrograma->id)
                ->exists()
            ) {
                $j = [
                    'success' => false,
                    'message' => 'El tipo programa ya existe',
                    'code' => 400,
                ];
            } else {
                $tipoPrograma->update([
                    'name' => $request->input('nombre')
                ]);
                Alert::toast('Se editó el tipo programa ' . $request->nombre . ' exitosamente', 'info');
                $j = [
                    'success' => true,
                    'message' => 'Tipo Programa actualizado',
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

    /* Change State */
    public function state($id)
    {
        $j = [];

        try {
            $lastState = DB::table('program_type')->where('id', $id)->select('state')->first(); // Last program type state

            if (!$lastState->state) {
                DB::table('program_type')->where('id', $id)->update(['state' => true]); //Update state to true
            }
            else
            {
                DB::table('program_type')->where('id', $id)->update(['state' => false]); //Update state to false
            }

            $message = "El estado del programa paso a " . (!$lastState ? "habilitado" : "deshabilitado");

            $j['success'] = true;
            $j['title'] = "Cambio Exitoso";
            $j['message'] = $message;
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
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
            $tiposp = TipoPrograma::findOrFail($id);
            $tiposp->delete();
            Alert::toast('Se eliminó el tipo de programa', 'warning');
            $j['success'] = true;
            $j['message'] = 'Tipo programa eliminado';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }
}
