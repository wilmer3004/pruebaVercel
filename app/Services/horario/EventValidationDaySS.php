<?php
namespace App\Services\horario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class EventValidationDaySS{

    /////////////////////////////////////////////////////////////////////////////////////
    //validacion para que una ficha no pueda seleccionar un bloque distinto al de su jornada
    public function validateDayStudySheet($day,$studySheet,$hourStart,$hourEnd){
        $validateDay = DB::table('blocks as b')
            ->join('days as d','d.id','=','b.day_id')
            ->where('b.time_start',$hourStart)
            ->where('b.time_end',$hourEnd)
            ->select('d.id as idDay','d.name')
            ->get();

        $validateStudyS = DB::table('study_sheets as s')
            ->join('days as d','d.id','=','s.day_id')
            ->where('s.id',$studySheet)
            ->select('d.id as idStudyS','d.name')
            ->get();

        $dataResponse=[
            "message"=>'',
            "error"=>false
        ];

        // dd($validateDay[0]->idDay!=$validateStudyS[0]->idStudyS);
        //     dd($validateDay,$validateStudyS);
            if($validateDay[0]->idDay!=$validateStudyS[0]->idStudyS){
                $dataResponse['message']='La jornada del bloque horario seleccionado es diferente a la jornada del bloque horario de la ficha';
                $dataResponse['error']=true;
            }else{
                $dataResponse['message']='Jornadas compatibles';
            }

            // dd($dataResponse);
        return $dataResponse;

    }



}


