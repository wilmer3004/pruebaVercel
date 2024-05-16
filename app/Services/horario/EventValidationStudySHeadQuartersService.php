<?php
namespace App\Services\horario;
    use Carbon\Carbon;
    use Exception;
    use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

    class EventValidationStudySHeadQuartersService{
        /////////////////////////////////////////////////////
        //File validation at different locations
        public function validateStudySheetDifferentHeadQuarter($data){
            //Response json
            $responseData = [
                'status'=>false,
                'message'=>'',
                'typeDayAB'=>'',
                'daysToShedule'=>[],
                'error'=>false,
            ];

            //Instance of class
            $validateStudySheet = new EventValidationStudySheetService();

            //Arrays
            $validateDayA = [];
            $validateDayB = [];

            // Variables
            $dates = $data['dates'];
            $totalHoursComponent = $data['totalHoursComponent'];
            $totalHoursBlock = $data['totalHoursBlock'];
            $studySheetId = $data['studySheetId'];
            $startTime = $data['startTime'];
            $endTime = $data['endTime'];
            $days = $data['days'];
            $responseValidateStudyS = $validateStudySheet->validationDateCompletStudyS($dates,$totalHoursComponent,$totalHoursBlock,$studySheetId,$startTime,$endTime,$days);
            $dayA = $responseValidateStudyS['dayA'];
            $dayB = $responseValidateStudyS['dayB'];
            $dayAvailableA = $responseValidateStudyS['dayAvailableA'];
            $dayAvailableB = $responseValidateStudyS['dayAvailableB'];
            $totalEvents = $responseValidateStudyS['totalEvents'];


            //Check available days if it is busy at any other time and where it is located
            try{
                if(sizeof($dayA)>= $dayAvailableA){
                    for($i = 0; $i < sizeof($dayA); $i++){
                        if(sizeof($validateDayA)<$totalEvents){
                            $dayAValidate = Carbon::parse($dayA[$i]);
                            if($i < (sizeof($dayA) - 1)){
                                $dayAValidateAfter = Carbon::parse($dayA[$i + 1]);
                                $countDays = $dayAValidate->diffInDays($dayAValidateAfter);
                                if($countDays <= 5 && $countDays >0 ){
                                    if(!in_array($dayAValidate->format('Y-m-d'), $validateDayA)){
                                        $validateDayA[] = $dayA[$i];
                                    }
                                }else{
                                    $validateDayA = [];
                                }
                            }

                        }else{
                            break;
                        }

                    }

                    if(sizeof($validateDayA)<$totalEvents){
                        $responseData['message'] = "La cantidad de eventos no cumplen con la longitud de dias cercanos a programar";
                        $responseData['typeDayAB']="A";
                    }else{
                        $responseData['message']="Si cumple con la cantidad de dias a programar";
                        $responseData['daysToShedule']=$validateDayA;
                        $responseData['status']=true;
                        $responseData['error']=false;
                        $responseData['typeDayAB']="A";
                    }
                }else if(sizeof($dayB) >=$dayAvailableB){
                    for($i = 0; $i < sizeof($dayB); $i++){
                        if(sizeof($validateDayB)<$totalEvents){
                            $dayBValidate = Carbon::parse($dayB[$i]);
                            if($i < (sizeof($dayB) - 1)){
                                $dayBValidateAfter = Carbon::parse($dayB[$i + 1]);
                                $countDays = $dayBValidate->diffInDays($dayBValidateAfter);

                                if($countDays <= 5 && $countDays >0 ){
                                    if(!in_array($dayBValidate->format('Y-m-d'), $validateDayB)){
                                        $validateDayB[] = $dayB[$i];
                                    }
                                }else{
                                    $validateDayB = [];
                                }
                            }

                        }else{
                            break;
                        }

                    }
                    if(sizeof($validateDayB)<$totalEvents){
                        $responseData['message'] = "La cantidad de eventos no cumplen con la longitud de dias cercanos a programar";
                        $responseData['typeDayAB']="B";
                    }else{
                        $responseData['message']="Si cumple con la cantidad de dias a programar";
                        $responseData['daysToShedule']=$validateDayB;
                        $responseData['status']=true;
                        $responseData['error']=false;
                        $responseData['typeDayAB']="B";
                    }
                }
                else{
                    $responseData['message'] = "La cantidad de eventos no cumplen con la longitud de dias a programar";
                }
            }catch(Exception $e){

                Log::info($e);
                $responseData['error']=true;
            }
            return $responseData;

        }

        //Metodo de validacion de dia por dia si exziste disponibilidad

        //Realizar validacion que se comunique con el metodo anterior para la solicitud en caso que la cantidad de dias sea mayor y no sirva los primeros dias a programar por lo tanto tome en cuenta el tope de dias y que se pueda programar o no
        public function eventValidateDayForDayHQ($data){

                //Funcion de recoleccion de dias disponibles
                $validateEventHeadQuarter = $this->validateStudySheetDifferentHeadQuarter($data);
                // Variables
                $dates = $data['dates'];
                $totalHoursComponent = $data['totalHoursComponent'];
                $totalHoursBlock = $data['totalHoursBlock'];
                $studySheetId = $data['studySheetId'];
                $startTime = $data['startTime'];
                $endTime = $data['endTime'];
                $days = $data['days'];



                dd($validateEventHeadQuarter);
                return $validateEventHeadQuarter;
        }



    }





