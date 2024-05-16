<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateInterval;
use DatePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class HorarioInformationTeacherController extends Controller
{
    //Funcion de llamado de la datatable de instructores
    public function datatableTeacherHR(){
        //Variables de retorno
        $totalEvents = 0;

        //Determinar cantidad de instructores
        $events = DB::table('events as ev')
        ->leftJoin('teachers as t', 't.id', '=', 'ev.teacher_id')
        ->select(
            DB::raw("t.id as idInstructor"),
        )
        ->where('ev.start', '>=', '2000-01-01')
        ->where('ev.end', '>=', '2000-01-01')
        ->whereNotNull('ev.teacher_id')
        ->groupBy(
            't.id',
        )
        ->orderBy('t.id', 'asc')
        ->get();
        $totalEvents = sizeof($events);

        //Cambiar nombre de la variable cuando se pruebe funcionalidad a quarters

        $trimestres = DB::table('year_quarters')
        ->get();
        //Cambiar nombre de la variable cuando se pruebe funcionalidad a colorsCoordinations
        $colorsCoprdination= DB::table('coordinations')
        ->get();
        $holidays= DB::table('holidays as d')
        ->get();
        $component_type = DB::table('components_type')
        ->get();



        return view('horarioinformation.instructor.index',compact('holidays','colorsCoprdination','totalEvents','trimestres','component_type'));
    }

    //Metodo para listar los instructores programados a una ficha
    public function listar(){
        $j = [];
        try {
            $eventsTeacher = DB::table('events as ev')
            ->leftJoin('teachers as t', 't.id', '=', 'ev.teacher_id')
            ->leftJoin('users as u', 'u.id', '=', 't.user_id')
            ->leftJoin('people as p', 'u.id', '=', 'p.user_id')
            ->rightJoin('documents_type as dt','dt.id','=','p.document_type_id')
            ->select(
                DB::raw("p.name as instructorName"),
                DB::raw("t.id as idInstructor"),
                DB::raw("p.lastname as instructorLastName"),
                DB::raw("p.document as document"),
                'dt.nicknames',

            )
            ->where('ev.start', '>=', '2000-01-01')
            ->where('ev.end', '>=', '2000-01-01')
            ->whereNotNull('ev.teacher_id')
            ->groupBy(
                't.id',
                'p.name',
                'p.lastname',
                'p.document',
                'dt.nicknames',
            )
            ->orderBy('t.id', 'asc')
            ->get();


            $j['success'] = true;
            $j['data'] = $eventsTeacher;
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

    //Listar eventos segun instructor
    public function showEventToTeacher(Request $request){
        $j = [];
        $request->validate([
            'idTeacher' => 'required',
        ]);

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
                DB::raw("st.num as num"),
                't.total_hours as totalHours',
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
            ->where('t.id','=',$request->input('idTeacher'))
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

    //Funcion para el llamado dehoras totales del trimestre
    public function totalHoursTeacherQuarter(Request $request){
        $request->validate([
            'currentDate'=>'required|date_format:Y-m-d'
        ]);
        $j = [];
        try{
            $currentDate = $request->input('currentDate');
            $yearQuarter = DB::table('year_quarters')
            ->where('start_date', '<=', $currentDate)
            ->where('finish_date', '>=', $currentDate)
            ->first();

            $monthsQuarter = [];

            $startDate = Carbon::parse($yearQuarter->start_date);
            $finishDate = Carbon::parse($yearQuarter->finish_date)->endOfMonth();
            $period = CarbonPeriod::create($startDate, '1 month', $finishDate);
            // dd($period);

            // Iterar sobre cada mes en el período y mostrar el nombre del mes
            foreach ($period as $date) {
                $month= $date->format('F');
                $monthsQuarter[]=$month;
            }

            $j['success'] = true;
            $j['data'] = $monthsQuarter;
            $j['message'] = 'Consulta exitosa';
            $j['code'] =  200;

        }catch(\Throwable $th){
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] =  500;
        }
        return response()->json($j);




    }

    public function busyAndAvailableHoursTeacher(Request $request){
        $request->validate([
            'currentDate'=>'required|date_format:Y-m-d',
            'idTeacher'=>'required'

        ]);

        $j=[];

        $data = [
            "horasPorMesOcupado"=>[],
            'horasPorMesDesocupado'=>[],
        ];
        try{
            $datesMonths=[];
            $dateToSheduleAvailable = [];
            $dateToSheduleBusy = [];

            $currentDate = $request->input('currentDate');
            $idTeacher = $request->input('idTeacher');

            $yearQuarter = DB::table('year_quarters')
            ->where('start_date', '<=', $currentDate)
            ->where('finish_date', '>=', $currentDate)
            ->first();

            $teacher = DB::table('teachers')
            ->where('id',$idTeacher)
            ->first();

            $totalHours =$teacher->total_hours;

            if ($yearQuarter) {
                $startDate = Carbon::parse($yearQuarter->start_date);
                $finishDate = Carbon::parse($yearQuarter->finish_date);

                $fechasOmitir = DB::table('holidays')->pluck('date')->toArray();

                $firstDayOfMonth = $startDate->firstOfMonth();
                $lastDayOfMonth = $finishDate->endOfMonth();

                $interval = new DateInterval('P1D');

                // Create a DatePeriod object with the first day of the month, interval, and last day of the month
                $period = new DatePeriod($firstDayOfMonth, $interval, $lastDayOfMonth);
                foreach ($period as $date) {
                    $datesMonths[] = $date->format('Y-m-d');
                }
                // Eliminar los festivos del array
                $dateToShedule = array_values(array_diff($datesMonths, $fechasOmitir));

                foreach ($dateToShedule as $date) {
                    // Asegúrate de que $date esté en el formato 'Y-m-d'
                    $fechaInicio1 = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
                    $fechaFinal1 = Carbon::createFromFormat('Y-m-d', $date)->endOfDay();

                    $eventToCreateDay = DB::table('events')
                        ->where('teacher_id', $idTeacher)
                        ->where(function ($query) use ($fechaInicio1, $fechaFinal1) {
                            $query->whereBetween('start', [$fechaInicio1, $fechaFinal1])
                                ->orWhereBetween('end', [$fechaInicio1, $fechaFinal1]);
                        })
                        ->select('start','end')
                        ->get();

                    if ($eventToCreateDay->count() > 0) {
                        foreach ($eventToCreateDay as $event) {
                            // Asegúrate de que start y end son instancias de Carbon antes de formatear
                            $event->start = Carbon::parse($event->start)->format('Y-m-d H:i:s');
                            $event->end = Carbon::parse($event->end)->format('Y-m-d H:i:s');
                            $dateToSheduleBusy[] = [$event->start, $event->end];
                        }
                    } else {
                        $dateToSheduleAvailable[] = [$date, $date];
                    }
                }


             $hoursMaxT = 0;
             foreach ($dateToSheduleAvailable as $day) {
                 $dayCarbon = Carbon::parse($day[1]);
                 $hoursFormatText = $dayCarbon->format('H') + 1;
                 $hoursMaxT += $hoursFormatText;
             }


                 $horasPorMesOcupado = [];

                 foreach ($dateToSheduleBusy as $evento) {
                     $fechaInicio10 = Carbon::parse($evento[0]);
                     $fechaInicioDiferent1 = Carbon::parse($evento[0])->format('H');
                     $fechaFinal10 = Carbon::parse($evento[1])->addMinute()->format('H');

                     $diferenciaHoraBlock1 = $fechaFinal10 - $fechaInicioDiferent1;

                     $mes1 = $fechaInicio10->format('F');


                     $horasPorMesOcupado[$mes1] = ($horasPorMesOcupado[$mes1] ?? 0) + $diferenciaHoraBlock1;
                 }

                 $horasPorMesDesocupado = [];

                 foreach ($dateToSheduleBusy as $evento) {

                    $fechaInicio = Carbon::parse($evento[0]);


                     // Sumar la diferencia a las horas programadas por mes y trimestre
                     $mes = $fechaInicio->format('F');


                     $horasPorMesDesocupado[$mes] = $totalHours;
                 }

                 foreach ($horasPorMesDesocupado as $mes => $horas) {
                     if ($horas <= $totalHours) {
                         $data['error'] = false;
                         $data['horasPorMesDesocupado'][$mes]=$totalHours;

                         // Aquí puedes agregar más lógica según sea necesario
                     } else {
                         $data['error'] = true;
                         // Aquí puedes manejar este caso
                     }

                 }

                 // Ahora, puedes comparar las horas programadas con las horas disponibles

                     foreach ($horasPorMesOcupado as $mes => $horas) {
                         if ($horas <= $totalHours) {
                             $data['horasPorMesOcupado']+=[$mes=>$horas];
                             $data['horasPorMesDesocupado'][$mes]=$data['horasPorMesDesocupado'][$mes]-$horas;


                             // Aquí puedes agregar más lógica según sea necesario
                         } else {
                             $data['horasPorMesOcupado']+=[$mes=>$horas];
                             $data['horasPorMesDesocupado'][$mes]=$data['horasPorMesDesocupado'][$mes]-$horas;
                             // Aquí puedes manejar este caso
                         }

                     }






            }


            $j['success'] = true;
            $j['data'] = $data  ;
            $j['message'] = 'Consulta exitosa';
            $j['code'] =  200;
        }catch(\Throwable $th){
            $j['success']=false;
            $j['message'] = $th->getMessage();
            $j['code']=500;
        }
        return response()->json($j);
    }




}
