<?php
namespace App\Services\horario;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventValidationStudySheetService{

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Validacion ficha ya
    public function validateEventByConditionStudyS($studySheetId, $startDate, $endDate)
    {
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Consulta de si existe la ficha en algun evento
        $eventsST = DB::table('events')
        ->where('study_sheet_id',$studySheetId)
        ->where(function($query) use ($startDate,$endDate){
            $query->whereBetween('start',[$startDate,$endDate])
            ->orWhereBetween('start',[$startDate,$endDate]);
            $query->where('start','<',$endDate);
        })->exists();

        // dd($eventsST);
        // Return true if the event exists, false otherwise
        return $eventsST;
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Validacion Para Identificar la disponivilidad de una Ficha
    public function validationDateCompletStudyS($fechas,$totalHoursComponent,$totalHoursBlock,$studySheetId,$horaInicio,$horaFinal,$jornada){

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Declaraciones

        //Variables
        $fechasOmitir = DB::table('holidays')->pluck('date')->toArray();
        $fechaCero = '1999-12-30';
        $lengToShedule = 0;

        //Fechas
        $fechaInicioTimestre = '2024-01-22';
        $diaInicioTrimestre = 0;

        //contadores
        $dayACount = 0;
        $dayBCount = 0;

        //Arreglos
        $dayA = [];
        $dayB = [];

        //resultados de eventos a y b
        $dayAvailableA = 0;
        $dayAvailableB = 0;

        //Mapas Ordenados
        $data = [
            'totalEvents'=>0,
            'dayAvailableA'=>0,
            'dayAvailableB'=>0,
            'dayA'=>[],
            'dayB'=>[],
            'state'=>false,
        ];

        //Fechas a programar
        $dateToSheduleStudyS = [];

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Asignacion de cantidad total de eventos a programar
        $lengToShedule = (int)($totalHoursComponent/$totalHoursBlock);
        $data['totalEvents'] = $lengToShedule;

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Asignacion de de datos sobre fecha inicio del trimestre
        $fechaInicioTimestre = Carbon::createFromFormat('Y-m-d',$fechas[0]);
        //Determinar el dia de inicio del trimestre
        $diaInicioTrimestre = Carbon::parse($fechaInicioTimestre)->day;
        //Eliminar los dias festivos del trimestre
        $dateToSheduleStudyS = array_diff($fechas,$fechasOmitir);
        //Asignar valor determidado al Array de fechas del trimestre
        if ($diaInicioTrimestre % 2 == 0){
            array_unshift($dateToSheduleStudyS);
        }else{
            array_unshift($dateToSheduleStudyS,$fechaCero);
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // ¡Importante!
        // El siguiente Ciclo Servira Para Determinar Dias Si y Dias No
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        for($i=0;$i <sizeof($dateToSheduleStudyS); $i++){
            /////////////////////////////////////////////////////////
            //Asignar valores a fechas inicio y final
            $fechaInicio = Carbon::createFromFormat('Y-m-d',$dateToSheduleStudyS[$i]);
            $fechaFinal = Carbon::createFromFormat('Y-m-d',$dateToSheduleStudyS[$i]);

            ////////////////////////////////////////////////////////
            //Convertir fechas en formato DateTime
            //Fecha Inicio
            list($hI,$minI) = explode(':',$horaInicio);
            $fechaInicio->setHours($hI);
            $fechaInicio->setMinutes($minI);
            $fechaInicio->setSeconds(0);

            //Fecha Final
            list($hF,$minF) = explode(':',$horaFinal);
            $fechaFinal->setHours($hF);
            $fechaFinal->setMinutes($minF);
            $fechaFinal->setSeconds(0);
            /////////////////////////////////////////////////////////
            //Validar si ya existe un evento programado para la ficha mediante (fecha y hora)
            $evenToCreateDayStudyS = DB::table('events')
            ->where('study_sheet_id',$studySheetId)
            ->where(function($query) use ($fechaInicio,$fechaFinal){
                $query->whereBetween('start',[$fechaInicio,$fechaFinal])
                ->orWhereBetween('start',[$fechaInicio,$fechaFinal]);
                $query->where('start','<',$fechaFinal);
            })->exists();
                // dd($evenToCreateDayStudyS,$fechaInicio,$fechaFinal,$studySheetId);

            /////////////////////////////////////////////////////////
            //Si ya existe el evento continuar a la siguiente iteracion
            if($evenToCreateDayStudyS)continue;
            //Si es la fecha cero añadida continuar a la siguiente iteracion
            if($dateToSheduleStudyS[$i]==$fechaCero)continue;

            /////////////////////////////////////////////////////////
            // Realizar el parseo del dia en el que va la iteracion
            $dayCarbon = Carbon::parse($dateToSheduleStudyS[$i]);
            $dayFormatText = $dayCarbon->format('N');

            /////////////////////////////////////////////////////////
            //Validacion para identificar fines de semana
            if($jornada == 'fin de semana'){
                //Validar si el dia es sabado o domingo tanto para eventos A o B
                //Evento A
                if($dayFormatText == 6 || $dayFormatText == 7 && $i % 2 == 0){
                    $dayA[] = $dateToSheduleStudyS[$i];
                }
                // Evento B
                else if($dayFormatText == 6 || $dayFormatText == 7 && $i % 2 != 0){
                    $dayB[] = $dateToSheduleStudyS[$i];
                }
            }
            //Validar entre semana
            else{
                /////////////////////////////////////////////////////////
                //Quitar los sabados o domingos
                if($dayFormatText == 6 || $dayFormatText == 7)continue;

                ////////////////////////////////////////////////////////
                //Validar si el dia es para eventos A o B
                //Evento A
                if($i % 2 == 0){
                    $dayA[] = $dateToSheduleStudyS[$i];
                }
                //Evento B
                else{
                    $dayB[] = $dateToSheduleStudyS[$i];
                }

            }
        }
        // dd($dayA);


        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // ¡Importante!
        // Las siguientes variables almacenaran los datos para verificar la disponibilidad de la ficha
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////////////////////////////////
        //Cantidad de eventos A o B
        //Eventos A
        $dayACount = count($dayA);
        //Eventos B
        $dayBCount = count($dayB);
        /////////////////////////////////////////////////////////
        //Cantidad de eventos A o B Disponibles
        //Eventos A
        $dayAvailableA = $dayACount - $lengToShedule;
        //Eventos B
        $dayAvailableB = $dayBCount - $lengToShedule;

        /////////////////////////////////////////////////////////
        //Datos de conteo por defecto del mensaje
        //EventosA
        $data['dayAvailableA']=max(0,$dayAvailableA);
        //EventosB
        $data['dayAvailableB']=max(0,$dayAvailableB);

        /////////////////////////////////////////////////////////
        //Validar la disponibilidad de eventos A o B
        if($dayACount>0 || $dayBCount>0){
            if($dayACount >= $lengToShedule || $dayBCount >= $lengToShedule){
                $data['dayA'] = $dayA;
                $data['dayB'] = $dayB;
                $data['state'] = true;
            }else{

                if($dayACount < $lengToShedule){
                    $data['dayAvailableA'] = $dayACount;
                    $data['dayA'] = $dayA;
                }

                if($dayBCount < $lengToShedule){
                    $data['dayAvailableB'] = $dayBCount;
                    $data['dayB'] = $dayB;
                }

            }

        }

        return $data;

    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Validación para regresar el mensaje de respuesta de validacion
    public function messgeValidatioStudyS ($dataRequest){
        ////////////////////////////////////////////////////
        // Declaracion de mensaje de respuesta
        $message = [
            "message" =>"",
            "status" =>true,
            "dataError"=>"",
            "totalEventsST"=>0,
            "evenDayAvailableST"=>0,
            "oddDayAvailableST"=>0
        ];
        //////////////////////////////////////////////////
        //Declaracion de variables
        $fechas = '';
        $totalHoursComponent = 0;
        $totalHoursBlock = 0;
        $studySheetId = 0;
        $horaInicio = '';
        $horaFinal = '';
        $jornada = '';

        //Asignacion de valores a las variables para enviar los argumentos necesarios para la validacion
        $fechas = $dataRequest['fechas'];
        $totalHoursComponent = $dataRequest['componentHours'];
        $totalHoursBlock = $dataRequest['totalHoursBlock'];
        $studySheetId = $dataRequest['ficha'];
        $horaInicio = $dataRequest['horaInicio'];
        $horaFinal = $dataRequest['horaFinal'];
        $jornada = $dataRequest['jornada'];


        //Instancia de la funcion de validacion de la ficha
        $validationStudySheet = $this -> validationDateCompletStudyS($fechas,$totalHoursComponent,$totalHoursBlock,$studySheetId,$horaInicio,$horaFinal,$jornada);
        // dd($validationStudySheet);
        //////////////////////////////////////////////////////////////
        //Validaciones para retornar el mensaje
        if($validationStudySheet){
            $message['totalEventsST']=$validationStudySheet['totalEvents'];
            $message['evenDayAvailableST']=$validationStudySheet['dayAvailableA'];
            $message['oddDayAvailableST']=$validationStudySheet['dayAvailableB'];

            $message['dataError']=$validationStudySheet;
                if($jornada ==='fin de semana'){
                    if($validationStudySheet['state']){
                        $message['status'] = true;
                    }
                    else if ($validationStudySheet['dayAvailableA'] > 0 && $validationStudySheet['dayAvailableB'] > 0) {
                        $message['message'] = "¡Atención! La programación no puede continuar debido a la disponibilidad de sesiones de la ficha y bloque de tiempos seleccionado. Actualmente, hay {$validationStudySheet['dayAvailableA']} sesiones para un evento A y {$validationStudySheet['dayAvailableB']} sesiones para un evento B en fines de semana. Sin embargo, la ficha requiere de {$validationStudySheet['totalEvents']} sesiones en el bloque de tiempo seleccionado.
                        Considere cambiar el bloque horario o programar otra ficha para continuar.";
                        $message['status'] = false;
                    }else if($validationStudySheet['dayAvailableA'] <= 0 && $validationStudySheet['dayAvailableB'] > 0){
                        $message['message'] = "¡Atención! La programación no puede continuar debido a la disponibilidad de sesiones de la ficha y bloque de tiempos seleccionado. Actualmente, hay {$validationStudySheet['dayAvailableB']} sesiones para un evento B en fines de semana. Sin embargo, la ficha requiere de {$validationStudySheet['totalEvents']} sesiones en el bloque de tiempo seleccionado.
                        Considere cambiar el bloque horario o programar otra ficha para continuar.";
                        $message['status'] = false;

                    }else if($validationStudySheet['dayAvailableA'] > 0 && $validationStudySheet['dayAvailableB'] <= 0){
                        $message['message'] = "¡Atención! La programación no puede continuar debido a la disponibilidad de sesiones de la ficha y bloque de tiempos seleccionado. Actualmente, hay {$validationStudySheet['dayAvailableB']} sesiones para un evento A en fines de semana. Sin embargo, la ficha requiere de {$validationStudySheet['totalEvents']} sesiones en el bloque de tiempo seleccionado.
                        Considere cambiar el bloque horario o programar otra ficha para continuar.";
                        $message['status'] = false;

                    }else if($validationStudySheet['dayAvailableA'] <= 0 && $validationStudySheet['dayAvailableB'] <= 0){
                        $message['message'] = "¡Atención! La programación no puede continuar debido a la disponibilidad de sesiones de la ficha y bloque de tiempos seleccionado. Actualmente, el ambiente no cuenta con disponibilidad para generar la cantidad de sesiones necesarias para el evento en fines de semana. La ficha requiere de {$validationStudySheet['totalEvents']} sesiones en el bloque de tiempo seleccionado.
                        Considere cambiar el bloque horario o programar otra ficha para continuar.";
                        $message['status'] = false;

                    }else{
                        $message['message'] = "ATENCIÓN! OCURRIO UN ERROR EN LA VALIDACIÓN DE FICHA.";
                        $message['status'] = false;

                    }
                }else{
                    if($validationStudySheet['state']){
                        $message['status'] = true;
                    }
                    else if ($validationStudySheet['dayAvailableA'] > 0 && $validationStudySheet['dayAvailableB'] > 0) {
                        $message['message'] = "¡Atención! La programación no puede continuar debido a la disponibilidad de sesiones de la ficha y bloque de tiempos seleccionado. Actualmente, hay {$validationStudySheet['dayAvailableA']} sesiones para un evento A y {$validationStudySheet['dayAvailableB']} sesiones para un evento B. Sin embargo, la ficha requiere de {$validationStudySheet['totalEvents']} sesiones en el bloque de tiempo seleccionado.
                        Considere cambiar el bloque horario o programar otra ficha para continuar.";
                        $message['status'] = false;

                    }else if($validationStudySheet['dayAvailableA'] <= 0 && $validationStudySheet['dayAvailableB'] > 0){
                        $message['message'] = "¡Atención! La programación no puede continuar debido a la disponibilidad de sesiones de la ficha y bloque de tiempos seleccionado. Actualmente, hay {$validationStudySheet['dayAvailableB']} sesiones para un evento B. Sin embargo, la ficha requiere de {$validationStudySheet['totalEvents']} sesiones en el bloque de tiempo seleccionado.
                        Considere cambiar el bloque horario o programar otra ficha para continuar.";
                        $message['status'] = false;

                    }else if($validationStudySheet['dayAvailableA'] > 0 && $validationStudySheet['dayAvailableB'] <= 0){
                        $message['message'] = "¡Atención! La programación no puede continuar debido a la disponibilidad de sesiones de la ficha y bloque de tiempos seleccionado. Actualmente, hay {$validationStudySheet['dayAvailableA']} sesiones para un evento A. Sin embargo, la ficha requiere de {$validationStudySheet['totalEvents']} sesiones en el bloque de tiempo seleccionado.
                        Considere cambiar el bloque horario o programar otra ficha para continuar.";
                        $message['status'] = false;

                    }else if($validationStudySheet['dayAvailableA'] <= 0 && $validationStudySheet['dayAvailableB'] <= 0){
                        $message['message'] = "¡Atención! La programación no puede continuar debido a la disponibilidad de sesiones de la ficha y bloque de tiempos seleccionado. Actualmente, el ambiente no cuenta con disponibilidad para generar la cantidad de sesiones necesarias para el evento. La ficha requiere de {$validationStudySheet['totalEvents']} sesiones en el bloque de tiempo seleccionado.
                        Considere cambiar el bloque horario o programar otra ficha para continuar.";
                        $message['status'] = false;

                    }else{
                        $message['message'] = "ATENCIÓN! OCURRIO UN ERROR EN LA VALIDACIÓN DE FICHA.";
                        $message['status'] = false;

                    }

                }

        }else{
            $message['message'] = '¡¡OCURRIO UN ERROR AL MOMENTO DE EJECUTARSE LA VALIDACIÓN!!';
            $message['status'] = false;
        }
        // dd($message);
        return $message;

    }



    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Validacion para el maximo de horas diarias de un aprendiz
    public function evaluateMaximunDailyHours($dataRequest){

        //Variables
        $fechasOmitir = DB::table('holidays')->pluck('date')->toArray();
        $fechaCero = '1999-12-30';
        $lengToShedule = 0;
        $hourDifference = 0;

        //Arreglos
        $dayA = [];
        $dayB = [];
        $dayNotAvailable  =[];

        //Variable que contendra los datos de retorno
        $dataResponse = [
            'message' =>'',
            'state' => false,
            'dayNotAvailable'=>[],
        ];

        //Asignacion de valores a las variables para enviar los argumentos necesarios para la validacion
        $dates = $dataRequest['fechas'];
        $totalHoursComponent = $dataRequest['componentHours'];
        $totalHoursBlock = $dataRequest['totalHoursBlock'];
        $studySheetId = $dataRequest['ficha'];
        $hourStart = $dataRequest['horaInicio'];
        $hourEnd = $dataRequest['horaFinal'];
        $day = $dataRequest['jornada'];
        $typeEvent = $dataRequest['typeEvent'];

        ///////////////////////////////////////////////////////////////////////////////////////

        $hourStart1 = Carbon::parse($hourStart);
        $hourEnd1 = Carbon::parse($hourEnd)->addMinute();

        // Calcular la diferencia en horas
        $hourDifference = $hourStart1->diffInHours($hourEnd1);

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Asignacion de cantidad total de eventos a programar
        $lengToShedule = (int)($totalHoursComponent/$totalHoursBlock);

        ///////////////////////////////////////////////////////////////////////////////
        //Sacar dias que se van a programar
         //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Asignacion de de datos sobre fecha inicio del trimestre
        $fechaInicioTimestre = Carbon::createFromFormat('Y-m-d',$dates[0]);
        //Determinar el dia de inicio del trimestre
        $diaInicioTrimestre = Carbon::parse($fechaInicioTimestre)->day;
        //Eliminar los dias festivos del trimestre
        $dateToSheduleStudyS = array_diff($dates,$fechasOmitir);
        //Asignar valor determidado al Array de fechas del trimestre
        if ($diaInicioTrimestre % 2 == 0){
            array_unshift($dateToSheduleStudyS);
        }else{
            array_unshift($dateToSheduleStudyS,$fechaCero);
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // ¡Importante!
        // El siguiente Ciclo Servira Para Determinar Dias Si y Dias No
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        for($i=0;$i <sizeof($dateToSheduleStudyS); $i++){
            /////////////////////////////////////////////////////////
            //Asignar valores a fechas inicio y final
            $fechaInicio = Carbon::createFromFormat('Y-m-d',$dateToSheduleStudyS[$i]);
            $fechaFinal = Carbon::createFromFormat('Y-m-d',$dateToSheduleStudyS[$i]);

            ////////////////////////////////////////////////////////
            //Convertir fechas en formato DateTime
            //Fecha Inicio
            list($hI,$minI) = explode(':',$hourStart);
            $fechaInicio->setHours($hI);
            $fechaInicio->setMinutes($minI);
            $fechaInicio->setSeconds(0);

            //Fecha Final
            list($hF,$minF) = explode(':',$hourEnd);
            $fechaFinal->setHours($hF);
            $fechaFinal->setMinutes($minF);
            $fechaFinal->setSeconds(0);
            /////////////////////////////////////////////////////////
            //Validar si ya existe un evento programado para la ficha mediante (fecha y hora)
            $evenToCreateDayStudyS = DB::table('events')
            ->where('study_sheet_id',$studySheetId)
            ->where(function($query) use ($fechaInicio,$fechaFinal){
                $query->whereBetween('start',[$fechaInicio,$fechaFinal])
                ->orWhereBetween('start',[$fechaInicio,$fechaFinal]);
                $query->where('start','<',$fechaFinal);
            })->exists();
                // dd($evenToCreateDayStudyS,$fechaInicio,$fechaFinal,$studySheetId);

            /////////////////////////////////////////////////////////
            //Si ya existe el evento continuar a la siguiente iteracion
            if($evenToCreateDayStudyS)continue;
            //Si es la fecha cero añadida continuar a la siguiente iteracion
            if($dateToSheduleStudyS[$i]==$fechaCero)continue;

            /////////////////////////////////////////////////////////
            // Realizar el parseo del dia en el que va la iteracion
            $dayCarbon = Carbon::parse($dateToSheduleStudyS[$i]);
            $dayFormatText = $dayCarbon->format('N');

            /////////////////////////////////////////////////////////
            //Validacion para identificar fines de semana
            if($day == 'fin de semana'){
                //Validar si el dia es sabado o domingo tanto para eventos A o B
                //Evento A
                if($dayFormatText == 6 || $dayFormatText == 7 && $i % 2 == 0){
                    $dayA[] = $dateToSheduleStudyS[$i];
                }
                // Evento B
                else if($dayFormatText == 6 || $dayFormatText == 7 && $i % 2 != 0){
                    $dayB[] = $dateToSheduleStudyS[$i];
                }
            }
            //Validar entre semana
            else{
                /////////////////////////////////////////////////////////
                //Quitar los sabados o domingos
                if($dayFormatText == 6 || $dayFormatText == 7)continue;

                ////////////////////////////////////////////////////////
                //Validar si el dia es para eventos A o B
                //Evento A
                if($i % 2 == 0){
                    $dayA[] = $dateToSheduleStudyS[$i];
                }
                //Evento B
                else{
                    $dayB[] = $dateToSheduleStudyS[$i];
                }

            }
        }

        ////////////////////////////////////////////////////////////////
        //Datos para tomar diferencias por jornadas
        $validateStudyS = DB::table('study_sheets as s')
        ->join('days as d','d.id','=','s.day_id')
        ->rightJoin('blocks as b','b.day_id','=','d.id')
        ->where('s.id',$studySheetId)
        ->select('d.id as idStudyS','d.name','time_start','time_end')
        ->get();

        // Find the smallest time_start
        $minTimeStart = $validateStudyS->sortBy('time_start')->first()->time_start;

        // Find the largest time_end
        $maxTimeEnd = $validateStudyS->sortByDesc('time_end')->first()->time_end;

        $minTimeStart = Carbon::parse($minTimeStart);
        $maxTimeEnd = Carbon::parse($maxTimeEnd)->addMinute();

        $diffHoursDays = $minTimeStart->diffInHours($maxTimeEnd);

        // Output or use the results as needed


        ///////////////////////////////////////////////////////////////////////////////
        //Validacion de acuerdo al tipo de evento
        if($typeEvent == "A" && count($dayA)>=$lengToShedule){
            foreach($dayA as $dateA){
                $fechaInicio1 = Carbon::createFromFormat('Y-m-d', $dateA)->startOfDay();
                $fechaFinal1 = Carbon::createFromFormat('Y-m-d', $dateA)->endOfDay();
                $eventToCreateDay = DB::table('events')
                ->where('study_sheet_id', $studySheetId)
                ->where(function ($query) use ($fechaInicio1, $fechaFinal1) {
                    $query->whereBetween('start', [$fechaInicio1, $fechaFinal1])
                        ->orWhereBetween('end', [$fechaInicio1, $fechaFinal1]);
                })
                ->select('start','end')
                ->get();
                $differenceHoursES = 0;
                foreach($eventToCreateDay as $datesConsult){
                    $datesConsult->end = Carbon::parse($datesConsult->end)->addMinute();
                    $datesConsult->start = Carbon::parse($datesConsult->start);
                    $differenceHours = $datesConsult->end->diffInHours($datesConsult->start);
                    $differenceHoursES += $differenceHours;
                }

                $differenceHoursES +=$hourDifference;
                if ($differenceHoursES > $diffHoursDays){
                    $dayNotAvailable[]=$dateA;
                }

            }


        }else if($typeEvent == "B"){
            foreach($dayB as $dateB){
                $fechaInicio2 = Carbon::createFromFormat('Y-m-d', $dateB)->startOfDay();
                $fechaFinal2 = Carbon::createFromFormat('Y-m-d', $dateB)->endOfDay();
                $eventToCreateDay = DB::table('events')
                ->where('study_sheet_id', $studySheetId)
                ->where(function ($query) use ($fechaInicio2, $fechaFinal2) {
                    $query->whereBetween('start', [$fechaInicio2, $fechaFinal2])
                        ->orWhereBetween('end', [$fechaInicio2, $fechaFinal2]);
                })
                ->select('start','end')
                ->get();
                $differenceHoursES = 0;
                foreach($eventToCreateDay as $datesConsult){
                    $datesConsult->end = Carbon::parse($datesConsult->end)->addMinute();
                    $datesConsult->start = Carbon::parse($datesConsult->start);
                    $differenceHours = $datesConsult->end->diffInHours($datesConsult->start);
                    $differenceHoursES += $differenceHours;
                }
                $differenceHoursES +=$hourDifference;
                if ($differenceHoursES > $diffHoursDays){
                    $dayNotAvailable[]=$dateB;
                }
            }
        }else{
            $response['error']=true;
        }

        $dataResponse['dayNotAvailable']=$dayNotAvailable;
        if(count($dayNotAvailable)>0){
            $dataResponse['state']=true;
            $dataResponse['message']='Las horas diarias de la ficha exceden las horas limites de la jornada de esta ficha las cuales son '.$diffHoursDays.' y se esta programando en eventos de tipo '.$typeEvent;
        }
        return $dataResponse;



    }







}





