<?php

namespace App\Http\Controllers;

use App\Models\Condicion;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class CondicionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Variable para contar el total de condiciones
        $totalcondiciones = Condicion::count('id');
        $totalcondicionesH = Condicion::where("state",true)->count('id');
        $totalcondicionesD = Condicion::where("state",false)->count('id');

        // Redireccionar a la lista de condiciones
        return view('condiciones.index', compact('totalcondiciones','totalcondicionesH','totalcondicionesD'));
    }

    public function listar()
    {
        $j = [];

        try {
            $condiciones = DB::table('conditions')->select('id', 'name', 'description','state')->get();
            $j['success'] = true;
            $j['message'] = 'Consulta exitosa';
            $j['data'] = $condiciones;
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
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
        $j = [];
        $request->validate([
            'nombre' => 'required|min:4|max:30|regex:/^[a-zA-ZÀ-ÿ\s]{4,30}$/',
            'descripcion' => 'required|min:8|max:200|regex:/^[a-zA-ZÀ-ÿ\s]{8,200}$/',
        ]);
        try {
            $url = route('condiciones.index');
            $condicion = DB::table('conditions')->where('name', $request->input('nombre'))->exists();

            if ($condicion) {
                $j['success'] = false;
                $j['message'] = 'La condición ya existe';
                $j['code'] = 505;
            } else {
                $condicion = Condicion::create([
                    'name' => $request->nombre,
                    'description' => $request->descripcion,
                ]);

                Alert::toast('Se creó la condición ' . $request->nombre . ' exitosamente', 'success');
                $j['success'] = true;
                $j['message'] = 'Se creó la condición exitosamente';
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
    public function show(Condicion $condicion)
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
            $condiciones = DB::table('conditions')
                ->select('id', 'name', 'description')
                ->where('id', $id)
                ->get();

            $j['success'] = true;
            $j['data'] = $condiciones;
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
            'nombre' => 'required|min:4|max:30|regex:/^[a-zA-ZÀ-ÿ\s]{4,30}$/',
            'descripcion' => 'required|min:8|max:200|regex:/^[a-zA-ZÀ-ÿ\s]{8,200}$/',
        ]);

        try {
            $url = route('condiciones.index');

            $condicion = Condicion::findOrFail($request->input('id'));

            if (Condicion::where('name', $request->input('nombre'))
                ->where('id', '!=', $condicion->id)
                ->exists()
            ) {
                $j = [
                    'success' => false,
                    'message' => 'La condición ya existe',
                    'code' => 400,
                ];
            } else {
                $condicion->update([
                    'name' => $request->input('nombre'),
                    'description' => $request->input('descripcion')
                ]);
                Alert::toast('Se editó la condición ' . $request->nombre . ' exitosamente', 'info');
                $j = [
                    'success' => true,
                    'message' => 'Condición actualizada',
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $j = [];

        try {
            $condicion = Condicion::findOrFail($id);
            $condicion->delete();
            Alert::toast('Se eliminó la condición', 'warning');
            $j['success'] = true;
            $j['message'] = 'condición eliminada';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    ///Cambiar estado de condiciones de contrato
    public function changeState($id)
    {

        $j = [];

        try {
            $condiciones = Condicion::findOrFail($id);
            if($condiciones->state ===true){
                $condiciones->update([
                    'state'=> false
                ]);
                Alert::toast('Se deshabilito la condición de contrato', 'warning');
                $j['title'] = 'Condición de contrato deshabilitada';
                $j['success'] = true;
                $j['message'] = 'Condición de contrato deshabilitada para su uso';
                $j['code'] = 200;
            } else {
                $condiciones->update([
                    'state'=> true
                ]);
                Alert::toast('Se habilito la condición de contrato', 'warning');
                $j['title'] = 'Condición de contrato habilitada';
                $j['success'] = true;
                $j['message'] = 'Condición de contrato habilitada para su uso';
                $j['code'] = 200;
            }

        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);

    }
}
