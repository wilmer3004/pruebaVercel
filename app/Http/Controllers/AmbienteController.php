<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use App\Models\AmbienteComponente;
use App\Models\Sede;
use App\Models\AmbienteCoordinaciones;
use App\Models\Coordinacion;
use App\Models\Evento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AmbienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalambientes = Ambiente::count('id'); // Total de Ambiente para la Card
        $sedes = Sede::where('state','activo')->get(); // Datatable headquarters
        $tipoComponentes = DB::table('components_type') ->get(); // Componentes
        $coordinations=Coordinacion::where('state',true)->get();

        return view('ambientes.index', compact('coordinations','totalambientes', 'sedes', 'tipoComponentes'));
    }

    public function listar()
    {
        $j = [];

        try {
            $ambientes = Ambiente::select(
                'environments.id as id',
                'headquarters.name as sede',
                'environments.name as ambiente',
                'environments.floor as piso',
                'environments.capacity as capacidad',
                'environments.state as state',
                DB::raw('string_agg(components_type.name, \', \') as componente')
            )
            ->join('headquarters', 'headquarters.id', '=', 'environments.headquarter_id')
            ->leftJoin('environment_components', 'environment_components.environment_id', '=', 'environments.id')
            ->leftJoin('components_type', 'components_type.id', '=', 'environment_components.component_type_id')
            ->orderBy('environments.id')
            ->groupBy('environments.id', 'headquarters.name', 'environments.name', 'environments.floor', 'environments.capacity') // Incluye todas las columnas no agregadas en GROUP BY
            ->get();

            $j['success'] = true;
            $j['data'] = $ambientes;
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
            'nombre' => 'required|min:4|max:100|regex:/^[a-zA-ZÀ-ÿ\s0-9]{4,100}$/',
            'sede' => 'required',
            'piso' => 'required|min:1|max:3|regex:/^\d{1,3}$/',
            'capacidad' => 'required|min:1|max:3|regex:/^\d{1,3}$/',
        ]);
        try {

            $url = route('ambientes.index');
            $ambiente = DB::table('environments')->where('name', $request->input('nombre'))->exists();
            $totalAmbi = DB::table('environments')
            ->where('headquarter_id', '=', $request->input('sede'))
            ->count();
            $ambiMaxSede = DB::table('headquarters')
            ->where('id', '=', $request->input('sede'))
            ->value('environment_capacity');

            if ($ambiente) {
                $j['success'] = false;
                $j['message'] = 'El ambiente ya existe';
                $j['code'] = 505;
            } else if($totalAmbi>=$ambiMaxSede){
                $j['success'] = false;
                $j['message'] = 'La sede ya ocupo el maximo de sus ambientes';
                $j['code'] = 505;
            } else {
                $ambiente = Ambiente::create([
                    'name' => $request->input('nombre'),
                    'headquarter_id' => $request->input('sede'),
                    'floor' => $request->input('piso'),
                    'capacity' => $request->input('capacidad'),
                    'components_type_id' => $request -> input('tipoComponenteAmbiente')
                ]);

                $tipoComponentesID = json_decode($request->input('tipoComponetesID'));
                $componentesArray = array_map('intval', $tipoComponentesID);

                foreach ($componentesArray as $componente) {
                    DB::table('environment_components')->insert([
                        'environment_id' => $ambiente->id,
                        'component_type_id' => $componente,
                    ]);
                }

                $idAmbiente = DB::table('environments')->select('id')->orderBy('id','desc')->first()->id;

                // Insertar en la tabla environment_coordinations insertar lo diferentes ids
                foreach($request->coordinations as $coordination){

                    $environment_coordination= AmbienteCoordinaciones::create([
                        'environment_id' => $idAmbiente,
                        'coordination_id' => $coordination
                    ]);

                    Alert::toast('Se creó el ambiente ' . `$request->nombre` . ' exitosamente', 'success');
                        $j['success'] = true;
                        $j['message'] = 'Se creó el ambiente exitosamente';
                        $j['code'] = 200;
                        $j['url'] = $url;
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
    public function show(Ambiente $ambiente)
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
            $ambientes = DB::table('environments')
                ->select(
                    'environments.id as id',
                    'environments.name as name',
                    'environments.headquarter_id as headquarterId',
                    'environments.floor',
                    'environments.capacity as capacity',
                    'headquarters.name as headquarter',
                    'components_type.id as componentId',
                    'components_type.name as componente'
                )
                ->join('headquarters', 'headquarters.id', '=', 'environments.headquarter_id')
                ->leftJoin('environment_components', 'environments.id', '=', 'environment_components.environment_id')
                ->leftJoin('components_type', 'components_type.id', '=', 'environment_components.component_type_id')
                ->where('environments.id', $id)
                ->get();

            $environment_coordination= DB::table('environment_coordinations')->select('coordination_id')
            ->where('environment_id','=',$id)->get();


            $j['success'] = true;
            $j['data'] = $ambientes;
            $j['data2'] = $environment_coordination;
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
            'nombre' => 'required|min:3|max:100|regex:/^[a-zA-ZÀ-ÿ\s0-9]{4,100}$/',
            'sede' => 'required',
            'piso' => 'required|numeric|min:0|max:30',
            'capacidad' => 'required|numeric|min:0|max:40' ,
            'confirmation'=>'required',
            'confirmation2'=>'required',
        ]);

        $confirmation=$request->input('confirmation');
        $confirmation2=$request->input('confirmation2');

        try {
            $url = route('ambientes.index'); // Vover a ambiente.inde

            $ambientes = Ambiente::findOrFail($request->input('id')); // Datos relacionados al id del ambiente

            $idTipoComponente = $request -> input('tipoComponenteAmbienteE'); // Id tipo de componente a dictar en ambiente
            $componentesArray = array_map('intval', $idTipoComponente); // Transforma los id a int
            $tipoComponentesActual = DB::table('environment_components')
                -> select('component_type_id')
                -> where('environment_id', $ambientes->id)
                -> orderBy('component_type_id', 'asc')
                -> get();


            $events = Evento::where('environment_id',$request->input('id')) ->exists();
            $nameEnvironment = strtolower($ambientes->name);

            $customDateTime = now()->format('Y-m-d');
            $yearQuarter = DB::table('year_quarters')
            ->where('start_date', '<=', $customDateTime)
            ->where('finish_date', '>=', $customDateTime)
            ->first();

            if($yearQuarter){
                $fechaFinal = Carbon::createFromFormat('Y-m-d H:i:s', $yearQuarter->finish_date . ' 23:59:59')->endOfDay();
                $customDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $customDateTime . ' 00:00:00')->startOfDay();
                $totalStudentsMax = DB::table('events as e')
                ->join('study_sheets as s','s.id','=','e.study_sheet_id')
                ->where('e.environment_id',$request->input('id'))
                ->where(function ($query) use ($customDateTime, $fechaFinal) {
                    $query->whereBetween('start', [$customDateTime, $fechaFinal])
                        ->orWhereBetween('end', [$customDateTime, $fechaFinal]);
                })
                ->select(
                    DB::raw('MAX(s.num_trainnies) as num_trainnies')
                )
                ->first();



            }else{
                $totalStudentsMax = DB::table('events as e')
                ->join('study_sheets as s','s.id','=','e.study_sheet_id')
                ->where('e.environment_id',$request->input('id'))
                ->select(
                    DB::raw('MAX(s.num_trainnies) as num_trainnies')
                )
                ->first();

            }
            //Traer la cantidad maxima de aprendices donde el ambiente este programado


                $capacityEvent = $request->input('capacidad');

                if($totalStudentsMax && $totalStudentsMax->num_trainnies==null){
                    $totalStudentsMax->num_trainnies = 0;
                }

                if($totalStudentsMax && $totalStudentsMax->num_trainnies<=$capacityEvent){
                    $confirmation2 = "true";
                }

            if (Ambiente::where('name', $request->input('nombre')) // Condicional examinar existencia nombre de ambiente
                ->where('id', '!=', $ambientes->id)
                ->exists()
            ) {
                $j = [
                    'success' => false,
                    'message' => 'El ambiente ya existe',
                    'code' => 400,
                ];

            }else if($totalStudentsMax && $totalStudentsMax->num_trainnies && $totalStudentsMax->num_trainnies>$capacityEvent){
                $messageE = 'El ambiente ya esta programado con una ficha que su cantidad de aprendices es de ' . $totalStudentsMax->num_trainnies . ", si desea modificarlo tendra que aumentar la capacidad en vez de disminuirla";
                $j = [
                    'success' => false,
                    'message' => $messageE,
                    'code' => 409,
                ];

            } else if($events && $confirmation=="false" || $confirmation2=="false" || ($totalStudentsMax && $totalStudentsMax->num_trainnies>$capacityEvent)){
                if($nameEnvironment != $request->input('nombre')){
                    $j = [
                        'success' => false,
                        "confirmation"=>true,
                        "title"=>"¡Confirmacion cambio de nombre del ambiente!",
                        'message' => 'El ambiente ya esta programado, si desea modificar el nombre se alterara en el hisorial de eventos',
                        'code' => 409,
                    ];
                }else if($totalStudentsMax && $totalStudentsMax->num_trainnies<$capacityEvent){
                    $j = [
                        'success' => false,
                        "confirmation2"=>true,
                        "title"=>"¡Confirmacion cambio de capacidad del ambiente!",
                        'message' => 'El ambiente ya esta programado, si desea modificar la capacidad del ambiente apartir de la fecha actual se modificara',
                        'code' => 409,
                    ];
                }else{
                    $j = [
                        'success' => false,
                        'message' => 'El ambiente ya esta programado por lo tanto no se puede modificar los datos que no sean nombre',
                        'code' => 409,
                    ];
                }
            } else {
                // Update ambiente
                $ambientes->update([
                    'name' => $request->input('nombre'),
                    'headquarter_id' => $request->input('sede'),
                    'floor' => $request->input('piso'),
                    'capacity' => $request->input('capacidad'),
                ]);
                // Update relaciones
                    // Elimina relacion
                foreach ($tipoComponentesActual as $tipoComponenteActual){
                    if (!in_array($tipoComponenteActual -> component_type_id, $componentesArray)){
                        // Elimnar relacion a coordinacion
                        DB::table('environment_components')
                            -> where('environment_id', $ambientes->id)
                            -> where('component_type_id', $tipoComponenteActual -> component_type_id)
                            -> delete();
                    }
                }
                    // Agregar relacion
                foreach ($componentesArray as $componente) {
                    if(!in_array($componente, $tipoComponentesActual->pluck('component_type_id')->toArray())){
                        DB::table('environment_components')->insert([
                            'environment_id' => $ambientes->id,
                            'component_type_id' => $componente
                        ]);
                    }
                }

                // Eliminar las relaciones en la tabla debil
                AmbienteCoordinaciones::where('environment_id',$ambientes->id)->delete();

                foreach ($request->coordinations as $coordination){
                    $environment_coordination= AmbienteCoordinaciones::create([
                        'environment_id' => $ambientes->id,
                        'coordination_id' => $coordination
                    ]);
                }


                Alert::toast('Se editó el ambiente ' . `$request->nombre` . ' exitosamente', 'info');
                $j = [
                    'success' => true,
                    'message' => 'Ambiente actualizado',
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

     // Deshabilitar ambiente
    public function stateEnviroment($id) {

        $ambiente = Ambiente::findOrFail($id); // Traer datos del array

        // Examinar estados ambiente
        if(trim($ambiente->state)==='activo'){
            $ambiente->update([
                'state'=> 'inactivo'
            ]);
            Alert::toast('Se deshabilito el ambiente', 'warning');
            $j['title'] = 'Deshabilitado';
            $j['success'] = true;
            $j['message'] = 'Ambiente a sido deshabilitado para su uso';
            $j['code'] = 200;
        } else {
            $ambiente->update([
                'state'=> 'activo'
            ]);
            Alert::toast('Se habilito el ambiente', 'warning');
            $j['title'] = 'Habilitado';
            $j['success'] = true;
            $j['message'] = 'Ambiente a sido habilitado para su uso';
            $j['code'] = 200;
        }
    }


    // Eliminar ambiente
    public function destroy($id)
    {
        $j = [];

        try {
            $ambiente = Ambiente::findOrFail($id); // Traer datos del array

            $existAmbienteTrimestre = DB::table('events') // Existencia del ambiente en el trimestre
                ->where('environment_id', $ambiente->id)
                ->exists();

            if ($existAmbienteTrimestre){
                // MENSAJE DE DELETE INTERRUMPIDO
                Log::info('Accion Interrumpida - El ambiente a esta siendo usado en el historial');
                $j['success'] = false;
                $j['message'] = 'Accion Interrumpida - El ambiente no puede ser eliminado, actualmente esta en la programación';
                $j['code'] = 500;
            }

            else {
                AmbienteComponente::where('environment_id', $ambiente->id)->delete();
                AmbienteCoordinaciones::where('environment_id', $ambiente->id)->delete();
                Ambiente::where('id', $ambiente->id)->delete();
                $j['success'] = true;
                $j['title'] = 'Eliminación Exitosa';
                $j['message'] = 'La eliminación del ambiente fue exitosa';
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
