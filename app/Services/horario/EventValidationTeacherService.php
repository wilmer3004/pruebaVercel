<?php



namespace App\Services\horario;

use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\DB;



class EventValidationTeacherService

{

    public function validateEventByConditionTeacher($teacher_id, $startDate, $endDate) {
        return DB::table('events as ev')
            ->where('teacher_id', $teacher_id)
            ->where('start', $startDate->toDateTimeString())
            ->where('end', $endDate->toDateTimeString())
            ->exists();
    }


    // Validacion instructor sin diferentes ambientes
    public function validationDateCompletedTeacher($fechas,$totalHoursComponent,$totalHoursBlock,$teacher_id,$horaInicio,$horaFinal,$jornada){
        // Obtener las fechas de festivos y convertirlas a un array
        $fechasOmitir = DB::table('holidays')->pluck('date')->toArray();

        // Elimino los festivos del array de trimestre
        $dateToShedule = array_values(array_diff($fechas, $fechasOmitir));

        // Reasigno los indices del array
        $dateToShedule = array_values($dateToShedule);

        // Calcular el número total de eventos a programar
        $lengEventsToShedule = (int) ($totalHoursComponent / $totalHoursBlock);

        // Preparar un array para almacenar las fechas disponibles
        $dateToSheduleAvailable = [];
        //Dias pares
        $evenDay = [];
        //Dias impares
        $oddDay = [];


        //Determinar dia pares e impares

        for  ($i = 0; $i < sizeof($dateToShedule); $i++) {
            $fechaInicio1 = Carbon::createFromFormat('Y-m-d', $dateToShedule[$i]);
            $fechaFinal1 = Carbon::createFromFormat('Y-m-d', $dateToShedule[$i]);

            // CONVERTIR LAS FECHAS A FORMATO DATETIME
            list($hI, $minI) = explode(':', $horaInicio);
            $fechaInicio1->setHour($hI);
            $fechaInicio1->setMinute($minI);
            $fechaInicio1->setSeconds(0);

            list($hF, $minF) = explode(':', $horaFinal);
            $fechaFinal1->setHour($hF);
            $fechaFinal1->setMinute($minF);
            $fechaFinal1->setSeconds(0);


            // Verificar si existe un evento para la fecha y hora especificadas
            $eventToCreateDay = DB::table('events')
            ->where('teacher_id', $teacher_id)
            ->where(function ($query) use ($fechaInicio1, $fechaFinal1) {
                $query->whereBetween('start', [$fechaInicio1, $fechaFinal1])
                    ->orWhereBetween('end', [$fechaInicio1, $fechaFinal1]);
                $query->where('start', '<', $fechaFinal1);
            })->exists();


            // Si no existe el evento, agregar la fecha al nuevo arreglo
            if ($eventToCreateDay)continue;

            if($dateToShedule[$i]=='1999-12-30')continue;

            $dayCarbon = Carbon::parse($dateToShedule[$i]);
            $dayFormatText = $dayCarbon->format('N'); // 'N' devuelve el día de la semana como un número
            if($jornada === 'fin de semana'){
                // Verificar si el día es sábado o domingo
                if ($dayFormatText ==  6 || $dayFormatText ==  7 && $i % 2 == 0) {
                    $evenDay[] = $dateToShedule[$i];
                } else if($dayFormatText ==  6 || $dayFormatText ==  7 && $i % 2 != 0) {
                    $oddDay[] = $dateToShedule[$i];
                }
            }
            else{
                // Verificar si el día es sábado o domingo
                if ($dayFormatText ==  6 || $dayFormatText ==  7) {
                    continue; // Saltar el día actual si es sábado o domingo
                }

                // Arreglo de días pares
                if ($i %  2 ==  0) {
                    $evenDay[] = $dateToShedule[$i];
                } else {
                    $oddDay[] = $dateToShedule[$i];
                }
            }

        }
        // dd($oddDay,$evenDay);
        // Calcular las fechas disponibles y la disponibilidad
        // cantidad dias pares disponibles
        $evenDayCount = count($evenDay);
        // cantidad dias impares disponibles
        $oddDayCount = count($oddDay);
        //EVENTOS DE DIAS PARES DISPONIBLES
        $evenAvailable = $evenDayCount - $lengEventsToShedule;
        //EVENTOS DE DIAS IMPARES DISPONIBLES
        $oddAvailable = $oddDayCount - $lengEventsToShedule;
        // dd($evenDay,$oddDay);

    // Estado por defecto, asumiendo que el estado es falso y no hay disponibilidad
    // Estado por defecto, asumiendo que el estado es falso y no hay disponibilidad
    $data = [
        "timeDifferenceTotal"=>$totalHoursBlock * $lengEventsToShedule,
        "timeDifference"=>$totalHoursBlock,
        "totalEvents" => $lengEventsToShedule,
        "evenAvailable" => max(0, $evenAvailable),
        "oddAvailable" => max(0, $oddAvailable),
        "oddEvent" => $oddDay,
        "evenEvent" => $evenDay,
        "state" => false,
    ];

    // Verificar si hay disponibilidad para eventos de días pares o impares
    if ($evenDayCount >  0 || $oddDayCount >  0) {
        // Si hay disponibilidad, ajustar el estado y la disponibilidad según sea necesario
        if ($evenDayCount >= $lengEventsToShedule || $oddDayCount >= $lengEventsToShedule) {
            $data['state'] = true;
        } else {
            // Si hay disponibilidad pero no es suficiente para todos los eventos, ajustar la disponibilidad
            if ($evenDayCount < $lengEventsToShedule) {
                $data['evenAvailable'] =$evenDayCount;
            }
            if ($oddDayCount < $lengEventsToShedule) {
                $data['oddAvailable'] = $oddDayCount;
            }
        }
    }


    // Finalmente, retornar el resultado
    return $data;
    }












    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Validacion horas de instructor

        public function validateTeacherMaxHours($fechas,$totalHoursComponent,$totalHoursBlock,$teacher_id,$horaInicio,$horaFinal,$jornada,$monthsLocked){
            $eventsTeacher = $this->validationDateCompletedTeacher($fechas,$totalHoursComponent,$totalHoursBlock,$teacher_id,$horaInicio,$horaFinal,$jornada);

            $totalHours = DB::table('teachers')
            ->where('id', $teacher_id)
            ->value('total_hours'); // Asume que 'total_hours' es el nombre de la columna

            $data = [
                "totalEvents"=> 0,
                "timeDifferenceTeacherTotal"=>0,
                "timeDifferenceEvent"=>0,
                "error"=>true,
                "horasPorMesOcupado"=>[],
                "horasPorTrimestre"=>0,
                "horasTotalesMes"=>$totalHours,
                'horasPorMesDesocupado'=>[],
                "eventACount"=>0,
                "eventBCount"=>0,
                "eventA"=>[],
                "eventB"=>[],
                "meses"=>[],

            ];


            if ($eventsTeacher && $totalHours) {
                $data['timeDifferenceTeacherTotal'] = $eventsTeacher['timeDifferenceTotal'];
                $data['eventACount'] = count($eventsTeacher['evenEvent']);
                $data['eventBCount'] = count( $eventsTeacher['oddEvent']);
                $data['eventA'] = $eventsTeacher['evenEvent'];
                $data['eventB'] = $eventsTeacher['oddEvent'];
                $data['timeDifferenceEvent'] = $eventsTeacher['timeDifference'];
                $data['totalEvents'] = $eventsTeacher['totalEvents'];
                        // Obtener las fechas de festivos y convertirlas a un array
            $fechasOmitir = DB::table('holidays')->pluck('date')->toArray();

            // Elimino los festivos del array de trimestre
            $dateToShedule = array_values(array_diff($fechas, $fechasOmitir));

            // Reasigno los indices del array
            $dateToShedule = array_values($dateToShedule);


            $start = new DateTime($fechas[0]);
            $end = new DateTime(end($fechas));

            // Extract the month and year from the start and end dates
            $startMonth = $start->format('m');
            $startYear = $start->format('Y');
            $endMonth = $end->format('m');
            $endYear = $end->format('Y');

            // Initialize an array to hold the dates
            $datesMonths = [];

            // Loop through each month from the start month to the end month
            for ($month = $startMonth; $month <= $endMonth; $month++) {
                // If the month is less than 10, prepend a 0 to keep the format consistent
                $month = str_pad($month, 2, '0', STR_PAD_LEFT);

                // Create a DateTime object for the first day of the current month
                $firstDayOfMonth = new DateTime("$startYear-$month-01");

                // Create a DateTime object for the last day of the current month
                $lastDayOfMonth = clone $firstDayOfMonth;
                $lastDayOfMonth->modify('last day of this month');

                // Create a DateInterval object for a 1-day interval
                $interval = new DateInterval('P1D');

                // Create a DatePeriod object with the first day of the month, interval, and last day of the month
                $period = new DatePeriod($firstDayOfMonth, $interval, $lastDayOfMonth);

                // Iterate over the period and add each date to the array
                foreach ($period as $date) {
                    $datesMonths[] = $date->format('Y-m-d');
                }
            }



            // Preparar un array para almacenar las fechas disponibles
            $dateToSheduleAvailable = [];
            $dateToSheduleBusy = [];
            //Dias pares
            $evenDay = [];
            //Dias impares
            $oddDay = [];
            $datesMonthsFinally = array_values(array_diff($datesMonths, $fechasOmitir));
            // dd($datesMonthsFinally);
            // Iterar sobre las fechas para verificar si existen eventos
            foreach ($datesMonthsFinally as $date) {
                // Asegúrate de que $date esté en el formato 'Y-m-d'
                $fechaInicio1 = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
                $fechaFinal1 = Carbon::createFromFormat('Y-m-d', $date)->endOfDay();

                // O usa Carbon::parse si $date puede estar en cualquier formato reconocible por Carbon
                // $fechaInicio1 = Carbon::parse($date)->startOfDay();
                // $fechaFinal1 = Carbon::parse($date)->endOfDay();

                $eventToCreateDay = DB::table('events')
                    ->where('teacher_id', $teacher_id)
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


            //sumarle 1 a la hora final
                $horaFinal = Carbon::parse($horaFinal)->addMinute();
                $horaInicio = Carbon::parse($horaInicio);
                $horaFinal1= $horaFinal->format('H');
                $horaInicio1= $horaInicio->format('H');

            $hoursMaxT = 0;
            foreach ($dateToSheduleAvailable as $day) {
                $dayCarbon = Carbon::parse($day[1]);
                // Obtén la hora en formato de 24 horas y suma 1
                $hoursFormatText = $dayCarbon->format('H') + 1;
                $hoursMaxT += $hoursFormatText;
            }
            // dd($horaInicio1, $horaFinal1);

                $data['error']=false;


                $horasPorMesOcupado = [];
                $horasPorTrimestre =0;

                foreach ($dateToSheduleBusy as $evento) {
                    $fechaInicio10 = Carbon::parse($evento[0]);
                    $fechaInicioDiferent1 = Carbon::parse($evento[0])->format('H');
                    $fechaFinal10 = Carbon::parse($evento[1])->addMinute()->format('H');

                    $diferenciaHoraBlock1 = $fechaFinal10 - $fechaInicioDiferent1;


                    // Sumar la diferencia a las horas programadas por mes y trimestre
                    $mes1 = $fechaInicio10->format('F');


                    $horasPorMesOcupado[$mes1] = ($horasPorMesOcupado[$mes1] ?? 0) + $diferenciaHoraBlock1;
                }

                $horasPorMesDesocupado = [];

                foreach ($datesMonthsFinally as $evento) {
                    $fechaInicio = Carbon::parse($evento);
                    $fechaInicioDiferent2 = Carbon::parse($evento)->format('H');
                    $fechaFinal = Carbon::parse($evento)->addMinute()->format('H');

                    $diferenciaHoraBlock2 = $fechaFinal-$fechaInicioDiferent2;

                    // Sumar la diferencia a las horas programadas por mes y trimestre
                    $mes = $fechaInicio->format('F');


                    $horasPorMesDesocupado[$mes] = $totalHours;
                }
                foreach ($horasPorMesDesocupado as $mes => $horas) {
                    if ($horas <= $totalHours) {
                        $data['error'] = false;
                        $data['horasPorMesDesocupado'][$mes]=$totalHours;
                        $data['meses'][]=$mes;

                        // Aquí puedes agregar más lógica según sea necesario
                    } else {
                        $data['error'] = true;
                        // Aquí puedes manejar este caso
                    }

                }

                // Ahora, puedes comparar las horas programadas con las horas disponibles

                    foreach ($horasPorMesOcupado as $mes => $horas) {
                        if ($horas <= $totalHours) {
                            $data['error'] = false;
                            $data['horasPorMesOcupado']+=[$mes=>$horas];
                            $data['horasPorMesDesocupado'][$mes]=$data['horasPorMesDesocupado'][$mes]-$horas;


                            // Aquí puedes agregar más lógica según sea necesario
                        } else {
                            $data['error'] = true;
                            $data['horasPorMesOcupado']+=[$mes=>$horas];
                            $data['horasPorMesDesocupado'][$mes]=$data['horasPorMesDesocupado'][$mes]-$horas;
                            // Aquí puedes manejar este caso
                        }

                    }
                return $data;
            }
            return $data;

        }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        public function validationHoursTeacher($fechas,$totalHoursComponent,$totalHoursBlock,$teacher_id,$horaInicio,$horaFinal,$jornada,$monthsLockedA,$monthsLockedB){
            $validateTeacherMaxHourV = $this->validateTeacherMaxHours($fechas,$totalHoursComponent,$totalHoursBlock,$teacher_id,$horaInicio,$horaFinal,$jornada,null);
            $mesesDisponibleEventosEven = [];
            $mesesDisponibleEventosOdd = [];
            $data = [
                "totalEvents"=>0,
                "timeDifferenceTeacherTotal"=>0,
                "timeDifferenceEvent"=>0,
                "error"=>true,
                "horasPorMesOcupado"=>[],
                "horasPorTrimestre"=>0,
                "horasTotalesMes"=>0,
                'horasPorMesDesocupado'=>[],
                "eventACount"=>0,
                "eventBCount"=>0,
                "eventA"=>[],
                "eventB"=>[],
                "message"=>"",
                "mesesSesionesDisponibleEven"=>[],
                "mesesSesionesDisponibleOdd"=>[],
                "mesesBloqueadosA"=>null,
                "mesesBloqueadosB"=>null,
                "meses"=>[],

            ];
            if ($validateTeacherMaxHourV) {
                $data["timeDifferenceTeacherTotal"] = $validateTeacherMaxHourV['timeDifferenceTeacherTotal'];
                $data["timeDifferenceEvent"] = $validateTeacherMaxHourV['timeDifferenceEvent'];
                $data["error"] = $validateTeacherMaxHourV['error'];
                $data["horasPorMesOcupado"] = $validateTeacherMaxHourV['horasPorMesOcupado'];
                $data["horasPorTrimestre"] = $validateTeacherMaxHourV['horasPorTrimestre'];
                $data["horasTotalesMes"] = $validateTeacherMaxHourV['horasTotalesMes'];
                $data["horasPorMesDesocupado"] = $validateTeacherMaxHourV['horasPorMesDesocupado'];
                $data["eventACount"] = count($validateTeacherMaxHourV['eventA']);
                $data["eventBCount"] = count($validateTeacherMaxHourV['eventB']);
                $data["eventA"] = $validateTeacherMaxHourV['eventA'];
                $data["eventB"] = $validateTeacherMaxHourV['eventB'];
                $data["totalEvents"] = $validateTeacherMaxHourV['totalEvents'];
                $data["meses"] = $validateTeacherMaxHourV['meses'];

                //////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //meses bloqueados

                if ($monthsLockedA !== null)$data['mesesBloqueadosA']= $monthsLockedA;
                if ($monthsLockedB !== null)$data['mesesBloqueadosB']= $monthsLockedB;

                //////////////////////////////////////////////////////////////////////////////////////////////////////////////

                if($validateTeacherMaxHourV['horasPorMesDesocupado'] && $data["error"] ==false){
                    foreach ($validateTeacherMaxHourV['horasPorMesDesocupado'] as $mes => $horas) {
                        if ($horas>= $validateTeacherMaxHourV['timeDifferenceTeacherTotal']) {
                            $data["mesesSesionesDisponibleOdd"][$mes] = $data["totalEvents"];
                        }
                    }
                    foreach ($validateTeacherMaxHourV['horasPorMesDesocupado'] as $mes => $horas) {
                        if ($horas>= $validateTeacherMaxHourV['timeDifferenceTeacherTotal']) {
                            $data["mesesSesionesDisponibleEven"][$mes] = $data["totalEvents"];
                        }
                    }
                    foreach($validateTeacherMaxHourV["eventA"] as $fecha){
                        $fecha1=Carbon::parse($fecha);
                        $mes = $fecha1->format('F');
                        if (!isset($mesesDisponibleEventosEven[$mes]) || !is_array($mesesDisponibleEventosEven[$mes])) {
                            $mesesDisponibleEventosEven[$mes] = [];
                        }
                        $mesesDisponibleEventosEven[$mes][] = $fecha;
                    }


                    //Valiodacion para quitar el mes que no nos sirve
                    if($monthsLockedA != null){
                        foreach ($monthsLockedA as $mes) {
                            unset($mesesDisponibleEventosEven[$mes]);
                        }
                    }



                    foreach($validateTeacherMaxHourV["eventB"] as $fecha){
                        $fecha1=Carbon::parse($fecha);
                        $mes = $fecha1->format('F');
                        if (!isset($mesesDisponibleEventosOdd[$mes]) || !is_array($mesesDisponibleEventosOdd[$mes])) {
                            $mesesDisponibleEventosOdd[$mes] = [];
                        }
                        $mesesDisponibleEventosOdd[$mes][] = $fecha;
                    }

                    //Valiodacion para quitar el mes que no nos sirve
                    if($monthsLockedB != null){
                        foreach ($monthsLockedB as $mes) {
                            unset($mesesDisponibleEventosOdd[$monthsLockedB]);
                        }
                    }


                    $eventOddDisponibleCount=[];
                    $eventEvenDisponibleCount=[];
                    foreach($mesesDisponibleEventosOdd as $mes => $eventos){
                        if (!isset($eventOddDisponibleCount[$mes])) {
                            $eventOddDisponibleCount[$mes] = 0;
                        }
                        $eventOddDisponibleCount[$mes]+=count(array_unique($eventos));
                    }

                    foreach($mesesDisponibleEventosEven as $fecha => $eventos){
                        if (!isset($eventEvenDisponibleCount[$fecha])) {
                            $eventEvenDisponibleCount[$fecha] = 0;
                        }
                        $eventEvenDisponibleCount[$fecha]+=count(array_unique($eventos));
                    }

                    $mesesDisponiblesPPEven = [];
                    $mesesDisponiblesPPOdd = [];
                    $count1 = 0;
                    $count2 = 0;
                    $count3 = 0;
                    $count4 = 0;
                    $mesesEven = array_keys($eventEvenDisponibleCount); // Obtiene las claves del array (los nombres de los meses)
                    $valoresEven = array_values($eventEvenDisponibleCount); //
                    $mesesOdd = array_keys($eventOddDisponibleCount); // Obtiene las claves del array (los nombres de los meses)
                    $valoresOdd = array_values($eventOddDisponibleCount); //

                    $incrementadoOdd = false;
                    $incrementadoEven = false;

                    while(
                        (count(array_unique($eventOddDisponibleCount)) != 0 ? $count2  : 0) <= count(array_unique($eventOddDisponibleCount)) &&
                        (count(array_unique($eventEvenDisponibleCount)) != 0 ? $count4 : 0) <= count(array_unique($eventEvenDisponibleCount)) &&
                        $count3 != 2
                        ){

                            if(count(array_unique($eventOddDisponibleCount)) == 0 && !$incrementadoOdd){
                                $count3++;
                                $incrementadoOdd = true; // Marca que ya se ha incrementado para eventOddDisponibleCount
                            }
                            if(count(array_unique($eventEvenDisponibleCount)) == 0 && !$incrementadoEven){
                                $count3++;
                                $incrementadoEven = true; // Marca que ya se ha incrementado para eventEvenDisponibleCount
                            }
                        foreach ($eventEvenDisponibleCount as $mes=> $countEvent){
                        $count2++;
                            if ($count3 ==1)break;
                            if($countEvent >= $data['totalEvents'] && count(array_unique($eventEvenDisponibleCount))>=$count1){
                                if (!isset($mesesDisponiblesPPEven[$mes]) || !is_array($mesesDisponiblesPPEven[$mes])) {
                                    $mesesDisponiblesPPEven[$mes] = [];
                                }
                            }

                            if ((count(array_unique($eventEvenDisponibleCount))*2)>=$count1) {
                                $indiceActual = array_search($mes, $mesesEven); // Encuentra el índice del mes actual en el array de meses
                                if ($indiceActual === false || $indiceActual == count($mesesEven) - 1) continue; // Si es el último mes, no hay "siguiente mes"

                                // Obtiene el valor del siguiente mes
                                $siguienteMes = $valoresEven[$indiceActual + 1]; // Accede al valor del siguiente mes usando el índice actual + 1

                                // Suma el valor del mes actual con el del siguiente mes
                                $sumaSiguienteMes = $countEvent + $siguienteMes;

                                // Verifica si la suma es mayor o igual al valor que deseas comparar
                                if ($sumaSiguienteMes >= $data['totalEvents']) {
                                    if (!isset($mesesDisponiblesPPEven[$mes]) || !is_array($mesesDisponiblesPPEven[$mes])) {
                                        $mesesDisponiblesPPEven[$mes] = $eventEvenDisponibleCount[$mes];
                                        $mesesDisponiblesPPEven[$mesesEven[$indiceActual + 1]] = $eventEvenDisponibleCount[$mesesEven[$indiceActual + 1]]; // Agrega el siguiente mes al array de meses disponibles
                                        $count3++;
                                        break;
                                    }
                                }
                            }

                            if ((count(array_unique($eventEvenDisponibleCount))*3)>=$count1) {
                                $indiceActual = array_search($mes, $mesesEven); // Encuentra el índice del mes actual en el array de meses
                                if ($indiceActual === false || $indiceActual == count($mesesEven) - 2) continue; // Si es el último mes, no hay "siguiente mes"

                                // Obtiene el valor del siguiente mes
                                $siguienteMes1 = $valoresEven[$indiceActual + 1]; // Accede al valor del siguiente mes usando el índice actual + 1
                                $siguienteMes2 = $valoresEven[$indiceActual + 2]; // Accede al valor del siguiente mes usando el índice actual + 1

                                // Suma el valor del mes actual con el del siguiente mes
                                $sumaSiguienteMes = $countEvent + $siguienteMes1 + $siguienteMes2;

                                // Verifica si la suma es mayor o igual al valor que deseas comparar
                                if ($sumaSiguienteMes >= $data['totalEvents']) {
                                    if (!isset($mesesDisponiblesPPEven[$mes]) || !is_array($mesesDisponiblesPPEven[$mes])) {
                                        $mesesDisponiblesPPEven[$mes] = $eventEvenDisponibleCount[$mes];
                                        $mesesDisponiblesPPEven[$mesesEven[$indiceActual + 1]] = $eventEvenDisponibleCount[$mesesEven[$indiceActual + 1]]; // Agrega el siguiente mes al array de meses disponibles
                                        $mesesDisponiblesPPEven[$mesesEven[$indiceActual + 2]] = $eventEvenDisponibleCount[$mesesEven[$indiceActual + 2]]; // Agrega el siguiente mes al array de meses disponibles
                                        $count3++;
                                        break;
                                    }
                                }
                            }

                            if ((count(array_unique($eventEvenDisponibleCount))*(count(array_unique($eventEvenDisponibleCount))))>=$count1) {
                                $count3++;
                                break;
                            }

                            $count1++;
                        }

                        $count1=0;

                        foreach ($eventOddDisponibleCount as $mes=> $countEvent){
                            $count4 ++;
                            if ($count3 ==2)break;

                            if($countEvent >= $data['totalEvents'] && count(array_unique($eventOddDisponibleCount))>=$count1){
                                if (!isset($mesesDisponiblesPPOdd[$mes]) || !is_array($mesesDisponiblesPPOdd[$mes])) {
                                    $mesesDisponiblesPPOdd[$mes] = [];
                                }
                            }

                            if ((count(array_unique($eventOddDisponibleCount))*2)>=$count1) {
                                $indiceActual = array_search($mes, $mesesOdd); // Encuentra el índice del mes actual en el array de meses
                                if ($indiceActual === false || $indiceActual == count($mesesOdd) - 1) continue; // Si es el último mes, no hay "siguiente mes"

                                // Obtiene el valor del siguiente mes
                                $siguienteMes = $valoresOdd[$indiceActual + 1]; // Accede al valor del siguiente mes usando el índice actual + 1

                                // Suma el valor del mes actual con el del siguiente mes
                                $sumaSiguienteMes = $countEvent + $siguienteMes;

                                // Verifica si la suma es mayor o igual al valor que deseas comparar
                                if ($sumaSiguienteMes >= $data['totalEvents']) {
                                    if (!isset($mesesDisponiblesPPOdd[$mes]) || !is_array($mesesDisponiblesPPOdd[$mes])) {
                                        $mesesDisponiblesPPOdd[$mes] = $eventOddDisponibleCount[$mes];
                                        $mesesDisponiblesPPOdd[$mesesOdd[$indiceActual + 1]] = $eventOddDisponibleCount[$mesesOdd[$indiceActual + 1]]; // Agrega el siguiente mes al array de meses disponibles
                                        $count3++;
                                        break;
                                    }
                                }
                            }

                            if ((count(array_unique($eventOddDisponibleCount))*3)>=$count1) {
                                $indiceActual = array_search($mes, $mesesOdd); // Encuentra el índice del mes actual en el array de meses
                                if ($indiceActual === false || $indiceActual == count($mesesOdd) - 2) continue; // Si es el último mes, no hay "siguiente mes"

                                // Obtiene el valor del siguiente mes
                                $siguienteMes1 = $valoresOdd[$indiceActual + 1]; // Accede al valor del siguiente mes usando el índice actual + 1
                                $siguienteMes2 = $valoresOdd[$indiceActual + 2]; // Accede al valor del siguiente mes usando el índice actual + 1

                                // Suma el valor del mes actual con el del siguiente mes
                                $sumaSiguienteMes = $countEvent + $siguienteMes1 + $siguienteMes2;

                                // Verifica si la suma es mayor o igual al valor que deseas comparar
                                if ($sumaSiguienteMes >= $data['totalEvents']) {
                                    if (!isset($mesesDisponiblesPPOdd[$mes]) || !is_array($mesesDisponiblesPPOdd[$mes])) {
                                        $mesesDisponiblesPPOdd[$mes] = $eventOddDisponibleCount[$mes];
                                        $mesesDisponiblesPPOdd[$mesesOdd[$indiceActual + 2]] = $eventOddDisponibleCount[$mesesOdd[$indiceActual + 2]]; // Agrega el siguiente mes al array de meses disponibles
                                        $mesesDisponiblesPPOdd[$mesesOdd[$indiceActual + 1]] = $eventOddDisponibleCount[$mesesOdd[$indiceActual + 1]]; // Agrega el siguiente mes al array de meses disponibles
                                        $count3++;
                                        break;
                                    }
                                }
                            }

                            if ((count(array_unique($eventEvenDisponibleCount))*(count(array_unique($eventEvenDisponibleCount))))>=$count1) {
                                $indiceActual = array_search($mes, $mesesEven); // Encuentra el índice del mes actual en el array de meses
                                if ($indiceActual === false || $indiceActual == count($mesesEven) - 3) continue; // Si es el último mes, no hay "siguiente mes"

                                // Obtiene el valor del siguiente mes
                                $siguienteMes1 = $valoresEven[$indiceActual + 1]; // Accede al valor del siguiente mes usando el índice actual + 1
                                $siguienteMes2 = $valoresEven[$indiceActual + 2]; // Accede al valor del siguiente mes usando el índice actual + 1
                                $siguienteMes3 = $valoresEven[$indiceActual + 3]; // Accede al valor del siguiente mes usando el índice actual + 1

                                // Suma el valor del mes actual con el del siguiente mes
                                $sumaSiguienteMes = $countEvent + $siguienteMes1 + $siguienteMes2 + $siguienteMes3;

                                // Verifica si la suma es mayor o igual al valor que deseas comparar
                                if ($sumaSiguienteMes >= $data['totalEvents']) {
                                    if (!isset($mesesDisponiblesPPEven[$mes]) || !is_array($mesesDisponiblesPPEven[$mes])) {
                                        $mesesDisponiblesPPEven[$mes] = $eventEvenDisponibleCount[$mes];
                                        $mesesDisponiblesPPEven[$mesesEven[$indiceActual + 1]] = $eventEvenDisponibleCount[$mesesEven[$indiceActual + 1]]; // Agrega el siguiente mes al array de meses disponibles
                                        $mesesDisponiblesPPEven[$mesesEven[$indiceActual + 2]] = $eventEvenDisponibleCount[$mesesEven[$indiceActual + 2]]; // Agrega el siguiente mes al array de meses disponibles
                                        $mesesDisponiblesPPEven[$mesesEven[$indiceActual + 3]] = $eventEvenDisponibleCount[$mesesEven[$indiceActual + 3]]; // Agrega el siguiente mes al array de meses disponibles
                                        $count3++;
                                        break;
                                    }
                                }
                            }

                            $count1++;
                        }
                    }

                    $mesesDisponibleEventosEvenFiltradosOdd = [];
                    $mesesDisponiblesFinalesKeysOdd = array_keys($mesesDisponiblesPPOdd); // Obtiene las claves del array (los nombres de los meses)

                    $mesesDisponibleEventosEvenFiltradosEven = [];
                    $mesesDisponiblesFinalesKeysEven = array_keys($mesesDisponiblesPPEven); // Obtiene las claves del array (los nombres de los meses)

                    // $mesesDisponiblesFinales =
                    $mesesDisponibleEventosEvenFiltradosOdd = array_intersect_key($mesesDisponibleEventosOdd, array_flip($mesesDisponiblesFinalesKeysOdd));
                    $data["mesesSesionesDisponibleOdd"] = $mesesDisponibleEventosEvenFiltradosOdd;

                    $mesesDisponibleEventosEvenFiltradosEven = array_intersect_key($mesesDisponibleEventosEven, array_flip($mesesDisponiblesFinalesKeysEven));
                    $data["mesesSesionesDisponibleEven"] = $mesesDisponibleEventosEvenFiltradosEven;
                    // $validateTeacherMaxHourV['message']="El instructor ya tiene sus horas ocupadas en el ";

                    // dd($data);
                    return $data;
                }else {

                    return $data;
                    // $validateTeacherMaxHourV["state"]+= true;
                }

            }else{
                return $data;
            }


        }



}
