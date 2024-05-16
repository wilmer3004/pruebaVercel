<?php

namespace App\Http\Controllers;

use App\Models\Coordinacion;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class CoordinacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalcoordinaciones = Coordinacion::count('id');
        $totalcoordinacionesH = Coordinacion::where("state",true)->count('id');
        $totalcoordinacionesD = Coordinacion::where("state",false)->count('id');


        return view('coordinaciones.index', compact('totalcoordinaciones','totalcoordinacionesD','totalcoordinacionesH'));
    }

    public function listar()
    {
        $j = [];

        try {
            $coordinaciones = DB::table('coordinations')->select('id', 'name','color','state')->get();
            $j['success'] = true;
            $j['message'] = 'Consulta exitosa';
            $j['data'] = $coordinaciones;
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
            'color' => 'required|regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/',
            'tipoTec' => 'required'
        ]);
        try {
            $url = route('coordinaciones.index');
            $coordinacion = DB::table('coordinations')->where('name', $request->input('nombre'))->exists();
            $color = DB::table('coordinations')->where('color', $request->input('color'))->exists();

            if ($coordinacion) {
                $j['success'] = false;
                $j['message'] = 'La coordinación ya existe';
                $j['code'] = 505;
            }else if ($color) {
                $j['success'] = false;
                $j['message'] = 'El color ya existe';
                $j['code'] = 505;
            } else {
                $coordinacion = Coordinacion::create([
                    'name' => $request->input('nombre'),
                    'color'=>$request->input('color'),
                    'multi_technique'=>$request->input('tipoTec')
                ]);

                Alert::toast('Se creó la coordinación ' . $request->nombre . ' exitosamente', 'success');
                $j['success'] = true;
                $j['message'] = 'Se creó la coordinación exitosamente';
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
    public function show(Coordinacion $coordinacion)
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
            $coordinaciones = DB::table('coordinations')
                ->select('id', 'name','color', 'multi_technique')
                ->where('id', $id)
                ->get();

            $j['success'] = true;
            $j['data'] = $coordinaciones;
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
            'color' => 'required|regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/',
            'tipoTec' => 'required'
        ]);

        try {
            $url = route('coordinaciones.index');

            $coordinacion = Coordinacion::findOrFail($request->input('id'));
            $color = DB::table('coordinations')
                ->where('color', $request->input('color'))
                ->where('id', '!=', $coordinacion->id)
                ->exists();

            if (Coordinacion::where('name', $request->input('nombre'))
                ->where('id', '!=', $coordinacion->id)
                ->exists()
            ) {
                $j = [
                    'success' => false,
                    'message' => 'La coordinación ya existe',
                    'code' => 400,
                ];
            }else if ($color) {
                $j['success'] = false;
                $j['message'] = 'El color ya existe';
                $j['code'] = 505;
            } else {
                $coordinacion->update([
                    'name' => $request->input('nombre'),
                    'color'=> $request->input('color'),
                    'multi_technique'=>$request->input('tipoTec')
                ]);
                Alert::toast('Se editó la coordinación ' . $request->nombre . ' exitosamente', 'info');
                $j = [
                    'success' => true,
                    'message' => 'Coordinación actualizada',
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
            $existPrograms = DB::table('programs')->where('coordination_id', $id)->exists();
            $existTeachers = DB::table('teachers_coordinations')->where('coordination_id', $id)->exists();

            if($existPrograms || $existTeachers){
                $j['success'] = false;
                $j['message'] = 'No se pudo eliminar, ya que la coordinacion se esta utilizando en otras areas';
                $j['code'] = 200;
            } else {
                $coord = Coordinacion::findOrFail($id);
                $coord->delete();
                Alert::toast('Se eliminó la coordinación', 'warning');
                $j['title'] = 'Coordinacion eliminada';
                $j['success'] = true;
                $j['message'] = 'Se elimino la cordinacion correctamente';
                $j['code'] = 200;
            }
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    ///Cambiar estado de coordinaciones

    public function changeState($id)
    {

        $j = [];

        try {
            $coordinaciones = Coordinacion::findOrFail($id);
            if($coordinaciones->state ===true){
                $coordinaciones->update([
                    'state'=> false
                ]);
                Alert::toast('Se deshabilito la coordinación', 'warning');
                $j['title'] = 'coordinación deshabilitada';
                $j['success'] = true;
                $j['message'] = 'coordinación deshabilitada para su uso';
                $j['code'] = 200;
            } else {
                $coordinaciones->update([
                    'state'=> true
                ]);
                Alert::toast('Se habilito la coordinación', 'warning');
                $j['title'] = 'coordinación habilitada';
                $j['success'] = true;
                $j['message'] = 'coordinación habilitada para su uso';
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
