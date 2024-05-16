<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Trimestre;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HorarioInformationController extends Controller
{
        //////////////////////////////////////////////////////////////////////////////////
    //DATATABLE EVENTOS
    public function datatable()
    {
        $totalEventos = 0;
        $eventos = DB::table('events as ev')
        ->join('study_sheets as st', 'st.id', '=', 'ev.study_sheet_id')
        ->leftJoin('teachers as t', 't.id', '=', 'ev.teacher_id')
        ->leftJoin('users as u', 'u.id', '=', 't.user_id')
        ->leftJoin('people as p', 'u.id', '=', 'p.user_id')
        ->join('environments as env', 'env.id', '=', 'ev.environment_id')
        ->join('quarters as q', 'q.id', '=', 'st.quarter_id')
        ->leftJoin('programs as pro', 'pro.id', '=', 'st.program_id')
        ->join('components as comp','comp.id','=','ev.component_id')
        ->rightJoin('coordinations as co','co.id','=','pro.coordination_id')
        ->rightJoin('days as day','day.id','=','st.day_id')
        ->select(
            DB::raw("st.number as nFicha"),
            DB::raw("st.id as idFicha"),
            "st.num as num",
            DB::raw("p.name as instructorName"),
            DB::raw("t.id as idInstructor"),
            DB::raw("p.lastname as instructorLastName"),
            DB::raw("env.name as ambiente"),
            DB::raw("env.id as idAmbiente"),
            DB::raw("q.name as trimestre"),
            DB::raw("q.id as idTrimestre"),
            DB::raw("pro.name as programa"),
            DB::raw("pro.id as idPrograma"),
            DB::raw("comp.id as idComponent"),
            DB::raw("comp.name as nameComponent"),
            DB::raw("co.name as nameCordination"),
            DB::raw("co.id as idCordination"),
            DB::raw("st.end_lective as endLective"),
            DB::raw("MAX(day.name) as jornada"),
            DB::raw("MIN(ev.start) as inicio"),
            DB::raw("MAX(ev.end) as final")

        )
        ->where('ev.start', '>=', '2000-01-01')
        ->where('ev.end', '>=', '2000-01-01')
        ->groupBy(
            'st.number',
            'st.id',
            'p.name',
            'p.lastname',
            't.id',
            'env.name',
            'env.id',
            'q.name',
            'q.id',
            'pro.name',
            'pro.id',
            'comp.id',
            'comp.name',
            'co.name',
            'co.id',
            'st.num',
        )
        ->orderBy('co.name', 'asc')
        ->get();

        $newArray = []; // Initialize the new array

        $eventos->each(function ($evento) use (&$newArray, &$totalEventos) {
            // Check if the event already exists in the new array
            $exists = false;
            foreach ($newArray as $existingEvento) {
                if ($existingEvento->trimestre === $evento->trimestre &&
                    $existingEvento->nficha === $evento->nficha &&
                    $existingEvento->num === $evento->num) {
                    $exists = true;
                    break;
                }
            }

            // If the event does not exist in the new array, add it
            if (!$exists) {
                $newArray[] = $evento;
                $totalEventos = $totalEventos +  1;
            }
        });

        $trimestres = DB::table('year_quarters')
        ->get();
        $colorsCoprdination= DB::table('coordinations')
        ->get();
        $holidays= DB::table('holidays as d')
        ->get();
        $component_type = DB::table('components_type')
        ->get();



        return view('horarioinformation.index', compact('holidays','colorsCoprdination','totalEventos','trimestres','component_type'))->render();
    }


    ///////////////////////////////////////////////////////////////////////////////////////////
    // FUNCION PARA LISTAR UN EVENTO
    public function listar()
    {
        $j = [];
        try {
            $eventos = DB::table('events as ev')
            ->join('study_sheets as st', 'st.id', '=', 'ev.study_sheet_id')
            ->leftJoin('teachers as t', 't.id', '=', 'ev.teacher_id')
            ->leftJoin('users as u', 'u.id', '=', 't.user_id')
            ->leftJoin('people as p', 'u.id', '=', 'p.user_id')
            ->join('environments as env', 'env.id', '=', 'ev.environment_id')
            ->join('quarters as q', 'q.id', '=', 'st.quarter_id')
            ->leftJoin('programs as pro', 'pro.id', '=', 'st.program_id')
            ->join('components as comp','comp.id','=','ev.component_id')
            ->rightJoin('coordinations as co','co.id','=','pro.coordination_id')
            ->rightJoin('days as day','day.id','=','st.day_id')
            ->select(
                DB::raw("st.number as nFicha"),
                DB::raw("st.id as idFicha"),
                "st.num as num",
                DB::raw("p.name as instructorName"),
                DB::raw("t.id as idInstructor"),
                DB::raw("p.lastname as instructorLastName"),
                DB::raw("env.name as ambiente"),
                DB::raw("env.id as idAmbiente"),
                DB::raw("q.name as trimestre"),
                DB::raw("q.id as idTrimestre"),
                DB::raw("pro.name as programa"),
                DB::raw("pro.id as idPrograma"),
                DB::raw("comp.id as idComponent"),
                DB::raw("comp.name as nameComponent"),
                DB::raw("co.name as nameCordination"),
                DB::raw("co.id as idCordination"),
                DB::raw("st.end_lective as endLective"),
                DB::raw("MAX(day.name) as jornada"),
                DB::raw("MIN(ev.start) as inicio"),
                DB::raw("MAX(ev.end) as final")

            )
            ->where('ev.start', '>=', '2000-01-01')
            ->where('ev.end', '>=', '2000-01-01')
            ->groupBy(
                'st.number',
                'st.id',
                'p.name',
                'p.lastname',
                't.id',
                'env.name',
                'env.id',
                'q.name',
                'q.id',
                'pro.name',
                'pro.id',
                'comp.id',
                'comp.name',
                'co.name',
                'co.id',
                'st.num',
            )
            ->orderBy('co.name', 'asc')
            ->get();

            $newArray = []; // Initialize the new array

            $eventos->each(function ($evento) use (&$newArray) {
                // Check if the event already exists in the new array
                $exists = false;
                foreach ($newArray as $existingEvento) {
                    if ($existingEvento->trimestre === $evento->trimestre &&
                        $existingEvento->nficha === $evento->nficha &&
                        $existingEvento->num === $evento->num) {
                        $exists = true;
                        break;
                    }
                }

                // If the event does not exist in the new array, add it
                if (!$exists) {
                    $newArray[] = $evento;
                }
            });



            $j['success'] = true;
            $j['data'] = $newArray;
            $j['message'] = 'Consulta exitosa';
            $j['code'] =  200;
        } catch (\Throwable $th) {
            // Log the exception to get more details
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] =  500;
        }

        return response()->json($j);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////
    // FUNCION PARA EDITAR UN EVENTO

    // public function update(Request $request){
    //     $request->validate([
    //         'idTeacheUpdate',
    //         'idAmbiente' => 'required',
    //         'idInstructor' => 'required',
    //         'idFicha' => 'required',
    //         'idComponent' => 'required',
    //         'bloqueInicio'=>'required|date_format:H:i:s',
    //         'bloqueFin'=>'required|date_format:H:i:s'
    //     ]);

    //     $j =[];
    //     try{
    //         // Get the provided times
    //         $bloqueInicio = $request->input('bloqueInicio');
    //         $bloqueFin = $request->input('bloqueFin');

    //         $events = Evento::where([
    //             ['environment_id', $request->input('idAmbiente')],
    //             ['teacher_id', $request->input('idInstructor')],
    //             ['study_sheet_id', $request->input('idFicha')],
    //             ['component_id', $request->input('idComponent')],
    //         ])->get();

    //         foreach ($events as $event) {
    //             // Combine the date part of start and end with the provided times
    //             $startTime = Carbon::parse($event->start)->format('Y-m-d ') . $bloqueInicio;
    //             $endTime = Carbon::parse($event->end)->format('Y-m-d ') . $bloqueFin;

    //             // Convert the combined strings to Carbon instances
    //             $startComparison = Carbon::parse($startTime);
    //             $endComparison = Carbon::parse($endTime);

    //             // Check if the provided times are within the range
    //             // if ($startComparison >= Carbon::parse($event->start) && $endComparison <= Carbon::parse($event->end)) {
    //             //     // If within range, delete the event
    //             //     $event->delete();
    //             // }
    //         }

    //     }catch (\Throwable $th) {
    //         $j = [
    //             'success' => false,
    //             'message' => $th->getMessage(),
    //             'code' => 500,
    //         ];
    //     }
    // }
    ///////////////////////////////////////////////////////////////////////////////////////////
    // FUNCION PARA LISTAR LOS EVENTOS POR FICHA
    public function showEventoFicha(Request $request){
        $request->validate([
            'idFicha' => 'required',
        ]);
        $j = [];
        try{
            $eventos = DB::table('events as ev')
            ->join('study_sheets as st', 'st.id', '=', 'ev.study_sheet_id')
            ->leftJoin('teachers as t', 't.id', '=', 'ev.teacher_id')
            ->leftJoin('users as u', 'u.id', '=', 't.user_id')
            ->leftJoin('people as p', 'u.id', '=', 'p.user_id')
            ->join('environments as env', 'env.id', '=', 'ev.environment_id')
            ->join('quarters as q', 'q.id', '=', 'st.quarter_id')
            ->leftJoin('programs as pro', 'pro.id', '=', 'st.program_id')
            ->join('components as comp','comp.id','=','ev.component_id')
            ->rightJoin('coordinations as co','co.id','=','pro.coordination_id')
            ->join('components_type as comptype','comp.component_type_id','=','comptype.id')
            ->rightJoin('days as day','day.id','=','st.day_id')
            ->select(
                DB::raw("st.number as nFicha"),
                DB::raw("st.id as idFicha"),
                DB::raw("p.name as instructorName"),
                DB::raw("t.id as idInstructor"),
                DB::raw("p.lastname as instructorLastName"),
                DB::raw("env.name as ambiente"),
                DB::raw("env.id as idAmbiente"),
                DB::raw("q.name as trimestre"),
                DB::raw("q.id as idTrimestre"),
                DB::raw("pro.name as programa"),
                DB::raw("pro.id as idPrograma"),
                DB::raw("comp.id as idComponent"),
                DB::raw("comp.name as nameComponent"),
                DB::raw("co.name as nameCordination"),
                DB::raw("co.id as idCordination"),
                DB::raw("st.end_lective as endLective"),
                DB::raw("MAX(day.name) as jornada"),
                DB::raw("MAX(comptype.name) as component_type"),
                DB::raw("MIN(ev.start) as inicio"),
                DB::raw("MAX(ev.end) as final")
            )
            ->where('ev.start', '>=', '2000-01-01')
            ->where('ev.end', '>=', '2000-01-01')
            ->where('st.id','=',$request->input('idFicha'))
            ->groupBy(
                'st.number',
                'st.id',
                'p.name',
                'p.lastname',
                't.id',
                'env.name',
                'env.id',
                'q.name',
                'q.id',
                'pro.name',
                'pro.id',
                'comp.id',
                'comp.name',
                'co.name',
                'co.id',
            )
            ->orderBy('co.name', 'asc')
            ->get();


            $j['success'] = true;
            $j['data'] = $eventos;
            $j['message'] = 'Consulta exitosa';
            $j['code'] =  200;
        } catch (\Throwable $th) {
            // Log the exception to get more details
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] =  500;
        }

        return response()->json($j);
    }


    ///////////////////////////////////////////////////////////////////////////////////////////
    // FUNCION PARA ELIMINAR UN EVENTO
    public function destroy(Request $request){
        $request->validate([
            'idAmbiente' => 'required',
            'idInstructor' => '',
            'idFicha' => 'required',
            'idComponent' => 'required',
            'bloqueInicio'=>'required|date_format:H:i:s',
            'bloqueFin'=>'required|date_format:H:i:s'
        ]);
        $j = [];
        try{
            // Get the provided times
            $bloqueInicio = $request->input('bloqueInicio');
            $bloqueFin = $request->input('bloqueFin');

            // Fetch the events based on other parameters except start and end
            $events = Evento::where([
                ['environment_id', $request->input('idAmbiente')],
                ['teacher_id', $request->input('idInstructor')],
                ['study_sheet_id', $request->input('idFicha')],
                ['component_id', $request->input('idComponent')],
            ])->get();

            foreach ($events as $event) {
                // Combine the date part of start and end with the provided times
                $startTime = Carbon::parse($event->start)->format('Y-m-d ') . $bloqueInicio;
                $endTime = Carbon::parse($event->end)->format('Y-m-d ') . $bloqueFin;

                // Convert the combined strings to Carbon instances
                $startComparison = Carbon::parse($startTime);
                $endComparison = Carbon::parse($endTime);

                // Check if the provided times are within the range
                if ($startComparison >= Carbon::parse($event->start) && $endComparison <= Carbon::parse($event->end)) {
                    // If within range, delete the event
                    $event->delete();
                }
            }

            $j['success'] = true;
            $j['message'] = 'Events deleted successfully';
            $j['code'] =  200;
        } catch(\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] =  500;
        }



    }

    // FUNCION PARA LISTAR LOS EVENTOS POR TRIMESTRE
    public function listarEventsTrimester(Request $request){

        $data = [];
        $request->validate([
            'fechaInicio' => 'required|date_format:"Y-m-d"',
            'fechaFin' => 'required|date_format:"Y-m-d"'
        ]);

        // dd('hola');

        try{

            $dataEvent = DB::table('events as ev')
            ->join('study_sheets as st', 'st.id', '=', 'ev.study_sheet_id')
            ->leftJoin('teachers as t', 't.id', '=', 'ev.teacher_id')
            ->leftJoin('users as u', 'u.id', '=', 't.user_id')
            ->leftJoin('people as p', 'u.id', '=', 'p.user_id')
            ->join('environments as env', 'env.id', '=', 'ev.environment_id')
            ->join('quarters as q', 'q.id', '=', 'st.quarter_id')
            ->leftJoin('programs as pro', 'pro.id', '=', 'st.program_id')
            ->join('components as comp','comp.id','=','ev.component_id')
            ->rightJoin('coordinations as co','co.id','=','pro.coordination_id')
            ->select(
                DB::raw("st.number as nFicha"),
                DB::raw("st.num as num"),
                DB::raw("st.id as idFicha"),
                DB::raw("p.name as instructorName"),
                DB::raw("t.id as idInstructor"),
                DB::raw("p.lastname as instructorLastName"),
                DB::raw("u.state as estado"),
                DB::raw("env.name as ambiente"),
                DB::raw("env.id as idAmbiente"),
                DB::raw("q.name as trimestre"),
                DB::raw("q.id as idTrimestre"),
                DB::raw("pro.name as programa"),
                DB::raw("pro.id as idPrograma"),
                DB::raw("comp.id as idComponent"),
                DB::raw("comp.name as nameComponent"),
                DB::raw("co.name as nameCordination"),
                DB::raw("co.id as idCordination"),
                DB::raw("st.end_lective as endLective"),
                DB::raw("ev.start as inicio"),
                DB::raw("ev.end as final")
            )
            ->where('ev.start', '>=', $request->input('fechaInicio'))
            ->where('ev.end', '<=', now()->parse($request->input('fechaFin'))->addDay()->toDateString())
            ->orderBy('co.name', 'asc')
            ->get();

            // dd($request->input('fechaInicio'));

            $data['success'] = true;
            $data['data'] = $dataEvent;
            $data['message'] = 'Consulta exitosa';
            $data['code'] =  200;

            return response()->json($data);

        } catch (\Throwable $th) {
            // Log the exception to get more details
            $data['success'] = false;
            $data['message'] = $th->getMessage();
            $data['code'] =  500;
        }

    }


    // FUNCION PARA LISTAR LOS TRIMESTRES
    function listarTrimestre(){

        $j=[];
        $js = [];
        try {

            $trimestres= DB::table('year_quarters as yq')
            ->get();

            foreach($trimestres as $trimestre){
                $quarter_start = DB::table('year_quarters')->where('id', $trimestre->id)->value('start_date');
                $quarter_end = DB::table('year_quarters')->where('id', $trimestre->id)->value('finish_date');

                $fichas_programadas = DB::table('events as e')
                    ->join('study_sheets as ss', 'e.study_sheet_id', '=', 'ss.id')
                    ->where('e.start', '>=', $quarter_start)
                    ->where('e.end', '<=', $quarter_end)
                    ->select(DB::raw('COUNT(DISTINCT CONCAT(ss.number,ss.num)) as fichas_programadas'))
                    ->value('fichas_programadas');

                    // Agregar información al array $js
                    $js[] = [
                        'trimestre_id' => $trimestre->id,
                        'quarter_start' => $quarter_start,
                        'quarter_end' => $quarter_end,
                        'fichas_programadas' => $fichas_programadas,
                    ];
                }

                $j['success'] = true;
                $j['data'] = $js;
                $j['message'] = 'Consulta exitosa';
                $j['code'] =  200;

        } catch (\Throwable $th) {
                // Log the exception to get more details
                $j['success'] = false;
                $j['message'] = $th->getMessage();
                $j['code'] =  500;
        }

        return response()->json($j);
    }

    // FUNCION PARA LISTAR UN EVENTO POR TRIMESTRE
    public function listarEvent(Request $request)
    {
        $j = [];

        $request->validate([
            'fechaInicio' => 'required|date_format:"Y-m-d"',
            'fechaFin' => 'required|date_format:"Y-m-d"'
        ]);
        try {
           $eventos = DB::table('events as ev')
        ->join('study_sheets as st', 'st.id', '=', 'ev.study_sheet_id')
        ->leftJoin('teachers as t', 't.id', '=', 'ev.teacher_id')
        ->leftJoin('users as u', 'u.id', '=', 't.user_id')
        ->leftJoin('people as p', 'u.id', '=', 'p.user_id')
        ->join('environments as env', 'env.id', '=', 'ev.environment_id')
        ->join('quarters as q', 'q.id', '=', 'st.quarter_id')
        ->leftJoin('programs as pro', 'pro.id', '=', 'st.program_id')
        ->join('components as comp','comp.id','=','ev.component_id')
        ->rightJoin('coordinations as co','co.id','=','pro.coordination_id')
        ->select(
            DB::raw("st.number as nFicha"),
            DB::raw("st.num as num"),
            DB::raw("st.id as idFicha"),
            DB::raw("p.name as instructorName"),
            DB::raw("t.id as idInstructor"),
            DB::raw("p.lastname as instructorLastName"),
            DB::raw("env.name as ambiente"),
            DB::raw("env.id as idAmbiente"),
            DB::raw("q.name as trimestre"),
            DB::raw("q.id as idTrimestre"),
            DB::raw("pro.name as programa"),
            DB::raw("pro.id as idPrograma"),
            DB::raw("comp.id as idComponent"),
            DB::raw("comp.name as nameComponent"),
            DB::raw("co.name as nameCordination"),
            DB::raw("co.id as idCordination"),
            DB::raw("st.end_lective as endLective"),
            DB::raw("MIN(ev.start) as inicio"),
            DB::raw("MAX(ev.end) as final")
        )
        ->where('ev.start', '>=', $request->input('fechaInicio'))
        ->where('ev.end', '<=', $request->input('fechaFin'))
        ->groupBy(
            'st.number',
            'st.id',
            'p.name',
            'p.lastname',
            't.id',
            'env.name',
            'env.id',
            'q.name',
            'q.id',
            'pro.name',
            'pro.id',
            'comp.id',
            'comp.name',
            'co.name',
            'co.id'
        )
        ->orderBy('co.name', 'asc')
        ->get();


            $j['success'] = true;
            $j['data'] = $eventos;
            $j['message'] = 'Consulta exitosa';
            $j['code'] =  200;
        } catch (\Throwable $th) {
            // Log the exception to get more details
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] =  500;
        }

        return response()->json($j);
    }





}

