<?php

namespace App\Http\Controllers;

use App\Models\Jornada;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class JornadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totaljornadas = Jornada::count('id');
        $totaljornadasDeshabilitadas = Jornada::where('state','=','inactivo')->count();
        $totaljornadasHabilitadas = Jornada::where('state','=','activo')->count();

        return view('jornadas.index', compact('totaljornadas','totaljornadasDeshabilitadas','totaljornadasHabilitadas'));
    }

    public function listar()
    {
        $j = [];

        try {
            $jornadas = DB::table('days')->select('id', 'name','color','state')->get();
            $j['success'] = true;
            $j['message'] = 'Consulta exitosa';
            $j['data'] = $jornadas;
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
            'color' => 'required'
        ]);
        try {
            $url = route('jornadas.index');
            $jornada = DB::table('days')->where('name', $request->input('nombre'))->exists();
            $color = DB::table('days')->where('color', $request->input('color'))->exists();

            if ($jornada) {
                $j['success'] = false;
                $j['message'] = 'La jornada ya existe';
                $j['code'] = 505;
            }else if ($color) {
                $j['success'] = false;
                $j['message'] = 'El color ya existe';
                $j['code'] = 505;
            } else {
                $jornada = Jornada::create([
                    'name' => $request->input('nombre'),
                    'color' => $request->input('color'),
                ]);

                Alert::toast('Se creó la jornada ' . $request->nombre . ' exitosamente', 'success');
                $j['success'] = true;
                $j['message'] = 'Se creó la jornada exitosamente';
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
    public function show(Jornada $jornada)
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
            $jornadas = DB::table('days')
                ->select('id', 'name','color')
                ->where('id', $id)
                ->get();

            $j['success'] = true;
            $j['data'] = $jornadas;
            $j['message'] = 'Consulta exitosa';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function update(Request $request)
    {   

        $j = [];

        $request->validate([
            'nombre' => 'required|min:4|max:20|regex:/^[a-zA-ZÀ-ÿ\s]{4,20}$/'
        ]);

        try {
            $url = route('jornadas.index');

            $jornada = Jornada::findOrFail($request->input('id'));
            $color = DB::table('days')
                ->where('id', '!=', $jornada->id)
                ->where('color', $request->input('color'))
                ->exists();
            
            // Contar numero de comas   
            $comas = substr_count($request->input('color'), ',');            

            if ($comas === 3) {                

                $opacidad = strrchr($request->input('color'), '.'); 

                if (!$opacidad) {
                    $j = [
                        'success' => false,
                        'message' => 'La opicidad del color no puede ser 0',
                        'code' => 400,
                    ];
                }
                
                if ($opacidad[1] === '0' && $opacidad[2] === '0') {
                    $j = [
                        'success' => false,
                        'message' => 'La opicidad del color no puede ser 0',
                        'code' => 400,
                    ];
                }            
            }

            // Verificar si el nuevo nombre ya existe en otras jornadas
            if (Jornada::where('name', $request->input('nombre'))
                ->where('id', '!=', $jornada->id)
                ->exists()
            ) {
                $j = [
                    'success' => false,
                    'message' => 'La jornada ya existe',
                    'code' => 400,
                ];
            }else if($color){
                $j = [
                    'success' => false,
                    'message' => 'El color ya existe',
                    'code' => 400,
                ];
            }else {
                $jornada->update([
                    'name' => $request->input('nombre'),
                    'color' => $request->input('color')
                ]);
                Alert::toast('Se editó la jornada ' . $request->nombre . ' exitosamente', 'info');
                $j = [
                    'success' => true,
                    'message' => 'Jornada actualizada',
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
            $jornada = Jornada::findOrFail($id);
            Jornada::where('id', $id)->delete();
            Alert::toast('Se eliminó la jornada', 'warning');
            $j['success'] = true;
            $j['message'] = 'Jornada eliminada';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function changeState($id)
    {
        $j = [];

        try {
            $jornada = Jornada::findOrFail($id);

            if(trim($jornada->state)==='activo'){
                $jornada->update([
                    'state'=> 'inactivo'
                ]);
                Alert::toast('Se deshabilito la jornada', 'warning');
                $j['title'] = 'Jornada deshabilita';
                $j['success'] = true;
                $j['message'] = 'Jornada deshabilita para su uso';
                $j['code'] = 200;
            } else {
                $jornada->update([
                    'state'=> 'activo'
                ]);
                Alert::toast('Se habilito la jornada', 'warning');
                $j['title'] = 'Jornada habilitada';
                $j['success'] = true;
                $j['message'] = 'Jornada habilitada para su uso';
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
