<?php

namespace App\Http\Controllers;

use App\Models\Coordinacion;
use App\Models\Programa;
use App\Models\TipoPrograma;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class ProgramaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalprogramas = Programa::count('id');
        $coordinaciones = Coordinacion::where('state',true)->get();
        $tiposprograma = TipoPrograma::where('state', true)->get();

        return view('programas.index', compact('totalprogramas', 'coordinaciones', 'tiposprograma'));
    }

    public function listar()
    {
        $j = [];

        try {
            $programas = Programa::select(
                'programs.id as id',
                'programs.name as programa',
                'coordinations.name as coordinacion',
                'program_type.name as tipoprograma',
                'programs.duration as duracion',
                'programs.state as state',
                'programs.color as color'
            )
                ->join('coordinations', 'coordinations.id', "=", "programs.coordination_id")
                ->join('program_type', 'program_type.id', "=", "programs.program_type_id")
                ->get();
            $j['success'] = true;
            $j['data'] = $programas;
            $j['message'] = 'Consulta exitosa';
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
            'nombre' => 'required|min:4|max:100|regex:/^[a-zA-ZÀ-ÿ\s]{4,50}$/',
            'descripcion' => 'required|min:4|max:200|regex:/^[a-zA-ZÀ-ÿ\s]{4,200}$/',
            'coordinacion' => 'required',
            'tipo' => 'required',
            'color' => 'required'
        ]);
        try {
            $url = route('programas.index');
            $tipoprograma = TipoPrograma::where('name', '=', 'tecnologo')->first();
            $programa = DB::table('programs')->where('name', $request->input('nombre'))->first();
            $color = DB::table('programs')->where('color', $request->input('color'))->exists();


            if ($programa) {
                $j['success'] = false;
                $j['message'] = 'El programa ya existe';
                $j['code'] = 505;
            } else if ($color) {
                $j['success'] = false;
                $j['message'] = 'El color ya existe';
                $j['code'] = 505;
            } else {

                if ($request->input('tipo') == $tipoprograma->id) {
                    $duracion = 24;
                } else {
                    $duracion = 12;
                }

                $programa = Programa::create([
                    'name' => $request->input('nombre'),
                    'description' => $request->input('descripcion'),
                    'coordination_id' => $request->input('coordinacion'),
                    'program_type_id' => $request->input('tipo'),
                    'duration' => $duracion,
                    'color' => $request->input('color'),
                ]);

                Alert::toast('Se creó el programa ' . $request->input('nombre') . ' exitosamente', 'success');
                $j['success'] = true;
                $j['message'] = 'Se creó el programa exitosamente';
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
    public function show(Programa $programa)
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
            $programa = DB::table('programs')
                ->select(
                    'programs.id as id',
                    'programs.name as name',
                    'programs.description as description',
                    'programs.program_type_id as typeId',
                    'programs.coordination_id as coordinationId',
                    'coordinations.name as coordination',
                    'program_type.name as type',
                    'programs.color as color'
                )
                ->join('coordinations', 'coordinations.id', '=', 'programs.coordination_id')
                ->join('program_type', 'program_type.id', '=', 'programs.program_type_id')
                ->where('programs.id', $id)
                ->get();

            $j['success'] = true;
            $j['data'] = $programa;
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
            'nombre' => 'required|min:4|max:100|regex:/^[a-zA-ZÀ-ÿ\s]{4,100}$/',
            'descripcion' => 'required|min:4|max:200|regex:/^[a-zA-ZÀ-ÿ\s]{4,200}$/',
            'coordinacion' => 'required',
            'tipo' => 'required',
            'color' => 'required',
        ]);

        try {


            // Traer los tipos de programa
            $tiposPrograma = TipoPrograma::where('id', $request->input('tipo'))->exists();
            $tiposCoordinaciones = Coordinacion::where('id', $request->input('coordinacion'))->exists();

            if (!$tiposPrograma || !$tiposCoordinaciones) {
                $j = [
                    'success' => false,
                    'message' => 'Surgio un error al momento de editar',
                    'code' => 409,
                    'reload' => true,
                ];
            } else {

                $url = route('programas.index');
                $programa = Programa::findOrFail($request->input('id'));

                $tipoprograma = TipoPrograma::where('name', '=', 'tecnologo')->first();
                $color = DB::table('programs')
                    ->where('id', '!=', $programa->id)
                    ->where('color', $request->input('color'))
                    ->exists();



                if ($request->tipo == $tipoprograma->id) {
                    $duracion = 24;
                } else {
                    $duracion = 12;
                }

                if (Programa::where('name', $request->input('nombre'))
                    ->where('id', '!=', $programa->id)
                    ->exists()
                ) {
                    $j = [
                        'success' => false,
                        'message' => 'El programa ya existe',
                        'code' => 400,
                    ];
                } else if ($color) {
                    $j['success'] = false;
                    $j['message'] = 'El color ya existe';
                    $j['code'] = 505;
                } else {
                    $programa->update([
                        'name' => $request->input('nombre'),
                        'description' => $request->input('descripcion'),
                        'coordination_id' => $request->input('coordinacion'),
                        'program_type_id' => $request->input('tipo'),
                        'duration' => $duracion,
                        'color' => $request->input('color'),
                    ]);
                    Alert::toast('Se editó el programa ' . $request->nombre . ' exitosamente', 'info');
                    $j = [
                        'success' => true,
                        'message' => 'Programa actualizado',
                        'url' => $url,
                        'code' => 200,
                    ];
                }
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
            $programas = Programa::findOrFail($id);

            if (trim($programas->state) === 'activo') {
                $programas->update([
                    'state' => 'inactivo'
                ]);
                Alert::toast('Se deshabilito el programa', 'warning');
                $j['title'] = 'Se deshabilito el programa';
                $j['success'] = true;
                $j['message'] = 'Programa deshabilitado para su uso';
                $j['code'] = 200;
            } else {
                $programas->update([
                    'state' => 'activo'
                ]);
                Alert::toast('Se habilito el programa', 'warning');
                $j['title'] = 'Se habilito el programa';
                $j['success'] = true;
                $j['message'] = 'Programa habilitado para su uso';
                $j['code'] = 200;
            }
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function delete($id)
    {
        $j = [];

        try {
            $existComponents = DB::table('components_programs')->where('program_id', $id)->exists();
            $existStudySheets = DB::table('study_sheets')->where('program_id', $id)->exists();

            if ($existComponents || $existStudySheets) {
                $j['success'] = false;
                $j['message'] = 'No se pudo eliminar, ya que el programa se esta utilizando en otras areas';
                $j['code'] = 200;
            } else {
                $program = Programa::findOrFail($id);
                $program->delete();
                Alert::toast('Se eliminó el programa', 'warning');
                $j['title'] = 'Programa eliminado';
                $j['success'] = true;
                $j['message'] = 'Se elimino el programa correctamente';
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
