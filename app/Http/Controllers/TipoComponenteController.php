<?php

namespace App\Http\Controllers;

use App\Models\TipoComponente;
use App\Models\TipoPrograma;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class TipoComponenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totaltipos = TipoComponente::count('id');

        return view('tipocomponentes.index', compact('totaltipos'));
    }

    public function listar()
    {
        $j = [];

        try {
            $tipoc = DB::table('components_type')->select('id', 'name','state')->get();
            $j['success'] = true;
            $j['message'] = 'Consulta exitosa';
            $j['data'] = $tipoc;
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
            'nombre' => 'required|min:4|max:20|regex:/^[a-zA-ZÀ-ÿ\s]{4,20}$/'
        ]);
        try {
            $url = route('tipos.index');
            $tipo = DB::table('components_type')->where('name', $request->input('nombre'))->exists();

            if ($tipo) {
                $j['success'] = false;
                $j['message'] = 'El tipo de componente ya existe';
                $j['code'] = 505;
            } else {
                $tipo = TipoComponente::create([
                    'name' => $request->input('nombre')
                ]);

                Alert::toast('Se creó el tipo de componente ' . $request->nombre . ' exitosamente', 'success');
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
    public function show(TipoComponente $tipoComponente)
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
            $tipoc = DB::table('components_type')
                ->select('id', 'name')
                ->where('id', $id)
                ->get();

            $j['success'] = true;
            $j['data'] = $tipoc;
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
            'nombre' => 'required|min:4|max:20|regex:/^[a-zA-ZÀ-ÿ\s]{4,20}$/'
        ]);

        try {
            $url = route('tipos.index');

            $tipoc = TipoComponente::findOrFail($request->input('id'));

            if (TipoComponente::where('name', $request->input('nombre'))
                ->where('id', '!=', $tipoc->id)
                ->exists()
            ) {
                $j = [
                    'success' => false,
                    'message' => 'El tipo componente ya existe',
                    'code' => 400,
                ];
            } else {
                $tipoc->update([
                    'name' => $request->input('nombre')
                ]);
                Alert::toast('Se editó el tipo componente ' . $request->nombre . ' exitosamente', 'info');
                $j = [
                    'success' => true,
                    'message' => 'Tipo Componente actualizado',
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
    public function changeState($id)
    {

        $j = [];

        try {
            $tiposComponentes = TipoComponente::findOrFail($id);
            if($tiposComponentes->state ===true){
                $tiposComponentes->update([
                    'state'=> false
                ]);
                Alert::toast('Se deshabilito el tipo de componente', 'warning');
                $j['title'] = 'Tipo de componente deshabilitado';
                $j['success'] = true;
                $j['message'] = 'Tipo de componente deshabilitado para su uso';
                $j['code'] = 200;
            } else {
                $tiposComponentes->update([
                    'state'=> true
                ]);
                Alert::toast('Se habilito el tipo de componente', 'warning');
                $j['title'] = 'Tipo de componente habilitado';
                $j['success'] = true;
                $j['message'] = 'Tipo de componente habilitado para su uso';
                $j['code'] = 200;
            }

        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);




        // $j = [];

        // try {
        //     $tipoc = TipoComponente::findOrFail($id);
        //     $tipoc->delete();
        //     Alert::toast('Se eliminó el tipo componente', 'warning');
        //     $j['success'] = true;
        //     $j['message'] = 'tipo componente eliminado';
        //     $j['code'] = 200;
        // } catch (\Throwable $th) {
        //     $j['success'] = false;
        //     $j['message'] = $th->getMessage();
        //     $j['code'] = 500;
        // }

        // return response()->json($j);
    }
}
