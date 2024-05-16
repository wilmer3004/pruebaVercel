<?php

namespace App\Http\Controllers;

use App\Models\Ficha;
use App\Models\Jornada;
use App\Models\Evento;
use App\Models\Oferta;
use App\Models\Programa;
use App\Models\TipoPrograma;
use App\Models\Trimestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class FichaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalfichas = Ficha::count('id');
        $totalfichasDeshabilitadas= Ficha::where('state','=','inactivo')->count();
        $totalfichasHabilitadas= Ficha::where('state','=','activo')->count();
        $fichas = Ficha::all();
        $programas = DB::table('programs as p')
            ->join('program_type as pt', 'p.program_type_id', '=', 'pt.id')
            ->rightJoin('coordinations as co','co.id','=','p.coordination_id')
            ->where('co.state',true)
            ->where('pt.state', true)
            ->where('p.state', 'activo')
            ->select('p.id', 'p.name', 'p.description', 'p.coordination_id', 'p.duration')
            ->get();
        $programasEdit = Programa::all();
        $jornadas = Jornada::where('state', 'activo')->get();
        $ofertas = Oferta::where('state', 'activo')->get();
        $ofertasEdit = Oferta::all();
        $trimestres = Trimestre::all();

        return view('fichas.index', compact('fichas', 'programasEdit', 'ofertasEdit', 'totalfichas','totalfichasDeshabilitadas','totalfichasHabilitadas', 'programas', 'jornadas', 'ofertas', 'trimestres'));
    }

    public function listar()
    {
        $j = [];

        try {
            $fichas = Ficha::select(
                'study_sheets.id as id',
                'study_sheets.number as numficha',
                'study_sheets.num as num',
                'study_sheets.num_trainnies as numaprendices',
                'programs.name as programa',
                'days.name as jornada',
                'offers.name as oferta',
                'quarters.name as trimestre',
                'study_sheets.state as state'
            )
                ->join('programs', 'programs.id', '=', 'study_sheets.program_id')
                ->join('days', 'days.id', '=', 'study_sheets.day_id')
                ->join('offers', 'offers.id', '=', 'study_sheets.offer_id')
                ->join('quarters', 'quarters.id', '=', 'study_sheets.quarter_id')
                ->get();

            $j['success'] = true;
            $j['data'] = $fichas;
            $j['message'] = 'Consulta exitosa';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    // Check if there are events for the tokens to be joined
    public function listarEvents(Request $request)
    {
        $j = [];

        try {

            $existsEvents = DB::table('events')
                ->select('s.number as number', 's.num as num')
                ->join('study_sheets as s', 'events.study_sheet_id', '=', 's.id')
                ->where('events.study_sheet_id', $request->input('ficha1'))
                ->where('events.start', '>=', now()) // Asegúrate de ajustar el nombre de la columna de fecha
                ->groupBy('number', 'num')
                ->first();



            if ($existsEvents != null) {
                $j = [
                    'success' => true,
                    'message' => $existsEvents->num != null ? "Ahi algunas programaciones para la ficha $existsEvents->number - $existsEvents->num" : "Ahi algunas programaciones para la ficha $existsEvents->number ",
                    'code' => 201
                ];
            } else {
                $j = [
                    'success' => true,
                    'message' => "No ahi programaciones presentes para la ficha",
                    'code' => 200
                ];
            }
        } catch (\Throwable $th) {
            $j = [
                'success' => false,
                'message' => $th->getMessage(),
                'code' => 500
            ];
        }

        return response()->json($j);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $j = [];
        $request->validate([
            'numero' => 'required|min:5|max:14|regex:/^\d{5,14}$/',
            'programa' => 'required',
            'aprendices' => 'required|min:1|max:3|regex:/^\d{1,3}$/',
            'oferta' => 'required',
            'jornada' => 'required',
            'trimestre' => 'required',
            'inicio' => 'required',
        ]);
        try {
            $url = route('fichas.index');

            $studySheet = DB::table('study_sheets')
                ->where('number', $request->input('numero'))
                ->whereNotNull('num')
                ->orderBy('num', 'desc')
                ->first();

            $fichasExist = DB::table('study_sheets')
                ->where('number', $request->input('numero'))
                ->where('num', $request->input('num'))
                ->exists();


            $numFicha = $studySheet ? max(0, $studySheet->num) : 0;

            if ($fichasExist) {
                $j = [
                    'success' => false,
                    'message' => 'La ficha ya existe',
                    'code' => 505
                ];
            } elseif ($studySheet == null && $request->input('num') != 1 && $request->input('num') != '') {
                $j = [
                    'success' => false,
                    'message' => "Estas creando una ficha dividida, debe iniciar en 1",
                    'code' => 505
                ];
            } elseif ($request->input('num') > 1 && (($numFicha + 1) != $request->input('num'))) {
                $j = [
                    'success' => false,
                    'message' => "El número de la ficha dividida debe ir de forma incremental, actualmente va en: {$request->input('numero')} - $numFicha",
                    'code' => 505
                ];
            } elseif ($numFicha >= 4) {
                $j = [
                    'success' => false,
                    'message' => "El número de fichas divididas alcanzó su tope, solo se pueden tener 4 fichas divididas por ficha",
                    'code' => 505
                ];
            } else {
                $programa = Programa::find($request->programa);
                $tipoprograma = TipoPrograma::where('name', 'tecnologo')->first();

                $end_lective = \Carbon\Carbon::parse($request->inicio)->addMonths(($programa->program_type_id == $tipoprograma->id) ? 18 : 6)->format('Y/m/d');

                $fichaData = [
                    'number' => $request->input('numero'),
                    'num' => $request->input('num'),
                    'program_id' => $request->input('programa'),
                    'num_trainnies' => $request->input('aprendices'),
                    'day_id' => $request->input('jornada'),
                    'offer_id' => $request->input('oferta'),
                    'quarter_id' => $request->input('trimestre'),
                    'start_lective' => $request->input('inicio'),
                    'end_lective' => $end_lective,
                ];

                Ficha::create($fichaData);

                Alert::toast("Se creó la ficha {$request->numero}-{$request->num} correctamente", 'success');

                $j = [
                    'success' => true,
                    'message' => 'Se creó la ficha exitosamente',
                    'code' => 200, 'url' => $url
                ];
            }
        } catch (\Throwable $th) {
            $j = [
                'success' => false,
                'message' => $th->getMessage(),
                'code' => 500
            ];
        }


        return response()->json($j);
    }

    /**s
     * Display the specified resource.
     */
    public function show(Ficha $ficha)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $j = [];

        try {
            $fichas = Ficha::select(
                'study_sheets.id as id',
                'study_sheets.number as number',
                'study_sheets.num as num',
                'study_sheets.program_id as programId',
                'study_sheets.num_trainnies as trainnies',
                'study_sheets.day_id as dayId',
                'study_sheets.offer_id as offerId',
                'study_sheets.quarter_id as quarterId',
                'study_sheets.start_lective as start',
                'study_sheets.state as estado',
                'programs.name as program',
                'days.name as day',
                'offers.name as offer',
                'quarters.name as quarter'
            )
                ->join('programs', 'programs.id', '=', 'study_sheets.program_id')
                ->join('days', 'days.id', '=', 'study_sheets.day_id')
                ->join('offers', 'offers.id', '=', 'study_sheets.offer_id')
                ->join('quarters', 'quarters.id', '=', 'study_sheets.quarter_id')
                ->where('study_sheets.id', $id)
                ->get();

            $j['success'] = true;
            $j['data'] = $fichas;
            $j['message'] = 'Consulta exitosa';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'numero' => 'required|min:1|max:10|regex:/^[a-zA-ZÀ-ÿ\s0-9]{1,10}$/',
            'programa' => 'required',
            'aprendices' => 'required|min:1|max:3|regex:/^\d{1,3}$/',
            'oferta' => 'required',
            'jornada' => 'required',
            'trimestre' => 'required',
            'inicio' => 'required',
        ]);

        try {
            $url = route('fichas.index');

            // Verificar que el programa no este deshabilitado
            $programa = Programa::where('id', '=', $request->programa)->where('state', '=', 'activo')->first();
            $namePrograma = Programa::where('id', '=', $request->programa)->first();

            // Verificar si la oferta esta deshabilitada
            $oferta = Oferta::where('id', '=', $request->input('oferta'))->where('state', '=', 'activo')->first();
            $nameOferta = Oferta::where('id', '=', $request->input('oferta'))->first();

            if ($programa == null) {
                $j['success'] = false;
                $j['message'] = "El programa $namePrograma->name esta deshabilitado";
                $j['code'] = 505;

                return response()->json($j);
            }

            if ($oferta == null) {
                $j['success'] = false;
                $j['message'] = "La oferta $nameOferta->name esta deshabilitado";
                $j['code'] = 505;

                return response()->json($j);
            }

            $tipoprograma = TipoPrograma::where('name', '=', 'tecnologo')->first();

            if ($programa->program_type_id == $tipoprograma->id) {
                $end_lective = \Carbon\Carbon::parse($request->inicio)->addMonths(18)->format('Y/m/d');
            } else {
                $end_lective = \Carbon\Carbon::parse($request->inicio)->addMonths(6)->format('Y/m/d');
            }

            $ficha = Ficha::findOrFail($request->input('id'));



            if (Ficha::where('number', $request->input('numero'))
                ->where('num', $request->input('num'))
                ->where('id', '!=', $ficha->id)
                ->exists()
            ) {
                $j = [
                    'success' => false,
                    'message' => 'La ficha ya existe',
                    'code' => 400,
                ];
            } else if (intval($request->input('num')) == 0 && $request->input('num') != null) {
                $j['success'] = false;
                $j['message'] = "El número de la ficha dividida es invalido";
                $j['code'] = 505;
            } else if ($ficha->num != intval($request->input('num')) || $ficha->number != intval($request->input('numero'))) {
                $j['success'] = false;
                $j['message'] = "El numero de la ficha no se puede editar";
                $j['code'] = 505;
            } else if ($ficha->num > 4) {
                $j['success'] = false;
                $j['message'] = "El número de fichas divididas alcanzo su tope, solo se pueden tener 4 fichas divididas por ficha";
                $j['code'] = 505;
            } else {
                $ficha->update([
                    'number' => $request->input('numero'),
                    'num' => $request->input('num'),
                    'program_id' => $request->input('programa'),
                    'num_trainnies' => $request->input('aprendices'),
                    'day_id' => $request->input('jornada'),
                    'offer_id' => $request->input('oferta'),
                    'quarter_id' => $request->input('trimestre'),
                    'start_lective' => $request->input('inicio'),
                    'end_lective' => $end_lective
                ]);
                Alert::toast('Se editó la ficha ' . $request->nombre . $request->num . ' exitosamente', 'info');
                $j = [
                    'success' => true,
                    'message' => 'Ficha actualizada',
                    'url' => $url,
                    'code' => 200,
                ];
            }
        } catch (\Throwable $th) {
            $j = [
                'success' => false,
                'message' => $th->getMessage(),
                'code' => 500,
            ];
        }
        return response()->json($j);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $j = [];

        try {
            $fichas = Ficha::findOrFail($id);

            if (trim($fichas->state) === 'activo') {
                $fichas->update([
                    'state' => 'inactivo'
                ]);
                Alert::toast('Se deshabilito la ficha', 'warning');
                $j['title'] = 'Ficha deshabilita';
                $j['success'] = true;
                $j['message'] = 'Ficha deshabilita para su uso';
                $j['code'] = 200;
            } else {
                $fichas->update([
                    'state' => 'activo'
                ]);
                Alert::toast('Se habilito la ficha', 'warning');
                $j['title'] = 'Ficha habilitada';
                $j['success'] = true;
                $j['message'] = 'Ficha habilitada para su uso';
                $j['code'] = 200;
            }
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }


    /**
     * Function for joining tokens
     */
    public function joinTiles(Request $request)
    {
        $j = [];

        $request->validate([
            'ficha1' => 'required',
            'ficha2' => 'required',
        ]);

        try {

            Evento::where('study_sheet_id', $request->input('ficha1'))->where('start', '>=', now())->delete();

            $ficha1 = Ficha::findOrFail($request->input('ficha1'));

            $ficha2 = Ficha::findOrFail($request->input('ficha2'));


            $numberApprentices = $ficha1->num_trainnies + $ficha2->num_trainnies;
            if (trim($ficha1->state) == 'activo') {
                $ficha1->update([
                    'state' => 'inactivo_union',
                ]);

                $ficha2->update([
                    'num_trainnies' => $numberApprentices
                ]);
            } else {
                $ficha2->update([
                    'num_trainnies' => $numberApprentices
                ]);
            }

            $ficha2 = Ficha::select(
                'study_sheets.id as id',
                'study_sheets.number as number',
                'study_sheets.num as num',
                'study_sheets.program_id as programId',
                'study_sheets.num_trainnies as trainnies',
                'study_sheets.day_id as dayId',
                'study_sheets.offer_id as offerId',
                'study_sheets.quarter_id as quarterId',
                'study_sheets.start_lective as start',
                'study_sheets.state as estado',
                'programs.name as program',
                'days.name as day',
                'offers.name as offer',
                'quarters.name as quarter'
            )
                ->join('programs', 'programs.id', '=', 'study_sheets.program_id')
                ->join('days', 'days.id', '=', 'study_sheets.day_id')
                ->join('offers', 'offers.id', '=', 'study_sheets.offer_id')
                ->join('quarters', 'quarters.id', '=', 'study_sheets.quarter_id')
                ->where('study_sheets.id', $request->input('ficha2'))
                ->get();

            $j = [
                'success' => true,
                'message' => $ficha2,
                'code' => 200,
            ];
        } catch (\Throwable $th) {
            $j = [
                'success' => false,
                'message' => $th->getMessage(),
                'code' => 500,
            ];
        }
        return response()->json($j);
    }

    public function delete($id)
    {
        $j = [];

        try {
            $existEvents = DB::table('events')->where('study_sheet_id', $id)->exists();

            if ($existEvents) {
                $j['success'] = false;
                $j['message'] = 'No se pudo eliminar ya que la ficha esta presente en algunos eventos';
                $j['code'] = 200;
            } else {
                $ficha = Ficha::findOrFail($id);
                $ficha->delete();


                Alert::toast('Se eliminó la ficha', 'warning');
                $j['title'] = 'Ficha eliminada';
                $j['success'] = true;
                $j['message'] = 'Se elimino la ficha correctamente';
                $j['code'] = 200;
            }
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }
}
