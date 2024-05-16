<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use App\Models\Holiday;
use App\Models\Componente;
use App\Models\Evento;
use App\Models\Jornada;
use App\Models\Oferta;
use App\Models\Trimestre;
use App\Services\horario\EventValidationDaySS;
use App\Services\horario\EventValidationService;
use App\Services\horario\EventValidationStudySHeadQuartersService;
use App\Services\horario\EventValidationStudySheetService;
use App\Services\horario\EventValidationTeacherFinallyService;
use App\Services\horario\EventValidationTeacherService;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventoController extends Controller
{
    public function index()
    {

        return view('horarios.index')->render();
    }


    public function create()
    {
        $programas = DB::table('programs as p')
            ->join('program_type as pt', 'p.program_type_id', '=', 'pt.id')
            ->rightJoin('coordinations as co','co.id','=','p.coordination_id')
            ->where('co.state',true)
            ->where('pt.state', true)
            ->where('p.state', 'activo')
            ->select('p.id', 'p.name', 'p.description', 'p.coordination_id', 'p.duration' )
            ->get();
        return view('horarios.create', compact('programas'))->render();
    }


    //////////////////////////////////////////////////////////////////////////////////
    //DATATABLE EVENTOS
    public function datatable()
    {
        return view('horarios.datatable')->render();
    }

    public function findStudySheets(Request $request)
    {
        $j = [];

        try {
            $programa = $request->input('programa'); // id del programa
            $jornadas = Jornada::where('state', 'activo')->get();
            $ofertas = Oferta::where('state', 'activo')->get();
            $trimestres = Trimestre::all();


            $fichas = DB::table('study_sheets as fichas')
                ->select('days.name as day', 'fichas.number', 'fichas.num', 'fichas.id', 'fichas.program_id', 'fichas.day_id', 'fichas.offer_id', 'fichas.quarter_id', 'fichas.start_lective', 'fichas.end_lective')
                ->join('days', 'days.id', '=', 'fichas.day_id')
                ->where('fichas.program_id', '=', $programa)
                ->where('fichas.state','=','activo')
                ->where('days.state','=','activo')
                ->get();


            $j['success'] = true;
            $j['message'] = 'Consulta exitosa';
            $j['data'] = [$fichas, $jornadas, $ofertas, $trimestres];
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['data'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    // Base function - baseOptions (data -> id ficha, id program)
    public function baseOptions(Request $request) {

        $j = [];

        try {

            // Datos ficha
            $ficha = $request -> input('ficha'); // Id study_sheet
            $programa = DB::table('study_sheets') // Id study_sheet-program
                -> where('id', $ficha)
                -> pluck('program_id')
                -> first();

            // Components
            $componentes = DB::table('components') // study_sheet-components
                ->where('cp.program_id', $programa)
                ->where('components.state','=','activo')
                ->join('components_type', 'components_type.id', '=', 'components.component_type_id')
                ->join('components_programs as cp', 'components.id', '=', 'cp.component_id')
                ->where('components_type.state','=',true)
                ->select('components.id', 'components.name', 'components_type.name as type_name') // Id, name y component-name
                ->get();

            // Year quarter
            $trimestres = DB::table('year_quarters')->where('state', 'activo')->get();
            /* dd($trimestres) ; */

            // time block
            $bloques = DB::table('blocks as b')
            ->leftJoin('days as d','b.day_id','=','d.id')
            ->leftJoin('study_sheets as st','st.day_id','=','d.id')
            ->where('st.id','=',$ficha)
            ->where('b.state', true)
            -> select('b.id', 'b.time_start', 'b.time_end')
            -> get(); // time-range


            $j['success'] = true;
            $j['message'] = 'Consulta exitosa';
            $j['data'] = [$componentes, $trimestres, $bloques]; // Send data
            $j['code'] = 200;

        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['data'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function ambiente(Request $request) {

        $j = [];

        try {

            // ID del componente
            $idComponente = $request->input('component');

            // Tipo del componente (trans or tec)
            $idTypeComponent = DB::table('components')
                ->where('id', $idComponente)
                ->pluck('component_type_id')
                ->first();

            $idCoordination= DB::table('components as c')
                ->join('components_programs as cp','c.id','=','cp.component_id')
                ->join('programs as p','p.id','=','cp.program_id')
                ->join('coordinations as cor','cor.id','=','p.coordination_id')
                ->where('c.id','=', $idComponente)
                ->pluck('cor.id');

            // Obtener los IDs de los ambientes asociados al tipo de componente
            $ambientesIds = DB::table('environment_components')
                ->where('component_type_id', $idTypeComponent)
                ->pluck('environment_id');

            $ambienteIdsCoordination= DB::table('environment_coordinations')
                ->whereIn('coordination_id', $idCoordination)
                ->pluck('environment_id');

            // Obtener los IDs que se repiten en ambos conjuntos
            $commonIds = $ambientesIds->intersect($ambienteIdsCoordination);

            // Convierte los IDs a un array
            $finalIds = $commonIds->toArray();


            // Obtener los ambientes basados en los IDs obtenidos
            $environments = DB::table('environments')
                ->whereIn('environments.id', $finalIds)
                ->where('environments.state','=','activo')
                ->join('headquarters', 'headquarters.id', '=', 'environments.headquarter_id')
                ->where('headquarters.state', '=', 'activo')
                ->select('environments.id', 'environments.name')
                ->get();


            $j = [
                'success' => true,
                'message' => 'Consulta exitosa',
                'data' => $environments->toArray(),
                'code' => 200
            ];
        }

        catch (\Throwable $th) {
            $j['success'] = false;
            $j['data'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);

    }

    public function instructor(Request $request) {

        $j = [];

        try {

            // Id ficha
            $idFicha = $request -> input('ficha');

            //
            $proId = DB::table('study_sheets')->where('id', $idFicha)->pluck('program_id')->first();

            // id coordinación
            $coordId = DB::table('study_sheets')
                ->join('programs', 'programs.id', '=', 'study_sheets.program_id')
                ->where('programs.id', $proId)
                ->pluck('programs.coordination_id as coordination')
                ->first();

            // Instructores
            $instructores = DB::table('teachers')
                ->select(
                    'teachers.id as id',
                    'users.id as uId',
                    'people.name as name',
                    'people.lastname as lastname',
                    'people.document as document',
                    'contracts.name as contract'
                )
                ->join('users', 'users.id', '=', 'teachers.user_id')
                ->join('people', 'people.user_id', '=', 'users.id')
                ->join('contracts', 'contracts.id', '=', 'teachers.contract_id')
                ->join('teachers_coordinations', 'teachers_coordinations.teacher_id', '=', 'teachers.id')
                ->join('coordinations', 'coordinations.id', '=', 'teachers_coordinations.coordination_id')
                ->where('coordinations.id', $coordId) // Filtro por ID de coordinación si es necesario
                ->where('users.state','=','activo')
                ->get();

            $j = [
                'success' => true,
                'message' => 'Consulta exitosa',
                'data' => $instructores -> toArray(),
                'code' => 200
            ];

        }

        catch (\Throwable $th) {
            $j['success'] = false;
            $j['data'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);

    }

    public function findOptions(Request $request)
    {
        $j = [];

        try {
            $ficha = $request->input('ficha');

            $proId = DB::table('study_sheets')->where('id', $ficha)->pluck('program_id')->first();

            $triId = DB::table('study_sheets')->where('id', $ficha)->pluck('quarter_id')->first();

            $coordId = DB::table('study_sheets')
                ->join('programs', 'programs.id', '=', 'study_sheets.program_id')
                ->where('programs.id', $proId)
                ->pluck('programs.coordination_id as coordination')
                ->first();

            $componentes = DB::table('components')
                ->select(
                    'components.id as id',
                    'components.name as name',
                    'components_type.name as type'
                )
                ->join('components_type', 'components_type.id', '=', 'components.component_type_id')
                // ->where('program_id', '=', $proId)
                // ->where('quarter_id', '=', $triId)
                ->get();

            $ambientes = DB::table('environments')->get();

            $trimestres = DB::table('year_quarters')->select('id', 'name', 'start_date', 'finish_date')->get();

            $bloquesPrueba = DB::table('blocks')->select('id', 'time_start', 'time_end')->get();

            $instructores = DB::table('teachers')
                ->select(
                    'teachers.id as id',
                    'users.id as uId',
                    'people.name as name',
                    'people.lastname as lastname',
                    'contracts.name as contract'
                )
                ->join('users', 'users.id', '=', 'teachers.user_id')
                ->join('people', 'people.user_id', 'users.id')
                ->join('contracts', 'contracts.id', '=', 'teachers.contract_id')
                ->where('teachers.coordination_id', $coordId)
                ->get();

            $j['success'] = true;
            $j['message'] = 'Consulta exitosa';
            $j['data'] = [$componentes, $ambientes, $trimestres, $bloquesPrueba];
            $j['code'] = 200;
        }

        catch (\Throwable $th)

        {
            $j['success'] = false;
            $j['data'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);

    }

    public function store(Request $request)
    {

        $j = [];

        try {

            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // VARIALE

            // Variable para el rango de fechas
            $fechas = $request->input('fechas');

            // Variable para el componete
            $componente = $request->input('componente');

            // Variable para el ambiente
            $ambiente = $request->input('ambiente');

            // Variable para la ficha
            $ficha = $request->input('ficha');

            // Variable para la hora de inicio del bloque
            $horaInicio = $request->input('bloqueI');

            // Variable para la hora final del bloque
            $horaFinal = $request->input('bloqueF');

            // Variable para la jornada de la ficha
            $jornada = $request->input('fichaJor');

            // Variale para la fecha incial
            $fechaA = $request->input('inicioInput');

            // Variale para la fecha final
            $fechaB = $request->input('finalInput');

            // ID Instructor
            // $instructorID = null;
            $instructorID = $request -> input('instructor');

            //Confirmacion de programacion despues de alerta
            $confirmation1 = $request -> input('confirmation1');
            $confirmation2 = $request -> input('confirmation2');
            ///////////////////////////////////////
            //Confirmacion de programacion ficha
            $confirmation3 = $request -> input('confirmation3');
            //Confirmacion de programacion ficha
            $confirmation4 = $request -> input('confirmation4');
            // Url para redirigir al index después de generar las sesiones
            $url = route('horarios.index');

            // Array para almacenar las fechas programadas deentro del for each
            $fechasEstablecidas = [];

            //Horas maximas del componente
            $componentHours = DB::table('components as comp')
            ->where('comp.id','=',$componente)
            ->select('total_hours')
            ->get();
            $componentHours= $componentHours[0]->total_hours;
            //HORAS TOTALES ENTRE BLOQUES
            $totalHoursBlock =  $this->blockHours($horaInicio,$horaFinal);


            // CONTADOR PARA MEDIR LAS HORAS MAXIMAS
            $countMaxHours = 0;

            // Tipo de componente que se programó
            $tipoComponente = Componente::select('components_type.name')
                ->join('components_type', 'components_type.id', '=', 'components.component_type_id')
                ->where('components.id', $componente)
                ->first();

            $type = $tipoComponente->name;

            // Programa de la ficha que se está asignando
            $proId = DB::table('study_sheets')->where('id', $ficha)->pluck('program_id')->first();

            // Coordinación de la ficha que se está asignando
            $coordId = DB::table('study_sheets')
                ->join('programs', 'programs.id', '=', 'study_sheets.program_id')
                ->where('programs.id', $proId)
                ->pluck('programs.coordination_id as coordination')
                ->first();

            /////////////////////////////////////////////////////////////////////////////////////
            //Objetos de validaciones
            $validationEvent = new EventValidationService();
            $validationEventStudySheet = new EventValidationStudySheetService();
            $validationEventTeacher = new EventValidationTeacherService();
            $validationEventTeacherFinally = new EventValidationTeacherFinallyService();
            $validationDayStudyS = new EventValidationDaySS();
            $validateHeadQuarterStudyS = new EventValidationStudySHeadQuartersService();
            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // LISTAR LOS INSTRUCTORES SEGUN EL COMPONENTE QUE SE ESTA PROGRAMANDO

            if ($type === 'Tecnica') {
                $instructores = DB::table('teachers')
                    ->select(
                        'teachers.id as id',
                        'users.id as uId',
                        'people.name as name',
                        'people.lastname as lastname',
                        'contracts.name as contract',
                        'tct.components_type_id as type',
                    )
                    ->join('users', 'users.id', '=', 'teachers.user_id')
                    ->join('people', 'people.user_id', '=', 'users.id')
                    ->join('contracts', 'contracts.id', '=', 'teachers.contract_id')
                    ->join('teachers_coordinations', 'teachers_coordinations.teacher_id', '=', 'teachers.id')
                    ->rightJoin('teachers_components_type as tct', 'tct.teachers_id', '=', 'teachers.id')
                    ->where('teachers_coordinations.coordination_id', $coordId)
                    ->where('tct.components_type_id', 1)
                    ->get();
            } elseif ($type === 'Transversal') {
                $instructores = DB::table('teachers')
                    ->select(
                        'teachers.id as id',
                        'users.id as uId',
                        'people.name as name',
                        'people.lastname as lastname',
                        'contracts.name as contract',
                        'tct.components_type_id as type',
                    )
                    ->join('users', 'users.id', '=', 'teachers.user_id')
                    ->join('people', 'people.user_id', '=', 'users.id')
                    ->join('contracts', 'contracts.id', '=', 'teachers.contract_id')
                    ->join('teachers_coordinations', 'teachers_coordinations.teacher_id', '=', 'teachers.id')
                    ->rightJoin('teachers_components_type as tct', 'tct.teachers_id', '=', 'teachers.id')
                    ->where('teachers_coordinations.coordination_id', $coordId)
                    ->where('tct.components_type_id', 2)
                    ->get();

            }

            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // FUNCIÓN DE ALMACENAMEINTO DE LA LOGICA PARA PROGRAMAR LAS SESIONES

            function crearEvento($ambiente, $componente, $ficha, $fechaInicio, $fechaFinal, $totalHours, $instructorID)
            {
                return Evento::create([
                    'environment_id' => $ambiente,
                    'component_id' => $componente,
                    'study_sheet_id' => $ficha,
                    'study_sheet_state' => 'programado',
                    'environment_state' => 'programado',
                    'start' => $fechaInicio->toDateTimeString(),
                    'end' => $fechaFinal->toDateTimeString(),
                    'total_hours' =>  $totalHours,
                    'teacher_id' => $instructorID
                ]);
            }

            $eventoCreate = false;
            $programacionInversa = false;
            $control = false;

            // ! Prueba para programar sin días festivos
            $fechasOmitir = DB::table('holidays')->pluck('date')->toArray();
            $fechaCero = '1999-12-30';

            $fechaInicioTrimestre = Carbon::createFromFormat('Y-m-d', $fechaA);
            $diaInicioTrimestre = Carbon::parse($fechaInicioTrimestre)->day;

            // Elimino los festivos del array de trimestre
            $fechasAProgramar = array_diff($fechas, $fechasOmitir);

            // Asignar un valor determinado al array en el indice 0

            if ($diaInicioTrimestre % 2 == 0){
                array_unshift($fechasAProgramar);
            }
            else {
                array_unshift($fechasAProgramar, $fechaCero);
            }

            // Reasigno los indices del array
            $fechasAProgramar = array_values($fechasAProgramar);

            $validationEventEnvironment =$validationEvent->validationDateCompleted($fechas,$componentHours,$totalHoursBlock,$ambiente,$horaInicio,$horaFinal,$jornada);
            //validacion sedes con ficha
            $dataValidateHeadQuarter = [
                "dates"=>$fechas,
                "totalHoursComponent"=>$componentHours,
                "totalHoursBlock"=>$totalHoursBlock,
                "studySheetId"=>$ficha,
                "startTime"=>$horaInicio,
                "endTime"=>$horaFinal,
                "days"=>$jornada
            ];

            // $validateHeadQuarterStudySDifferent = $validateHeadQuarterStudyS->eventValidateDayForDayHQ($dataValidateHeadQuarter);

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //Validacion ficha

            $data = [
                "fechas"=>$fechas,
                "componentHours"=>$componentHours,
                "totalHoursBlock"=>$totalHoursBlock,
                "ficha"=>$ficha,
                "horaInicio"=>$horaInicio,
                "horaFinal"=>$horaFinal,
                "jornada"=>$jornada
            ];

            $validationEventStudyS = $validationEventStudySheet->messgeValidatioStudyS($data);

            //Validacion horas maximas diarias de una ficha

            $dataStudySheetH = [
                "fechas"=>$fechas,
                "componentHours"=>$componentHours,
                "totalHoursBlock"=>$totalHoursBlock,
                "ficha"=>$ficha,
                "horaInicio"=>$horaInicio,
                "horaFinal"=>$horaFinal,
                "jornada"=>$jornada,
                "typeEvent"=>''
            ];
            $validationEventStudySHM = null;
            $eventValDayStudyS = $validationDayStudyS->validateDayStudySheet($jornada,$ficha,$horaInicio,$horaFinal);
            Log::info($eventValDayStudyS['message']);

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ///Validacion Instructor
            // $validationTeacher =$validationEventTeacherFinally->eventValidationTeacherMenssage($fechas,$componentHours,$totalHoursBlock,$instructorID,$horaInicio,$horaFinal,$jornada);

            // dd($validationTeacher);

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



            if($jornada ==='fin de semana'){
                if ($validationEventEnvironment['evenAvailable'] > 0 && $validationEventEnvironment['oddAvailable'] > 0) {
                    $message = "¡Atención! La programación no puede continuar debido a la disponibilidad de sesiones en el ambiente y bloque de tiempos seleccionado. Actualmente, hay {$validationEventEnvironment['evenAvailable']} sesiones para un evento A y {$validationEventEnvironment['oddAvailable']} sesiones para un evento B en fines de semana. Sin embargo, la ficha requiere de {$validationEventEnvironment['totalEvents']} sesiones en el bloque de tiempo seleccionado.
                    Considere cambiar el bloque horario o seleccionar otro ambiente para continuar.";
                }else if($validationEventEnvironment['evenAvailable'] <= 0 && $validationEventEnvironment['oddAvailable'] > 0){
                    $message = "¡Atención! La programación no puede continuar debido a la disponibilidad de sesiones en el ambiente y bloque de tiempos seleccionado. Actualmente, hay {$validationEventEnvironment['oddAvailable']} sesiones para un evento B en fines de semana. Sin embargo, la ficha requiere de {$validationEventEnvironment['totalEvents']} sesiones en el bloque de tiempo seleccionado.
                    Considere cambiar el bloque horario o seleccionar otro ambiente para continuar.";
                }else if($validationEventEnvironment['evenAvailable'] > 0 && $validationEventEnvironment['oddAvailable'] <= 0){
                    $message = "¡Atención! La programación no puede continuar debido a la disponibilidad de sesiones en el ambiente y bloque de tiempos seleccionado. Actualmente, hay {$validationEventEnvironment['oddAvailable']} sesiones para un evento A en fines de semana. Sin embargo, la ficha requiere de {$validationEventEnvironment['totalEvents']} sesiones en el bloque de tiempo seleccionado.
                    Considere cambiar el bloque horario o seleccionar otro ambiente para continuar.";
                }else if($validationEventEnvironment['evenAvailable'] <= 0 && $validationEventEnvironment['oddAvailable'] <= 0){
                    $message = "¡Atención! La programación no puede continuar debido a la disponibilidad de sesiones en el ambiente y bloque de tiempos seleccionado. Actualmente, el ambiente no cuenta con disponibilidad para generar la cantidad de sesiones necesarias para el evento en fines de semana. La ficha requiere de {$validationEventEnvironment['totalEvents']} sesiones en el bloque de tiempo seleccionado.
                    Considere cambiar el bloque horario o seleccionar otro ambiente para continuar.";
                }else{
                    $message = "ATENCIÓN! OCURRIO UN ERROR EN LA VALIDACIÓN DE AMBIENTE.";
                }
            }

            if ($validationEventEnvironment['evenAvailable'] > 0 && $validationEventEnvironment['oddAvailable'] > 0) {
                $message = "¡Atención! La programación no puede continuar debido a la disponibilidad de sesiones en el ambiente y bloque de tiempos seleccionado. Actualmente, hay {$validationEventEnvironment['evenAvailable']} sesiones para un evento A y {$validationEventEnvironment['oddAvailable']} sesiones para un evento B. Sin embargo, la ficha requiere de {$validationEventEnvironment['totalEvents']} sesiones en el bloque de tiempo seleccionado.
                Considere cambiar el bloque horario o seleccionar otro ambiente para continuar.";
            }else if($validationEventEnvironment['evenAvailable'] <= 0 && $validationEventEnvironment['oddAvailable'] > 0){
                $message = "¡Atención! La programación no puede continuar debido a la disponibilidad de sesiones en el ambiente y bloque de tiempos seleccionado. Actualmente, hay {$validationEventEnvironment['oddAvailable']} sesiones para un evento B. Sin embargo, la ficha requiere de {$validationEventEnvironment['totalEvents']} sesiones en el bloque de tiempo seleccionado.
                Considere cambiar el bloque horario o seleccionar otro ambiente para continuar.";
            }else if($validationEventEnvironment['evenAvailable'] > 0 && $validationEventEnvironment['oddAvailable'] <= 0){
                $message = "¡Atención! La programación no puede continuar debido a la disponibilidad de sesiones en el ambiente y bloque de tiempos seleccionado. Actualmente, hay {$validationEventEnvironment['evenAvailable']} sesiones para un evento A. Sin embargo, la ficha requiere de {$validationEventEnvironment['totalEvents']} sesiones en el bloque de tiempo seleccionado.
                Considere cambiar el bloque horario o seleccionar otro ambiente para continuar.";
            }else if($validationEventEnvironment['evenAvailable'] <= 0 && $validationEventEnvironment['oddAvailable'] <= 0){
                $message = "¡Atención! La programación no puede continuar debido a la disponibilidad de sesiones en el ambiente y bloque de tiempos seleccionado. Actualmente, el ambiente no cuenta con disponibilidad para generar la cantidad de sesiones necesarias para el evento. La ficha requiere de {$validationEventEnvironment['totalEvents']} sesiones en el bloque de tiempo seleccionado.
                Considere cambiar el bloque horario o seleccionar otro ambiente para continuar.";
            }else{
                $message = "ATENCIÓN! OCURRIO UN ERROR EN LA VALIDACIÓN DE AMBIENTE.";
            }

            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // PROGRAMACIÓN //

            if ($type === 'Tecnica') {

                /////////////////////////////////////////////////////////////////////////
                // VALIDACIONES

                // VALIDACIÓN COMPONENTE TECNICO UNICO DENTRO DEL TRIMESTRE
                $programaTecnicoUnico = DB::table('events')
                    ->join('components', 'events.component_id', '=', 'components.id')
                    ->join('components_programs as cp', 'cp.component_id', '=', 'components.id')
                    ->join('programs as p', 'cp.program_id', '=', 'p.id')
                    ->join('coordinations as c','p.coordination_id','=','c.id')
                    ->where('events.study_sheet_id', $ficha)
                    ->where('components.component_type_id', 1)
                    ->where('events.end', '>=', $fechaA)
                    ->where('events.end', '<=', $fechaB)
                    ->where('c.multi_technique', '!=', true) // verifica si la coordinacion es multitecnica
                    ->exists();

                // VALIDACIÓN COMPONENTE NO REPETIDO
                $componenteRepetido = DB::table('events')
                    -> where('events.study_sheet_id', $ficha)
                    -> where('events.component_id', $componente)
                    -> where('events.end', '>=', $fechaA)
                    -> where('events.end', '<=', $fechaB)
                    -> exists();

                /////////////////////////////////////////////////////////////////////////
                // MENSAJES DE PROGRAMACIÓN INTERRUMPIDA

                // MENSAJE DE PROGRMAMACIÓN INTERRUMPIDA - VALIDACIÓN COMPONENTE TECNICO UNICO DENTRO DEL TRIMESTRE

                if ($programaTecnicoUnico) {

                    Log::info('Programación interrumpida - Ficha ya cuenta con programación tecnica');
                    $j['success'] = false;
                    $j['message'] = "Programación Interrumpida - La ficha ya cuenta con una programación tecnica en el trimestre.";
                    $j['code'] = 500;

                }else if($eventValDayStudyS['error']==true){
                    Log::info($eventValDayStudyS['message']);
                    $j['success'] = false;
                    $j['message'] = $eventValDayStudyS['message'];
                    $j['code'] = 500;
                }else if ($validationEventEnvironment["state"] === false && $confirmation1===true && $confirmation2!==true) {
                    if($jornada ==='fin de semana'){
                        Log::info($message);

                        $j['success'] = false;
                        $j['evenDayAvailable'] = $validationEventEnvironment["evenAvailable"];
                        $j['oddDayAvailable'] = $validationEventEnvironment["oddAvailable"];
                        $j['totalEvents'] = $validationEventEnvironment["totalEvents"];
                        $j['message'] = $message;
                        $j['code'] = 500;
                    }else{
                        Log::info($message);
                        $j['success'] = false;
                        $j['evenDayAvailable'] = $validationEventEnvironment["evenAvailable"];
                        $j['oddDayAvailable'] = $validationEventEnvironment["oddAvailable"];
                        $j['totalEvents'] = $validationEventEnvironment["totalEvents"];
                        $j['message'] = $message;
                        $j['code'] = 500;
                    }
                }
                ///////////////////////////////////////////////////////////////////////////////
                //Validacion Ficha
                else if ($validationEventStudyS["status"] === false && $confirmation1===true && $confirmation3!==true) {
                    $messageStudySheet = $validationEventStudyS["message"];
                    log::info($validationEventStudyS);
                    // dd($validationEventStudyS);
                    if($jornada ==='fin de semana'){
                        Log::info($messageStudySheet);

                        $j['success'] = false;
                        $j['evenDayAvailableST'] = $validationEventStudyS["evenDayAvailableST"];
                        $j['oddDayAvailableST'] = $validationEventStudyS["oddDayAvailableST"];
                        $j['totalEventsST'] = $validationEventStudyS["totalEventsST"];
                        $j['message'] = $messageStudySheet;
                        $j['code'] = 500;
                    }else{
                        Log::info($messageStudySheet);
                        $j['success'] = false;
                        $j['evenDayAvailableST'] = $validationEventStudyS["evenDayAvailableST"];
                        $j['oddDayAvailableST'] = $validationEventStudyS["oddDayAvailableST"];
                        $j['totalEventsST'] = $validationEventStudyS["totalEventsST"];
                        $j['message'] = $messageStudySheet;
                        $j['code'] = 500;
                    }
                }

                // MENSAJE DE PROGRMAMACIÓN INTERRUMPIDA - VALIDACIÓN COMPONENTE NO REPETIDO
                else if ($componenteRepetido ) {

                    Log::info('Programación interrumpida - La ficha ya cuenta en el trimestre con esa componente asignada');
                    $j['success'] = false;
                    $j['message'] = 'Programación interrumpida - La ficha ya cuenta en el trimestre con ese componentencia asignada';
                    $j['code'] = 500;

                }

                else {
                    //Objetos

                    /////////////////////////////////////////////////////////////////////////
                    // CICLO PARA PROGRAMAR
                    for ($i = 0; $i < sizeof($fechasAProgramar); $i++) {
                        if ($countMaxHours > $componentHours) break;

                        $fechaInicio = Carbon::createFromFormat('Y-m-d', $fechasAProgramar[$i]);
                        $fechaFinal = Carbon::createFromFormat('Y-m-d', $fechasAProgramar[$i]);

                        $fechaDia = Carbon::parse($fechasAProgramar[$i])->day; // Examinar si trimestre inicia en par o impar

                        // CONVERTIR LAS FECHAS A FORMATO DATETIME
                        list($hI, $minI) = explode(':', $horaInicio);
                        $fechaInicio->setHour($hI);
                        $fechaInicio->setMinute($minI);
                        $fechaInicio->setSeconds(0);

                        list($hF, $minF) = explode(':', $horaFinal);
                        $fechaFinal->setHour($hF);
                        $fechaFinal->setMinute($minF);
                        $fechaFinal->setSeconds(0);

                        $diferenciaMin = $fechaInicio->diffInMinutes($fechaFinal);
                        $totalHours = ceil($diferenciaMin / 60);

                        $busyEnvironment = $validationEvent->validateEventByConditionEnvironment($ambiente,$fechaInicio,$fechaFinal);
                        $busyStudySheet = $validationEventStudySheet->validateEventByConditionStudyS($ficha,$fechaInicio,$fechaFinal);
                        $busyTeacher = $validationEventTeacher->validateEventByConditionTeacher($instructorID,$fechaInicio,$fechaFinal);

                        if (

                            ($jornada === 'mañana' && $fechaInicio->isWeekday() && $fechaFinal->isWeekday()) ||
                            ($jornada === 'tarde' && $fechaInicio->isWeekday() && $fechaFinal->isWeekday()) ||
                            ($jornada === 'noche' && $fechaInicio->isWeekday() && $fechaFinal->isWeekday()) ||
                            ($jornada === 'fin de semana' && $fechaInicio->isWeekend() && $fechaFinal->isWeekend())

                            )

                        {

                            $ambienteProgramado = DB::table('events')
                            ->where('environment_id', $ambiente)
                            ->where(function ($query) use ($fechaInicio, $fechaFinal) {
                                $query->whereBetween('start', [$fechaInicio, $fechaFinal])
                                    ->orWhereBetween('end', [$fechaInicio, $fechaFinal]);
                                $query->where('start', '<', $fechaFinal);
                            })->exists();

                            if ($ambienteProgramado && $control == false && $confirmation1===true && $confirmation2!==true && $confirmation3!==true) {

                                Log::info('Programación Interrumpida - El ambiente ya está programado en el mismo bloque');
                                $j['success'] = false;
                                $j['message'] = 'Programación Interrumpida - El ambiente ya está programado en el mismo bloque';
                                $j['code'] = 500;
                                $programacionInversa = true; // Habilitar examinación de la programación inversa

                            }

                            else {

                                // PROGRAMAR DIA PAR - TECNICA
                                if ($programacionInversa == false && $i % 2 === 0) {

                                    ///////////////////////////////////////////////////////////
                                    //Validacion horas maximas ficha
                                    $dataStudySheetH['typeEvent']='A';
                                    $validationEventStudySHM = $validationEventStudySheet->evaluateMaximunDailyHours($dataStudySheetH);
                                    ///////////////////////////////////////////////////////////
                                    // EVENTOS POR CONDICIONES PARA RECORRER
                                    $eventsST = DB::table('events as ev')
                                        ->where('study_sheet_id','=',$ficha)
                                        ->where('start','=',$fechaInicio->toDateTimeString())
                                        ->where('end','=',$fechaFinal->toDateTimeString())
                                        ->exists();
                                    //Objeto
                                    if ($busyEnvironment==false) {
                                        if (!$eventsST) {
                                            if($busyStudySheet==false){
                                                $countMaxHours += $totalHoursBlock;
                                                if($validationEventStudySHM['state']==true){
                                                    Log::info('Programación interrumpida - Ficha no disponile por exceder las horas maximas en el dia.');
                                                    $j['success'] = false;
                                                    $j['message'] =$validationEventStudySHM['message'];
                                                    $j['code'] = 500;
                                                    break;
                                                }

                                                if ($countMaxHours > $componentHours) break;

                                                Log::info('se programa dia par tecnica');
                                                Log::info($fechaDia);
                                                crearEvento($ambiente, $componente, $ficha, $fechaInicio, $fechaFinal, $totalHours, $instructorID);
                                                $eventoCreate = true;
                                                $control = true;
                                            }
                                        }
                                    }
                                }

                                // PROGRAMAR DIA IMPAR - TECNICA
                                elseif (!$eventoCreate && $programacionInversa && $i % 2 !== 0) {
                                    ///////////////////////////////////////////////////////////
                                    //Validacion horas maximas ficha
                                    $dataStudySheetH['typeEvent']='B';
                                    $validationEventStudySHM = $validationEventStudySheet->evaluateMaximunDailyHours($dataStudySheetH);

                                    ///////////////////////////////////////////////////////////
                                    // EVENTOS POR CONDICIONES PARA RECORRER
                                    $eventsST = DB::table('events as ev')
                                        ->where('study_sheet_id','=',$ficha)
                                        ->where('start','=',$fechaInicio->toDateTimeString())
                                        ->where('end','=',$fechaFinal->toDateTimeString())
                                        ->exists();
                                    if ($busyEnvironment==false) {
                                        if (!$eventsST && $busyEnvironment==false) {
                                                if($busyStudySheet==false){
                                                    if($validationEventStudySHM['state']==true){
                                                        Log::info('Programación interrumpida - Ficha no disponile por exceder las horas maximas en el dia.');
                                                        $j['success'] = false;
                                                        $j['message'] = $validationEventStudySHM['message'];
                                                        $j['code'] = 500;
                                                        break;
                                                    }
                                                    $countMaxHours += $totalHoursBlock;
                                                if ($countMaxHours > $componentHours) break;
                                                Log::info('se programa dia impar tecnica');
                                                Log::info($fechaDia);
                                                crearEvento($ambiente, $componente, $ficha, $fechaInicio, $fechaFinal, $totalHours, $instructorID);
                                                $programacionInversa = true;
                                                $control = true;
                                            }
                                        }
                                    }

                                }

                                // MENSAJE DE PROGRAMACIÓN EXITOSA
                                $j['success'] = true;
                                $j['url'] = $url;
                                $j['message'] = 'Sesiones generadas correctamente';
                                $j['code'] = 200;
                                $j['data'] = $instructores;

                            }

                        }

                    }
                }

            }

            else if ($type === 'Transversal') {

                /////////////////////////////////////////////////////////////////////////
                // VALIDACIONES

                // VALIDACIÓN PROGRAMACIÓN REPETIDA
                $componenteRepetido = DB::table('events')
                -> where('events.study_sheet_id', $ficha)
                -> where('events.component_id', $componente)
                -> where('events.end', '>=', $fechaA)
                -> where('events.end', '<=', $fechaB)
                -> exists();


                /////////////////////////////////////////////////////////////////////////
                // MENSAJES DE PROGRAMACIÓN INTERRUMPIDA

                // MENSAJE PROGRAMACIÓN INTERRUMPIDA - VALIDACIÓN PROGRAMACIÓN REPETIDA
                if ($componenteRepetido) {

                    Log::info('Programación interrumpida - La ficha ya cuenta en el trimestre con esa componente asignada');
                    $j['success'] = false;
                    $j['message'] = 'Programación interrumpida - La ficha ya cuenta en el trimestre con ese componentencia asignada';
                    $j['code'] = 500;

                }else if($eventValDayStudyS['error']==true){
                    Log::info($eventValDayStudyS['message']);
                    $j['success'] = false;
                    $j['message'] = $eventValDayStudyS['message'];
                    $j['code'] = 500;
                }else if ($validationEventEnvironment["state"] === false && $confirmation1===true && $confirmation2!==true) {
                    if($jornada ==='fin de semana'){
                        Log::info($message);
                        $j['success'] = false;
                        $j['evenDayAvailable'] = $validationEventEnvironment["evenAvailable"];
                        $j['oddDayAvailable'] = $validationEventEnvironment["oddAvailable"];
                        $j['totalEvents'] = $validationEventEnvironment["totalEvents"];
                        $j['message'] = $message;
                        $j['code'] = 500;
                    }else{
                        Log::info($message);
                        $j['success'] = false;
                        $j['evenDayAvailable'] = $validationEventEnvironment["evenAvailable"];
                        $j['oddDayAvailable'] = $validationEventEnvironment["oddAvailable"];
                        $j['totalEvents'] = $validationEventEnvironment["totalEvents"];
                        $j['message'] = $message;
                        $j['code'] = 500;
                    }
                }
                ///////////////////////////////////////////////////////////////////////////////
                //Validacion Ficha
                else if ($validationEventStudyS["status"] === false && $confirmation1===true && $confirmation3!==true) {
                    $messageStudySheet = $validationEventStudyS["message"];
                    if($jornada ==='fin de semana'){
                        Log::info($messageStudySheet);

                        $j['success'] = false;
                        $j['evenDayAvailableST'] = $validationEventEnvironment["evenAvailable"];
                        $j['oddDayAvailableST'] = $validationEventEnvironment["oddAvailable"];
                        $j['totalEventsST'] = $validationEventEnvironment["totalEvents"];
                        $j['message'] = $messageStudySheet;
                        $j['code'] = 500;
                    }else{
                        Log::info($messageStudySheet);
                        $j['success'] = false;
                        $j['evenDayAvailableST'] = $validationEventEnvironment["evenAvailable"];
                        $j['oddDayAvailableST'] = $validationEventEnvironment["oddAvailable"];
                        $j['totalEventsST'] = $validationEventEnvironment["totalEvents"];
                        $j['message'] = $messageStudySheet;
                        $j['code'] = 500;
                    }
                }


                else {

                    /////////////////////////////////////////////////////////////////////////
                    // CICLO PARA PROGRAMAR

                    for ($i = 0; $i < sizeof($fechasAProgramar); $i++) {
                        if($countMaxHours>$componentHours){
                            break;
                        }
                        $fechaInicio = Carbon::createFromFormat('Y-m-d', $fechasAProgramar[$i]);
                        $fechaFinal = Carbon::createFromFormat('Y-m-d', $fechasAProgramar[$i]);

                        $fechaDia = Carbon::parse($fechasAProgramar[$i])->day; // Examinar si trimestre inicia en par o impar

                        // Convertir las fechas a formato DateTime
                        list($hI, $minI) = explode(':', $horaInicio);
                        $fechaInicio->setHour($hI);
                        $fechaInicio->setMinute($minI);
                        $fechaInicio->setSeconds(0);

                        list($hF, $minF) = explode(':', $horaFinal);
                        $fechaFinal->setHour($hF);
                        $fechaFinal->setMinute($minF);
                        $fechaFinal->setSeconds(0);

                        $diferenciaMin = $fechaInicio->diffInMinutes($fechaFinal);
                        $totalHours = ceil($diferenciaMin / 60);

                        $busyEnvironment = $validationEvent->validateEventByConditionEnvironment($ambiente,$fechaInicio,$fechaFinal);
                        $busyStudySheet = $validationEventStudySheet->validateEventByConditionStudyS($ficha,$fechaInicio,$fechaFinal);
                        $busyTeacher = $validationEventTeacher->validateEventByConditionTeacher($instructorID,$fechaInicio,$fechaFinal);

                        if (

                            ($jornada === 'mañana' && $fechaInicio->isWeekday() && $fechaFinal->isWeekday()) ||
                            ($jornada === 'tarde' && $fechaInicio->isWeekday() && $fechaFinal->isWeekday()) ||
                            ($jornada === 'noche' && $fechaInicio->isWeekday() && $fechaFinal->isWeekday()) ||
                            ($jornada === 'fin de semana' && $fechaInicio->isWeekend() && $fechaFinal->isWeekend())

                            )

                        {

                            $ambienteProgramado = DB::table('events')
                            ->where('environment_id', $ambiente)
                            ->where(function ($query) use ($fechaInicio, $fechaFinal) {
                                $query->whereBetween('start', [$fechaInicio, $fechaFinal])
                                    ->orWhereBetween('end', [$fechaInicio, $fechaFinal]);
                                $query->where('start', '<', $fechaFinal);
                            })->exists();

                            if ($ambienteProgramado && $control == false) {
                                Log::info('ambiente programado');
                                $j['success'] = false;
                                $j['message'] = 'Progrmamación Interrumpida - El ambiente ya está programado en el mismo bloque';
                                $j['code'] = 500;
                                $programacionInversa = true;
                            }

                            else {

                                // PROGRAMAR DIA IMPAR
                                if ($programacionInversa == false && $i % 2 !== 0) {
                                    // EVENTOS POR CONDICIONES PARA RECORRER
                                    $validationEventStudySHM = $validationEventStudySheet->evaluateMaximunDailyHours($dataStudySheetH);
                                    $eventsST = DB::table('events as ev')
                                        ->where('study_sheet_id','=',$ficha)
                                        ->where('start','=',$fechaInicio->toDateTimeString())
                                        ->where('end','=',$fechaFinal->toDateTimeString())
                                        ->exists();
                                    if ($busyEnvironment==false) {
                                        if (!$eventsST) {
                                            if($busyStudySheet==false){
                                                if($validationEventStudySHM['state']==true){
                                                    Log::info('Programación interrumpida - Ficha no disponile por exceder las horas maximas en el dia.');
                                                    $j['success'] = false;
                                                    $j['message'] =$validationEventStudySHM['message'];
                                                    $j['code'] = 500;
                                                    break;
                                                }
                                                    $countMaxHours += $totalHoursBlock;
                                                    if ($countMaxHours > $componentHours) break;
                                                    Log::info('se programa dia impar transversal');
                                                    Log::info($fechaDia);
                                                    crearEvento($ambiente, $componente, $ficha, $fechaInicio, $fechaFinal, $totalHours, $instructorID);
                                                    $eventoCreate = true;
                                                    $control = true;
                                                }
                                            }
                                    }
                                }

                                // PROGRAMAR DIA PAR
                                elseif (!$eventoCreate && $programacionInversa && $i % 2 === 0) {
                                    // EVENTOS POR CONDICIONES PARA RECORRER
                                    $validationEventStudySHM = $validationEventStudySheet->evaluateMaximunDailyHours($dataStudySheetH);
                                    $eventsST = DB::table('events as ev')
                                        ->where('study_sheet_id','=',$ficha)
                                        ->where('start','=',$fechaInicio->toDateTimeString())
                                        ->where('end','=',$fechaFinal->toDateTimeString())
                                        ->exists();
                                    if ($busyEnvironment==false) {
                                        if (!$eventsST) {
                                            if($busyStudySheet ==false){
                                                if($validationEventStudySHM['state']==true){
                                                    Log::info('Programación interrumpida - Ficha no disponile por exceder las horas maximas en el dia.');
                                                    $j['success'] = false;
                                                    $j['message'] =$validationEventStudySHM['message'];
                                                    $j['code'] = 500;
                                                    break;
                                                }
                                                $countMaxHours += $totalHoursBlock;
                                                if ($countMaxHours > $componentHours) break;
                                                Log::info('se programa dia par transversal');
                                                Log::info($fechaDia);
                                                crearEvento($ambiente, $componente, $ficha, $fechaInicio, $fechaFinal, $totalHours, $instructorID);
                                                $programacionInversa = true;
                                                $control = true;
                                            }
                                        }
                                    }
                                }

                                // MENSAJE DE PROGRAMACIÓN EXITOSA
                                $j['success'] = true;
                                $j['url'] = $url;
                                $j['message'] = 'Sesiones generadas    correctamente';
                                $j['code'] = 200;
                                $j['data'] = $instructores;

                            }

                        }

                    }

                }

            }

        }

        catch (\Throwable $th)

        {

            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;

        }

        return response()->json($j);

    }


    public function storeTeacher(Request $request){
        $j = [];

        try

        {

            $url = route('horarios.index');
            // $condicionCumplida = true;
            // $horasMes = DB::table('events')
            //     ->selectRaw('EXTRACT(YEAR FROM start) AS year, EXTRACT(MONTH FROM start) AS month, SUM(total_hours) AS total_hours')
            //     ->where('component_id', $request->input('componente'))
            //     ->where('study_sheet_id', $request->input('ficha'))
            //     ->groupBy('year', 'month')
            //     ->pluck('total_hours')
            //     ->toArray();

            // $horas = Instructor::select('total_hours')->where('id', $request->input('instructor'))->first();
            // $horasInstructor = $horas->total_hours;


            // // dd($horasInstructor);

            // // Validación - No superar horas de trabajo mensuales
            // for ($i = 0; $i < sizeof($horasMes); $i++)

            // {

            //     if ($horasInstructor < 70)

            //     {
            //         $horasInstructor = 70;
            //     }

            //     if ($horasInstructor < $horasMes[$i])

            //     {
            //         $condicionCumplida = true;
            //         break;
            //     }

            // }

            // // dd($horasMes, $horasInstructor);

            // // Validacion - Validación Instructor no cruce su horario dentro de un bloque de tiempo

            // if (!$condicionCumplida)

            // {

            //     $j['success'] = false;
            //     $j['message'] = 'El instructor excedió sus horas mensuales';
            //     $j['code'] = 500;

            // }

            // else

            // {
                $teacherId = $request->input('instructor');

                if ($teacherId !== "null") {
                    $evento = DB::table('events')
                        ->where('study_sheet_id', $request->input('ficha'))
                        ->where('component_id', $request->input('componente'))
                        ->update(['teacher_id' => $teacherId]);
                } else {
                    $evento = DB::table('events')
                    ->where('study_sheet_id', $request->input('ficha'))
                    ->where('component_id', $request->input('componente'))
                    ->update(['teacher_id' => DB::raw('NULL')]);
                }


                $j['success'] = true;
                $j['url'] = $url;
                $j['message'] = 'Instructor asignado correctamente';
                $j['code'] = 200;


        }

        catch (\Throwable $th)

        {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function show(){
        $j = [];

        try {
            // Obtener los eventos de la base de datos
            $eventos = DB::table('events')->select(
                'study_sheets.number as number',
                'study_sheets.num as num',
                'environments.name as environment',
                'components.name as component',
                'events.study_sheet_state',
                'events.environment_state',
                'events.start',
                'events.end',
                'components_type.name as type',
                'teachers.id as id_teacher',
                'people.name as teacher_name',
                'people.lastname as teacher_lastname',
                DB::raw("CONCAT(study_sheets.number, '-', study_sheets.num, ' ', components_type.name) as title")
            )
            ->join('study_sheets', 'study_sheets.id', '=', 'events.study_sheet_id')
            ->join('environments', 'environments.id', '=', 'events.environment_id')
            ->join('components', 'components.id', '=', 'events.component_id')
            ->join('components_type', 'components_type.id', '=', 'components.component_type_id')
            ->leftJoin('teachers', 'teachers.id', '=', 'events.teacher_id')
            ->leftJoin('users','users.id', '=', 'teachers.user_id')
            ->leftJoin('people','people.user_id', '=', 'users.id')
            ->get();

            // Obtener los días festivos
            $festivos = Holiday::all();

            // Manipular los datos de los festivos
            foreach ($festivos as $festivo) {
                $festivo->start = $festivo->date;
                unset($festivo->date);
                $festivo->overlap = false;
                $festivo->display = 'background';
                $festivo->color = '#FFABAB';
            }

            $j['state'] = true;
            $j['message'] = 'Consulta exitosa';
            $j['events'] = $eventos;
            $j['holidays'] = $festivos;
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['state'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }


    public function blockHours($startBlock, $endBlock) {
        // Crear objetos DateTime para startBlock y endBlock
        $startBlock1 = new DateTime($startBlock);
        $endBlock1 = new DateTime($endBlock);

        // Sumar un minuto a endBlock1
        $endBlock1->add(new DateInterval("PT1M")); // PT1M representa  1 minuto

        // Calcular la diferencia entre startBlock1 y endBlock1
        $difference = $startBlock1->diff($endBlock1);
        $difference = $difference->format('%H');
        $total = (int)$difference;

        return $total;
    }


    // Filtrado de eventos en el cronograma
    public function filterCronograma(Request $request){
        $j= [];
        $eventosTotales= [];
        $numConteo= 0;

        function obtenerEventos($params) {
            $ambiente = $params['ambiente'] ?? null;
            $componente = $params['componente'] ?? null;
            $ficha = $params['ficha'] ?? null;
            $num = $params['num'] ?? null;
            $tipoComponente = $params['tipoComponente'] ?? null;
            $start = $params['start'] ?? null;
            $end = $params['end'] ?? null;
            $instructor = $params['instructor'] ?? null;


            if ($end !== null) {
                $end = Carbon::parse($end)->addDay(); // Suma un día a la fecha final
            }

            // Si las fechas de inicio y fin son nulas, obtener las fechas mínima y máxima de la base de datos
            if ($start === null && $end === null) {
                $earliestDate = DB::table('events')->min('start');
                $latestDate = DB::table('events')->max('end');

                $start = $earliestDate;
                $end = $latestDate;
            }


            $query = DB::table('events')
                    ->select(
                        'study_sheets.number as number',
                        'environments.name as environment',
                        'components.name as component',
                        'events.study_sheet_state',
                        'events.environment_state',
                        'events.start',
                        'events.end',
                        'teachers.id as id_teacher',
                        'people.name as teacher_name',
                        'people.lastname as teacher_lastname',
                        'components_type.name as type'
                    )
                    ->selectRaw("CONCAT(study_sheets.number, '-', study_sheets.num, ' ', components_type.name) as title")
                    ->join('study_sheets', 'study_sheets.id', '=', 'events.study_sheet_id')
                    ->join('environments', 'environments.id', '=', 'events.environment_id')
                    ->join('components', 'components.id', '=', 'events.component_id')
                    ->join('components_type', 'components_type.id', '=', 'components.component_type_id')
                    ->join('teachers', 'teachers.id', '=', 'events.teacher_id')
                    ->leftJoin('users','users.id', '=', 'teachers.user_id')
                    ->leftJoin('people','people.user_id', '=', 'users.id');

                if (!empty($ambiente)) {
                    $query->where('environments.name', $ambiente);
                }

                if (!empty($componente)) {
                    $query->where('components.name', $componente);
                }

                if (!empty($ficha)) {
                    $query->where('study_sheets.number', $ficha);
                }

                if (!empty($num)) {
                    $query->where('study_sheets.num', $num);
                }

                if (!empty($tipoComponente)) {
                    $query->where('components_type.name', $tipoComponente);
                }

                // Filtrar por nombre completo del instructor
                if (!empty($instructor)) {
                    $query->where(DB::raw("CONCAT(people.name, ' ', people.lastname)"), $instructor);
                }

                $query->whereBetween('events.start', [$start, $end]);
                $query->whereBetween('events.end', [$start, $end]);


                $events = $query->get();

                return $events;
        }


        try{
            // Asegurarse de que cada uno de los datos sea un array
            $ambientes = is_array($request->ambienteSelect) ? $request->ambienteSelect : [$request->ambienteSelect];
            $componentes = is_array($request->componentesSelect) ? $request->componentesSelect : [$request->componentesSelect];
            $fichas = is_array($request->fichaSelect) ? $request->fichaSelect : [$request->fichaSelect];
            $tipoComponentes = is_array($request->tipoComponentesSelect) ? $request->tipoComponentesSelect : [$request->tipoComponentesSelect];
            $instructores = is_array($request->instructorSelect) ? $request->instructorSelect : [$request ->instructorSelect];

            // Ahora puedes usar foreach sin preocuparte por si es un array o un solo dato
            foreach($ambientes as $ambiente){

                foreach($componentes as $componente){

                    foreach($fichas as $ficha){

                        $numeroDespuesGuion=null;

                        if (strpos($ficha, '-') !== false) {

                            list($numeroFicha, $numeroDespuesGuion) = explode('-', $ficha);

                            $ficha= $numeroFicha;

                            $numeroDespuesGuion = intval($numeroDespuesGuion);
                        }

                        foreach($tipoComponentes as $tipoComponente){

                            foreach($instructores as $instructor){
                                // dd($numeroDespuesGuion);

                                if($request->start==null && $request->end==null){
                                    $eventos = obtenerEventos(['componente' => $componente, 'ambiente'=> $ambiente, 'ficha'=> $ficha, 'num'=>$numeroDespuesGuion, 'tipoComponente'=> $tipoComponente,'instructor'=>$instructor]);
                                } else {
                                    $eventos = obtenerEventos(['componente' => $componente, 'ambiente'=> $ambiente, 'ficha'=> $ficha, 'num'=>$numeroDespuesGuion , 'tipoComponente'=> $tipoComponente,'instructor'=>$instructor, 'start' => $request->start, 'end' => $request->end]);
                                }

                                $eventosTotales = array_merge($eventosTotales, $eventos->toArray());

                            }
                        }
                    }
                }
            }


            // Obtener los días festivos
            $festivos = Holiday::all();

            // Manipular los datos de los festivos
            foreach ($festivos as $festivo) {
                $festivo->start = $festivo->date;
                unset($festivo->date);
                $festivo->overlap = false;
                $festivo->display = 'background';
                $festivo->color = '#FFABAB';
            }
            // dd($eventosTotales);
            $j['state'] = true;
            $j['message'] = 'Consulta exitosa';
            $j['events'] = $eventosTotales;
            $j['holidays'] = $festivos;
            $j['code'] = 200;

        } catch (\Throwable $th){
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);

    }



}
