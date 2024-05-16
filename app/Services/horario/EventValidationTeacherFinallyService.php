<?php
namespace App\Services\horario;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\DB;

class EventValidationTeacherFinallyService{
    public function eventValidationTeacherM($fechas,$componentHours,$totalHoursBlock,$instructorID,$horaInicio,$horaFinal,$jornada,$monthsLockedA,$monthsLockedB){

        $data=[
            "validationTeacher"=>[],
            "diasValidacionEven"=>[],
            "diasValidacionOdd"=>[],
            "mesesBloqueadosA"=>null,
            "mesesBloqueadosB"=>null,
            'meses'=>[],
        ];


        $validationEventTeacher = new EventValidationTeacherService();
        if($monthsLockedA !==null && $monthsLockedB !==null){
            $validationTeacher = $validationEventTeacher->validationHoursTeacher($fechas,$componentHours,$totalHoursBlock,$instructorID,$horaInicio,$horaFinal,$jornada,$monthsLockedA,$monthsLockedB);
        }else if($monthsLockedA !==null){
            $validationTeacher = $validationEventTeacher->validationHoursTeacher($fechas,$componentHours,$totalHoursBlock,$instructorID,$horaInicio,$horaFinal,$jornada,$monthsLockedA,null);
        }else if($monthsLockedB !==null){
            $validationTeacher = $validationEventTeacher->validationHoursTeacher($fechas,$componentHours,$totalHoursBlock,$instructorID,$horaInicio,$horaFinal,$jornada,null,$monthsLockedB);
        }else{
            $validationTeacher = $validationEventTeacher->validationHoursTeacher($fechas,$componentHours,$totalHoursBlock,$instructorID,$horaInicio,$horaFinal,$jornada,null,null);
        }

        $data['mesesBloqueadosA']=$validationTeacher['mesesBloqueadosA'];
        $data['mesesBloqueadosB']=$validationTeacher['mesesBloqueadosB'];
        $data['meses']=$validationTeacher['meses'];

        $mesesOdd = count($validationTeacher['mesesSesionesDisponibleOdd'])!=0? array_keys($validationTeacher['mesesSesionesDisponibleOdd']):[];
        $diasValidosEventosOdd = [];
        $mesesEven = count($validationTeacher['mesesSesionesDisponibleEven'])!=0? array_keys($validationTeacher['mesesSesionesDisponibleEven']):[];
        $diasValidosEventosEven = [];
        $contadorWhile = 0;

        $incrementadoOdd = false;
        $incrementadoEven = false;

        while($contadorWhile <2){
            if(count($validationTeacher['mesesSesionesDisponibleOdd']) == 0 && !$incrementadoOdd){
                $contadorWhile++;
                $incrementadoOdd = true; // Marca que ya se ha incrementado para eventOddDisponibleCount
            }
            if(count($validationTeacher['mesesSesionesDisponibleEven']) == 0 && !$incrementadoEven){
                $contadorWhile++;
                $incrementadoEven = true; // Marca que ya se ha incrementado para eventEvenDisponibleCount
            }
            if (!$incrementadoOdd) {

                foreach($validationTeacher['mesesSesionesDisponibleOdd'] as $mes =>$fecha){
                // $contador = 0;

                for($i=0; $i<sizeof($validationTeacher['mesesSesionesDisponibleOdd'][$mes]); $i++){

                    $indiceActual = array_search($mes, $mesesOdd); // Encuentra el índice del mes actual en el array de meses

                    if ($indiceActual === false) continue; // Si es el último mes, no hay "siguiente mes"


                    if(end($validationTeacher['mesesSesionesDisponibleOdd'][end($mesesOdd)]) == $validationTeacher['mesesSesionesDisponibleOdd'][$mes][$i])break;

                    $fechaD1 = Carbon::parse($validationTeacher['mesesSesionesDisponibleOdd'][$mes][$i]);

                    if ($validationTeacher['mesesSesionesDisponibleOdd'][$mes][$i] == end($validationTeacher['mesesSesionesDisponibleOdd'][$mes])){
                        $fechaD2 = Carbon::parse($validationTeacher['mesesSesionesDisponibleOdd'][$mesesOdd[$indiceActual + 1]][0]);
                    }else{
                        $fechaD2 = Carbon::parse($validationTeacher['mesesSesionesDisponibleOdd'][$mes][$i+1]);
                    }

                    $diferenciaDias = $fechaD1->diffInDays($fechaD2);

                    if($diferenciaDias <=5){
                        $diasValidosEventosOdd[]=$validationTeacher['mesesSesionesDisponibleOdd'][$mes][$i];

                    }else{
                        $contadorWhile++;
                        break;

                    }
                }


            }}else {
                // echo "El array \$validationTeacher['mesesSesionesDisponibleOdd'] está vacío.";

            }

            if (!$incrementadoEven) {
            foreach($validationTeacher['mesesSesionesDisponibleEven'] as $mes =>$fecha){
                if($incrementadoEven)break;

                // $contador = 0;
                for($i=0; $i<sizeof($validationTeacher['mesesSesionesDisponibleEven'][$mes]); $i++){

                    $indiceActual = array_search($mes, $mesesEven); // Encuentra el índice del mes actual en el array de meses

                    if ($indiceActual === false) continue; // Si es el último mes, no hay "siguiente mes"


                    if(end($validationTeacher['mesesSesionesDisponibleEven'][end($mesesEven)]) == $validationTeacher['mesesSesionesDisponibleEven'][$mes][$i])break;

                    $fechaD1 = Carbon::parse($validationTeacher['mesesSesionesDisponibleEven'][$mes][$i]);

                    if ($validationTeacher['mesesSesionesDisponibleEven'][$mes][$i] == end($validationTeacher['mesesSesionesDisponibleEven'][$mes])){
                        $fechaD2 = Carbon::parse($validationTeacher['mesesSesionesDisponibleEven'][$mesesEven[$indiceActual + 1]][0]);
                    }else{
                        $fechaD2 = Carbon::parse($validationTeacher['mesesSesionesDisponibleEven'][$mes][$i+1]);
                    }

                    $diferenciaDias = $fechaD1->diffInDays($fechaD2);

                    if($diferenciaDias <=5){
                        $diasValidosEventosEven[]=$validationTeacher['mesesSesionesDisponibleEven'][$mes][$i];

                    }else{$contadorWhile++;}
                }


            }}else {
                // echo "El array \$validationTeacher['mesesSesionesDisponibleEven'] está vacío.";

            }
            $contadorWhile++;
        }
        $data['validationTeacher']=$validationTeacher;
        $data['diasValidacionOdd']=$diasValidosEventosOdd;
        $data['diasValidacionEven']=$diasValidosEventosEven;
        $data['validationTeacher'] = $validationTeacher;
        $data['diasValidacionOdd'] = $diasValidosEventosOdd;
        $data['diasValidacionEven'] = $diasValidosEventosEven;

        return $data;
    }



    public function eventValidationTeacherMenssage($fechas,$componentHours,$totalHoursBlock,$instructorID,$horaInicio,$horaFinal,$jornada){
        $validationTeacherM = $this->eventValidationTeacherM($fechas,$componentHours,$totalHoursBlock,$instructorID,$horaInicio,$horaFinal,$jornada,null,null);
        $totalEvents = $validationTeacherM['validationTeacher']['totalEvents'];
        $eventsAvailableOdd = count($validationTeacherM['diasValidacionOdd']);
        $eventsAvailableEven = count($validationTeacherM['diasValidacionEven']);


        $message =[
            'state'=> true,
            'diasDisponibles'=>$eventsAvailableOdd,
            'success'=>true,
            'message'=>'El instructor puede ser programado en las siguientes fechas de acuerdo a la longitud de eventos disponibles',
            'eventsAvailableOdd'=>$validationTeacherM['diasValidacionOdd'],
            'eventsAvailableEven'=>$validationTeacherM['diasValidacionEven'],
            "mesesBloqueadosA"=>null,
            "mesesBloqueadosB"=>null,
        ];
        $uselessMonthsA=[];
        $uselessMonthsB=[];
        $meses = $validationTeacherM['meses'];
        $mesesBloqueadosA = $validationTeacherM['mesesBloqueadosA'];
        $mesesBloqueadosB = $validationTeacherM['mesesBloqueadosB'];
        $validationTeacherMRepeat = null;
        $countWhileError = 0;

        while($eventsAvailableEven<$totalEvents && $eventsAvailableEven>=0 && $countWhileError < 20){
            $countWhileError++;
            echo("<pre>");
            print_r( $uselessMonthsA);
            echo("</pre>");
            for ($i = 0; $i < count($meses); $i++) {

                if($i<(count($meses)-1)){
                    $mes = $meses[$i+1]; // Asumiendo que $meses[$i] es el nombre del mes
                }else{
                    $mes = $meses[$i]; // Asumiendo que $meses[$i] es el nombre del mes
                }

                if((count($meses)%2)==0 && (count($meses)/2)<=count($uselessMonthsA))break;
                if((count($meses)%2)==1 && (count($meses)/2)+1<=count($uselessMonthsA))break;

                // Verificar si el mes ya está en el array $uselessMonthsA
                if (!in_array($mes, $uselessMonthsA)) {
                    if (count($meses) % 2 == 0) {
                        if ($mesesBloqueadosA != null) {
                            if (!in_array($mes, $mesesBloqueadosA) || !in_array($mes, $uselessMonthsA)) {
                                $uselessMonthsA[] = $mes; // Agregar el mes si cumple la condición
                                break;
                            }
                        } else {
                            $uselessMonthsA[] = $meses[0]; // Agregar el primer mes si no hay meses bloqueados
                            break;
                        }
                    } else {
                        if ($mesesBloqueadosA != null) {
                            if (!in_array($mes, $mesesBloqueadosA) || !in_array($mes, $uselessMonthsA)) {
                                $uselessMonthsA[] = $mes; // Agregar el mes si cumple la condición
                                break;
                            }
                        } else {
                            $uselessMonthsA[] = $meses[0]; // Agregar el primer mes si no hay meses bloqueados
                            break;
                        }
                    }
                }
            }

            if(count($uselessMonthsA)>0){
                $validationTeacherMRepeat=$this->eventValidationTeacherM($fechas,$componentHours,$totalHoursBlock,$instructorID,$horaInicio,$horaFinal,$jornada,$uselessMonthsA,null);
            }else{
                $validationTeacherMRepeat=$this->eventValidationTeacherM($fechas,$componentHours,$totalHoursBlock,$instructorID,$horaInicio,$horaFinal,$jornada,null,null);
            }
            echo("<pre>");
            print_r($validationTeacherMRepeat );
            echo("</pre>");
            $meses = $validationTeacherMRepeat['meses'];
            $mesesBloqueadosA = $validationTeacherMRepeat['mesesBloqueadosA'];
        }
        if($eventsAvailableOdd>=$totalEvents && $eventsAvailableOdd!=0){
            $validationTeacherMRepeat=$this->eventValidationTeacherM($fechas,$componentHours,$totalHoursBlock,$instructorID,$horaInicio,$horaFinal,$jornada,null,null);
        }
        $message['mesesBloqueadosA']=$validationTeacherMRepeat['mesesBloqueadosA'];
        $message['mesesBloqueadosB']=$validationTeacherMRepeat['mesesBloqueadosB'];

        return $message;

    }





}





