<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use function Laravel\Prompts\select;

class ChartsController extends Controller
{

    public function graficas()
    {

        //////////////////////////////////////////////////////////////////////////////////
        // DATOS GENERALES

        //ColorsPrograms
        $colorsPrograms = DB::table('programs')
            ->get();

        // TOTAL FICHAS
        $totalFichas = DB::table('study_sheets')
            ->count(); // numero total de fichas

        // NOMBRE JORNADAS (MAÑANA, TARDE,...)
        $nameJourney = DB::table('days')
            ->select('name')
            ->get();

        // CORDINATIONS

        $radarChartB = DB::table('coordinations as cordi')
            ->orderByDesc('cordi.id')
            ->get();

        //dd($nameJourney); // Resultado consulta $nameJourney

        // NOMBRE PROGRAMAS (Gestion contable, recursos humanos,...)
        $namePrograms = DB::table('study_sheets as s')
            ->join('programs as p', 's.program_id', '=', 'p.id')
            ->select('p.name')
            ->distinct()
            ->orderBy('p.name') // Añadir la cláusula orderBy para ordenar por nombre
            ->get();

        //dd($namePrograms); // Resultado consulta $namePrograms

        //////////////////////////////////////////////////////////////////////////////////
        // POLAR RADAR

        // NOMBRE PROGRAMA Y LA CANTIDAD DE FICHAS EN EL PROGRAMA
        $resultsPolarRadar = DB::table('study_sheets as s')
            ->join('programs as p', 's.program_id', '=', 'p.id')
            ->select('p.name as program_name', DB::raw('COUNT(s.program_id) as program_count'))
            ->groupBy('p.name')
            ->orderBy('p.name')
            ->get();


        // stackedBarChart
        // NOMBRE PROGRAMA, OFERTAS, TRIMESTRE, FICHA
        $stackedBarChart = DB::table('study_sheets as s')
            ->join('programs as p', 's.program_id', '=', 'p.id')
            ->join('offers as o', 's.offer_id', '=', 'o.id')
            ->join('quarters as q', 's.quarter_id', '=', 'q.id')
            ->join('days as d', 's.day_id', '=', 'd.id')
            ->select('p.name as program_name', 'o.name as offer_name', 'd.name', 'q.name as quarter_name', DB::raw('COUNT(s.program_id) as program_count'))
            ->groupBy('p.name', 'o.name', 'q.name', 'd.name') // Corregido: eliminamos 'desc'
            ->orderBy('p.name', 'desc') // Correcto: especificamos 'desc' como dirección del ordenamiento
            ->orderBy('o.name', 'desc')
            ->orderBy('q.name', 'desc')
            ->orderBy('d.name', 'desc')
            ->get();

        // radarChart
        // NOMBRE INSTRUCTOR, TIPO DE CONTRATO, CONDICIONES, COORDINACION
        $radarChartA = DB::table('teachers as t')
        ->leftJoin('conditions_teacher as condiT', 't.id', '=', 'condiT.teacher_id')
        ->leftJoin('conditions as condi', 'condi.id', '=', 'condiT.condition_id')
        ->leftJoin('users as u', 'u.id', '=', 't.user_id')
        ->leftJoin('contracts as c', 't.contract_id', '=', 'c.id')
        ->rightJoin('teachers_coordinations as tco', 'tco.teacher_id', '=', 't.id')
        ->rightJoin('coordinations as co', 'tco.coordination_id', '=', 'co.id')
        ->select(
            DB::raw('COUNT(DISTINCT t.id) AS teacher_count'),
            'u.state as stateU',
            'c.name as nameCo',
            'co.name as nameC',
            'condi.description as nameCondi'
        )
        ->groupBy('c.name', 'co.name', 'condi.description', 'u.state')
        ->orderBy('c.name', 'desc')
        ->get();




        $radarChart = DB::table('teachers as t')
            ->join('contracts as c', 't.contract_id', '=', 'c.id')
            ->rightJoin('teachers_coordinations as tco', 'tco.teacher_id', '=', 't.id')
            ->rightJoin('coordinations as co', 'tco.coordination_id', '=', 'co.id')
            ->select(
                DB::raw('COUNT(c.name) AS teacher_count'),
                't.id',
                't.user_id',
                DB::raw('MAX(tco.coordination_id)'), // Corrected line
                'c.name as nameCo',
                'co.name as nameC',
            )
            ->groupBy('t.id','c.name','co.name')
            ->orderBy('c.name','desc')
            ->get();





        // dd($resultsPolarRadar); Observar los resultados de la sentencia $resultsPolarRadar

        //////////////////////////////////////////////////////////////////////////////////
        // Staked Bar

        // gestion contable e informacion financiera = GCIF

        // $fichasMananaGCIF = DB::table('study_sheets') ->where('day_id', 1) ->where('program_id', 1) ->count(); // jornada mañana
        // $fichasTardeGCIF = DB::table('study_sheets') ->where('day_id', 2) ->where('program_id', 1) ->count(); // jornada tarde
        // $fichasNocheGCIF = DB::table('study_sheets') ->where('day_id', 3) ->where('program_id', 1) ->count(); // jornada noche
        // $fichasFinSemanaGCIF = DB::table('study_sheets') ->where('day_id', 4) ->where('program_id', 1) ->count(); // jornada fin de semana

        // gestion empresaial = GE

        $fichasMananaGE = DB::table('study_sheets')->where('day_id', 1)->where('program_id', 2)->count(); // jornada mañana
        $fichasTardeGE = DB::table('study_sheets')->where('day_id', 2)->where('program_id', 2)->count(); // jornada tarde
        $fichasNocheGE = DB::table('study_sheets')->where('day_id', 3)->where('program_id', 2)->count(); // jornada noche
        $fichasFinSemanaGE = DB::table('study_sheets')->where('day_id', 4)->where('program_id', 2)->count(); // jornada fin de semana

        // gestion recursos humanos = RH

        $fichasMananaRH = DB::table('study_sheets')->where('day_id', 1)->where('program_id', 3)->count(); // jornada mañana
        $fichasTardeRH = DB::table('study_sheets')->where('day_id', 2)->where('program_id', 3)->count(); // jornada tarde
        $fichasNocheRH = DB::table('study_sheets')->where('day_id', 3)->where('program_id', 3)->count(); // jornada noche
        $fichasFinSemanaRH = DB::table('study_sheets')->where('day_id', 4)->where('program_id', 3)->count(); // jornada fin de semana

        // gestion contabilizacion de operaciones comerciales = COC

        $fichasMananaCOC = DB::table('study_sheets')->where('day_id', 1)->where('program_id', 4)->count(); // jornada mañana
        $fichasTardeCOC = DB::table('study_sheets')->where('day_id', 2)->where('program_id', 4)->count(); // jornada tarde
        $fichasNocheCOC = DB::table('study_sheets')->where('day_id', 3)->where('program_id', 4)->count(); // jornada noche
        $fichasFinSemanaCOC = DB::table('study_sheets')->where('day_id', 4)->where('program_id', 4)->count(); // jornada fin de semana

        // analisis y desarrollo de software = ADSO

        $fichasMananaADSO = DB::table('study_sheets')->where('day_id', 1)->where('program_id', 6)->count(); // jornada mañana
        $fichasTardeADSO = DB::table('study_sheets')->where('day_id', 2)->where('program_id', 6)->count(); // jornada tarde
        $fichasNocheADSO = DB::table('study_sheets')->where('day_id', 3)->where('program_id', 6)->count(); // jornada noche
        $fichasFinSemanaADSO = DB::table('study_sheets')->where('day_id', 4)->where('program_id', 6)->count(); // jornada fin de semana

        //////////////////////////////////////////////////////////////////////////////////
        // Staked Bar

        // OFERTA ABIERTA
        $ofertaAbiertaTrimestreI = DB::table('study_sheets')->where('offer_id', 1)->where('quarter_id', 1)->count();
        $ofertaAbiertaTrimestreII = DB::table('study_sheets')->where('offer_id', 1)->where('quarter_id', 2)->count();
        $ofertaAbiertaTrimestreIII = DB::table('study_sheets')->where('offer_id', 1)->where('quarter_id', 3)->count();
        $ofertaAbiertaTrimestreIV = DB::table('study_sheets')->where('offer_id', 1)->where('quarter_id', 4)->count();
        $ofertaAbiertaTrimestreV = DB::table('study_sheets')->where('offer_id', 1)->where('quarter_id', 5)->count();
        $ofertaAbiertaTrimestreVI = DB::table('study_sheets')->where('offer_id', 1)->where('quarter_id', 6)->count();
        $ofertaAbiertaTrimestreVII = DB::table('study_sheets')->where('offer_id', 1)->where('quarter_id', 7)->count();

        // OFERTA CERRADA

        $ofertaCerradaTrimestreI = DB::table('study_sheets')->where('offer_id', 2)->where('quarter_id', 1)->count();
        $ofertaCerradaTrimestreII = DB::table('study_sheets')->where('offer_id', 2)->where('quarter_id', 2)->count();
        $ofertaCerradaTrimestreIII = DB::table('study_sheets')->where('offer_id', 2)->where('quarter_id', 3)->count();
        $ofertaCerradaTrimestreIV = DB::table('study_sheets')->where('offer_id', 2)->where('quarter_id', 4)->count();
        $ofertaCerradaTrimestreV = DB::table('study_sheets')->where('offer_id', 2)->where('quarter_id', 5)->count();
        $ofertaCerradaTrimestreVI = DB::table('study_sheets')->where('offer_id', 2)->where('quarter_id', 6)->count();
        $ofertaCerradaTrimestreVII = DB::table('study_sheets')->where('offer_id', 2)->where('quarter_id', 7)->count();

        // gestion contable e informacion financiera = GCIF

        // Retornar la vista con los datos
        return view('graficas.index')->with([


            //////////////////////////////////////////////////////
            // COLORES
            'colorsPrograms'=>$colorsPrograms,
            //////////////////////////////////////////////////////


            // General data
            'totalFichas' => $totalFichas,
            'namePrograms' => $namePrograms,
            'nameJourney' => $nameJourney,

            /////////////////////////////////
            // Polar radar
            'resuladoPolarRadar' => $resultsPolarRadar,

            // radarChart
            'radarChart' => $radarChart,
            'radarChartA' => $radarChartA,
            'radarChartB' => $radarChartB,

            /////////////////////////////////
            // // Stacke GCFI
            // 'fichasMananaGCIF' => $fichasMananaGCIF,
            // 'fichasTardeGCIF' => $fichasTardeGCIF,
            // 'fichasNocheGCIF' => $fichasNocheGCIF,
            // 'fichasFinSemanaGCIF' => $fichasFinSemanaGCIF,

            // Stacked GE
            'fichasMananaGE' => $fichasMananaGE,
            'fichasTardeGE' => $fichasTardeGE,
            'fichasNocheGE' => $fichasNocheGE,
            'fichasFinSemanaGE' => $fichasFinSemanaGE,

            // Stacked RH
            'fichasMananaRH' => $fichasMananaRH,
            'fichasTardeRH' => $fichasTardeRH,
            'fichasNocheRH' => $fichasNocheRH,
            'fichasFinSemanaRH' => $fichasFinSemanaRH,

            // Stacked COC
            'fichasMananaCOC' => $fichasMananaCOC,
            'fichasTardeCOC' => $fichasTardeCOC,
            'fichasNocheCOC' => $fichasNocheCOC,
            'fichasFinSemanaCOC' => $fichasFinSemanaCOC,

            // Stacked ADSO
            'fichasMananaADSO' => $fichasMananaADSO,
            'fichasTardeADSO' => $fichasTardeADSO,
            'fichasNocheADSO' => $fichasNocheADSO,
            'fichasFinSemanaADSO' => $fichasFinSemanaADSO,

            /////////////////////////////////
            // Bar chart Border Radius

            ////////////////////////////////////
            'stackedBarChart' => $stackedBarChart,
            //////////////////////////////////////

            // OFERTA ABIERTA

            'ofertaAbiertaTrimestreI' => $ofertaAbiertaTrimestreI,
            'ofertaAbiertaTrimestreII' => $ofertaAbiertaTrimestreII,
            'ofertaAbiertaTrimestreIII' => $ofertaAbiertaTrimestreIII,
            'ofertaAbiertaTrimestreIV' => $ofertaAbiertaTrimestreIV,
            'ofertaAbiertaTrimestreV' => $ofertaAbiertaTrimestreV,
            'ofertaAbiertaTrimsetreVI' => $ofertaAbiertaTrimestreVI,
            'ofertaAbiertaTrimestreVII' => $ofertaAbiertaTrimestreVII,

            // OFERTA CERRADA

            'ofertaCerradaTrimestreI' => $ofertaCerradaTrimestreI,
            'ofertaCerradaTrimestreII' => $ofertaCerradaTrimestreII,
            'ofertaCerradaTrimestreIII' => $ofertaCerradaTrimestreIII,
            'ofertaCerradaTrimestreIV' => $ofertaCerradaTrimestreIV,
            'ofertaCerradaTrimestreV' => $ofertaCerradaTrimestreV,
            'ofertaCerradaTrimestreVI' => $ofertaCerradaTrimestreVI,
            'ofertaCerradaTrimestreVII' => $ofertaCerradaTrimestreVII

        ]);
    }
}
