<?php

namespace App\Http\Controllers;

use App\Models\Componente;
use App\Models\ComponenteProgramas;
use App\Models\Programa;
use App\Models\TipoComponente;
use App\Models\Trimestre;
use App\Models\Evento;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComponenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalcomponentes = Componente::count('id');
        $tipocomponente = TipoComponente::where('state',true)->get();
        $programa = DB::table('programs as p')
            ->join('program_type as pt', 'p.program_type_id', '=', 'pt.id')
            ->rightJoin('coordinations as co','co.id','=','p.coordination_id')
            ->where('co.state',true)
            ->where('pt.state', true)
            ->where('p.state', 'activo')
            ->select('p.id', 'p.name', 'p.description', 'p.coordination_id', 'p.duration')
            ->get();
        $trimestre = Trimestre::all();
        $componentes = Componente::all();

        $programaas = Programa::select('id', 'name')->get();

        return view('componentes.index', compact('programaas', 'componentes', 'totalcomponentes', 'tipocomponente', 'programa', 'trimestre'));
    }


    // Metodo para listar elementos en la tabla
    public function listar()
    {
        $j = [];

        try {
            $componente = Componente::select(
                'components.id as id',
                'components.name as componente',
                'components_type.name as tipocomponente',
                'quarters.name as trimestre',
                'components.total_hours as totalhoras',
                'components.description as descripcion',
                'components.state as state'
            )
                ->join('components_type', 'components_type.id', '=', 'components.component_type_id')
                ->join('quarters', 'quarters.id', '=', 'components.quarter_id')
                ->get();
            $j['success'] = true;
            $j['data'] = $componente;
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
            'nombre' => 'required|min:4|max:100|regex:/^[a-zA-ZÀ-ÿ\s]{4,100}$/',
            'descripcion' => 'required|min:4|max:200|regex:/^[a-zA-ZÀ-ÿ\s]{4,200}$/',
            'tipo' => 'required',
            'programs' => 'required',
            'trimestre' => 'required',
            'horas' => 'required|min:2|max:7|regex:/^\d{2,7}$/|'
        ]);
        try {
            $url = route('componentes.index');
            $componente = DB::table('components')->where('name', $request->input('nombre'))->exists();

            if ($componente) {
                $j['success'] = false;
                $j['message'] = 'El componente ya existe';
                $j['code'] = 505;
            } else {

                $componente = Componente::create([
                    'name' => $request->nombre,
                    'description' => $request->descripcion,
                    'component_type_id' => $request->tipo,
                    'quarter_id' => $request->trimestre,
                    'total_hours' => $request->horas
                ]);

                // Sacar el ultimo componente creado
                $idComponent = DB::table('components')->select('components.id')->orderBy('components.id', 'desc')->first()->id;


                // Insertar en la tabla component_program insertar lo diferentes ids
                foreach ($request->programs as $program) {

                    $results = DB::table('components_programs as cp')
                        ->rightJoin('programs as p', 'cp.program_id', '=', 'p.id')
                        ->join('coordinations as cor', 'cor.id', '=', 'p.coordination_id')
                        ->join('components as c', 'cp.component_id', '=', 'c.id')
                        ->join('components_type as ct', 'ct.id', '=', 'c.component_type_id')
                        ->select('ct.id', 'ct.name', 'c.name', 'p.name', 'p.coordination_id', 'cor.name')
                        ->where('ct.id', 1)
                        ->where('cp.program_id', $program)
                        ->where('cor.multi_technique', '!=', true)
                        ->get();

                    $nameProgram = DB::table('programs')->where('id', $program)->first();

                    $firstObject = $results->first();
                    if ($firstObject != null && $request->tipo == 1) {
                        $j['success'] = false;
                        $j['message'] = "El programa $nameProgram->name no esta relacionado a una coordinación multi tecnica.";
                        $j['code'] = 500;

                        ComponenteProgramas::where('component_id', $idComponent)->delete();
                        Componente::where('id', $idComponent)->delete();

                        return response()->json($j);
                    } else {
                        $component_program = ComponenteProgramas::create([
                            'component_id' => $idComponent,
                            'program_id' => $program
                        ]);
                        Alert::toast('Se creó el componente ' . $request->nombre . ' exitosamente', 'success');
                        $j['success'] = true;
                        $j['message'] = 'Se creó el componente exitosamente';
                        $j['code'] = 200;
                        $j['url'] = $url;
                    }
                }
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
    public function show(Componente $componente)
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


            $componentes = DB::table('components')
                ->select(
                    'components.id',
                    'components.name as name',
                    'components.description as description',
                    'components.component_type_id as typeId',
                    'components.quarter_id as quarterId',
                    'components.total_hours as hours',
                    'components_type.name as type',
                    'quarters.name as quarter'
                )
                ->join('components_type', 'components_type.id', '=', 'components.component_type_id')
                ->join('quarters', 'quarters.id', '=', 'components.quarter_id')
                ->where('components.id', $id)
                ->get();

            $component_programs = DB::table('components_programs')->select('program_id')
                ->where('component_id', '=', $id)->get();


            $j['success'] = true;
            $j['data'] = $componentes;
            $j['data2'] = $component_programs;
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
            'tipo' => 'required',
            'programs' => 'required',
            'trimestre' => 'required',
            'totalh' => 'required|min:2|max:7|regex:/^\d{2,7}$/|'
        ]);

        try {
            $url = route('componentes.index');

            $componente = Componente::findOrFail($request->input('id'));

            if (Componente::where('name', $request->input('nombre'))
                ->where('id', '!=', $componente->id)
                ->exists()
            ) {
                $j = [
                    'success' => false,
                    'message' => 'El componente ya existe',
                    'code' => 400,
                ];
            } else {



                ComponenteProgramas::where('component_id', $componente->id)->delete();

                foreach ($request->programs as $program) {

                    $results = DB::table('components_programs as cp')
                        ->rightJoin('programs as p', 'cp.program_id', '=', 'p.id')
                        ->join('coordinations as cor', 'cor.id', '=', 'p.coordination_id')
                        ->join('components as c', 'cp.component_id', '=', 'c.id')
                        ->join('components_type as ct', 'ct.id', '=', 'c.component_type_id')
                        ->select('ct.id', 'ct.name', 'c.name', 'p.name', 'p.coordination_id', 'cor.name')
                        ->where('ct.id', 1)
                        ->where('cp.program_id', $program)
                        ->where('cor.multi_technique', '!=', true)
                        ->where('c.id', '!=', $componente->id)
                        ->get();

                    $nameProgram = DB::table('programs')->where('id', $program)->first();

                    $firstObject = $results->first();

                    if ($nameProgram->state === 'inactivo') {
                        $j['success'] = false;
                        $j['message'] = "El programa $nameProgram->name esta deshabilitado";
                        $j['code'] = 500;

                        return response()->json($j);
                    }

                    if ($firstObject != null && $request->input('tipo') == 1) {
                        $j['success'] = false;
                        $j['message'] = "El programa $nameProgram->name no esta relacionado a una coordinación multi tecnica";
                        $j['code'] = 500;

                        return response()->json($j);
                    } else {
                        $component_program = ComponenteProgramas::create([
                            'component_id' => $componente->id,
                            'program_id' => $program
                        ]);
                    }
                }


                $componente->update([
                    'name' => $request->input('nombre'),
                    'description' => $request->input('descripcion'),
                    'component_type_id' => $request->input('tipo'),
                    'quarter_id' => $request->input('trimestre'),
                    'total_hours' => $request->input('totalh')
                ]);
                Alert::toast('Se editó el componente ' . $request->nombre . ' exitosamente', 'info');

                $j = [
                    'success' => true,
                    'message' => 'Componente actualizado',
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
            $Componente = Componente::findOrFail($id);

            // if($eventos!=null){
            //     ComponenteProgramas::where('component_id', $id)->delete();
            //     Componente::where('id', $id)->delete();
            // } else {
            //     ComponenteProgramas::where('component_id', $id)->delete();
            //     Componente::where('id', $id)->delete();
            // }

            if (trim($Componente->state) === 'activo') {
                $Componente->update([
                    'state' => 'inactivo'
                ]);
                Alert::toast('Se deshabilito el compoonente', 'warning');
                $j['title'] = 'Se deshabilito el componente';
                $j['success'] = true;
                $j['message'] = 'Componente deshabilitado para su uso';
                $j['code'] = 200;
            } else {
                $Componente->update([
                    'state' => 'activo'
                ]);
                Alert::toast('Se habilito el componente', 'warning');
                $j['title'] = 'Se habilito el componente';
                $j['success'] = true;
                $j['message'] = 'Componente habilitado para su uso';
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
            $existEvents = DB::table('events')->where('component_id', $id)->exists();

            if ($existEvents) {
                $j['success'] = false;
                $j['message'] = 'No se pudo eliminar ya que el componente se esta dictando en algunos eventos';
                $j['code'] = 200;
            } else {
                ComponenteProgramas::where('component_id', $id)->delete();
                $component = Componente::findOrFail($id);
                $component->delete();


                Alert::toast('Se eliminó el componente', 'warning');
                $j['title'] = 'Componente eliminado';
                $j['success'] = true;
                $j['message'] = 'Se elimino el componente correctamente';
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
