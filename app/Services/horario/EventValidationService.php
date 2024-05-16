<?php

namespace App\Services\horario;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventValidationService
{
    public function validateEventByConditionEnvironment($environmentId, $startDate, $endDate)
    {
        $eventsST = DB::table('events as ev')
            ->where('environment_id', '=', $environmentId)
            ->where('start', '=', $startDate->toDateTimeString())
            ->where('end', '=', $endDate->toDateTimeString())
            ->exists();
        if ($eventsST) {
            return true;
        }
        return false;
    }

    // FUNCION PARA VALIDAR SI UN AMBIENTE CUENTA CON DISPONIBILIDAD
    public function validationDateCompleted($fechas,$totalHoursComponent,$totalHoursBlock,$environmentId,$horaInicio,$horaFinal,$jornada){
        // Obtener las fechas de festivos y convertirlas a un array
        $fechasOmitir = DB::table('holidays')->pluck('date')->toArray();

        $fechaCero = '1999-12-30';

        // Calcular el número total de eventos a programar
        $lengEventsToShedule = (int) ($totalHoursComponent / $totalHoursBlock);

            $fechaInicioTrimestre = Carbon::createFromFormat('Y-m-d', $fechas[0]);
            $diaInicioTrimestre = Carbon::parse($fechaInicioTrimestre)->day;

            // Elimino los festivos del array de trimestre
            $dateToShedule = array_diff($fechas, $fechasOmitir);

            // Asignar un valor determinado al array en el indice 0

            if ($diaInicioTrimestre % 2 == 0){
                array_unshift($dateToShedule);
            }
            else {
                array_unshift($dateToShedule, $fechaCero);
            }

            // dd($dateToShedule);
        // Preparar un array para almacenar las fechas disponibles
        $dateToSheduleAvailable = [];
        //Dias pares
        $evenDay = [];
        //Dias impares
        $oddDay = [];

        
        //Determinar dia pares e impares

        for ($i = 0; $i < sizeof($dateToShedule); $i++) {


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
            ->where('environment_id', $environmentId)
            ->where(function ($query) use ($fechaInicio1, $fechaFinal1) {
                $query->whereBetween('start', [$fechaInicio1, $fechaFinal1])
                    ->orWhereBetween('end', [$fechaInicio1, $fechaFinal1]);
                $query->where('start', '<', $fechaFinal1);
            })->exists();

            /////////////////////////////////////////////////////////
            //Si ya existe el evento continuar a la siguiente iteracion
            if ($eventToCreateDay) continue;

            if($dateToShedule[$i]=='1999-12-30')continue;

            // Parsear a numero de dia
            $dayCarbon = Carbon::parse($dateToShedule[$i]);
            $dayFormatText = $dayCarbon->format('N'); // 'N' devuelve el día de la semana como un número
            if($jornada === 'fin de semana'){
                // Verificar si el día es sábado o domingo
                if ($dayFormatText ==  6 || $dayFormatText ==  7 && $i % 2 == 0) {
                    $evenDay[] = $dateToShedule[$i];
                } else if( $dayFormatText ==  6 || $dayFormatText ==  7 && $i % 2 != 0) {
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
        // dd($evenDayCount,$oddDayCount);
        // dd($evenDay,$oddDay,$lengEventsToShedule,$evenAvailable,$oddAvailable);

    // Estado por defecto, asumiendo que el estado es falso y no hay disponibilidad
    // Estado por defecto, asumiendo que el estado es falso y no hay disponibilidad
    $data = [
        "totalEvents" => $lengEventsToShedule,
        "evenAvailable" => max(0, $evenAvailable),
        "oddAvailable" => max(0, $oddAvailable),
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
}
