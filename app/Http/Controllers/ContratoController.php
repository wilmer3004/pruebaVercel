<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Variable para contar el total de contratos
        $totalcontratos = Contrato::count('id');
        $totalcontratosH = Contrato::where("state",true)->count('id');
        $totalcontratosD = Contrato::where("state",false)->count('id');

        // Redireccionar a la vista deseada
        return view('contratos.index', compact('totalcontratos','totalcontratosH','totalcontratosD'));
    }

    public function listar()
    {
        $j = [];

        try {
            $contrato = DB::table('contracts')->select('id', 'name', 'total_hours','state')->get();
            $j['success'] = true;
            $j['message'] = 'Consulta exitosa';
            $j['data'] = $contrato;
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function create(Request $request)
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
            'hora' => 'required|min:1|max:3|regex:/^\d{1,3}$/',
        ]);
        try {
            $url = route('contratos.index');
            $contrato = DB::table('contracts')->where('name', $request->input('nombre'))->exists();

            // dd($contrato);

            if ($contrato) {
                $j['success'] = false;
                $j['message'] = 'El contrato ya existe';
                $j['code'] = 500;
            } else {
                $contrato = Contrato::create([
                    'name' => $request->nombre,
                    'total_hours' => $request->hora
                ]);

                Alert::toast('Se creó el contrato ' . $request->nombre . ' exitosamente', 'success');
                $j['success'] = true;
                $j['message'] = 'Se creó el contrato exitosamente';
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
    public function show(Contrato $contrato)
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
            $contratos = DB::table('contracts')
                ->select('id', 'name', 'total_hours')
                ->where('id', $id)
                ->get();

            $j['success'] = true;
            $j['data'] = $contratos;
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
            'hora' => 'required|min:1|max:3|regex:/^\d{1,3}$/',
            'confirmation'=>'required'
        ]);

        $confirmation = $request->input('confirmation');

        try {
            $url = route('contratos.index');

            $contrato = Contrato::findOrFail($request->input('id'));
            $contractValidation = Contrato::where("id",$request->input('id'))->first();

            // dd($confirmation == "false" && $contractValidation->total_hours != $request->input('hora'));

            if (Contrato::where('name', $request->input('nombre'))
                ->where('id', '!=', $contrato->id)
                ->exists()
            ) {
                $j = [
                    'success' => false,
                    'message' => 'El contrato ya existe',
                    'code' => 400,
                ];
            }else if ($confirmation == "false" && $contractValidation->total_hours != $request->input('hora')){
                $j = [
                    'success' => false,
                    'message' => ' Modificar las horas totales de contrato tendrá un impacto inmediato en los nuevos instructores que se registren a partir de este momento. ¿Estás seguro de querer continuar?',
                    'confirmationHour' => true,
                    'code' => 409,
                ];
            } else {
                $contrato->update([
                    'name' => $request->input('nombre'),
                    'total_hours' => $request->input('hora')
                ]);
                Alert::toast('Se editó el contrato ' . $request->nombre . ' exitosamente', 'info');
                $j = [
                    'success' => true,
                    'message' => 'Contrato actualizado',
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
            $contrato = Contrato::findOrFail($id);
            $contrato->delete();
            Alert::toast('Se eliminó el contrato', 'warning');
            $j['success'] = true;
            $j['message'] = 'Contrato eliminado';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }
    }


    ///Cambiar estado de contrato
    public function changeState($id)
    {

        $j = [];

        try {
            $contratos = Contrato::findOrFail($id);
            if($contratos->state ===true){
                $contratos->update([
                    'state'=> false
                ]);
                Alert::toast('Se deshabilito el tipo de contrato', 'warning');
                $j['title'] = 'Tipo de contrato deshabilitado';
                $j['success'] = true;
                $j['message'] = 'Tipo de contrato deshabilitado para su uso';
                $j['code'] = 200;
            } else {
                $contratos->update([
                    'state'=> true
                ]);
                Alert::toast('Se habilito el tipo de contrato', 'warning');
                $j['title'] = 'Tipo de contrato habilitado';
                $j['success'] = true;
                $j['message'] = 'Tipo de contrato habilitado para su uso';
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
