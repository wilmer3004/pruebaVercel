<?php

namespace App\Http\Controllers;

use App\Models\Bloque;
use App\Models\Jornada;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BloqueController extends Controller
{
    public function index()
    {
        $totalbloques = Bloque::count('id');
        $totalbloquesDeshabilitados = Bloque::where('state',false)->count();
        $totalbloquesHabilitados = Bloque::where('state',true)->count();
        $jornadas = Jornada::all();

        return view('bloques.index', compact('totalbloques','totalbloquesDeshabilitados','totalbloquesHabilitados', 'jornadas'));
    }

    // TABLE
    public function listar()
    {
        $j = [];

        try {
            $bloques = Bloque::select(
                'blocks.id as id',
                'days.name as jornada',
                'blocks.time_start as hora_inicio',
                'blocks.time_end as hora_fin',
                'blocks.state as state'
            )
                ->join('days', 'days.id', '=', 'blocks.day_id')
                ->get();
            $j['success'] = true;
            $j['data'] = $bloques;
            $j['message'] = 'Consulta exitosa';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function create()
    {
        //
    }

    // CREATE - STORE
    public function store(Request $request)
    {
    $request->validate([
            'jornada' => 'required',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i',
        ]);

        // Extraer los minutos
        list($horaInicio, $minutosInicio) = explode(':', $request->hora_inicio);
        list($horaFin, $minutosFin) = explode(':', $request->hora_fin);
        // Validación personalizada para los minutos
        $validator = Validator::make($request->all(), [
            'hora_inicio' => [
                function ($attribute, $value, $fail) use ($request,$horaInicio,$minutosInicio) {
                    if ($minutosInicio!== '00') {
                        $horaCompleta = Carbon::createFromTimeString($request->hora_inicio)->format('g:i A');
                        $horaSinAMPM = Carbon::createFromTimeString($request->hora_inicio)->format('g');
                        $amOpm = Carbon::createFromTimeString($request->hora_inicio)->format('A');
                        $fail("La hora de inicio {$horaCompleta} no es válida; debería ser {$horaSinAMPM}:00 $amOpm.");

                    }
                },
            ],
            'hora_fin' => [
                function ($attribute, $value, $fail) use ($request,$horaFin, $minutosFin) { // Asegúrate de pasar las variables aquí
                    if ($minutosFin!== '59') {
                        $horaCompleta = Carbon::createFromTimeString($request->hora_fin)->format('g:i A');
                        $horaSinAMPM = Carbon::createFromTimeString($request->hora_fin)->format('g');
                        $amOpm = Carbon::createFromTimeString($request->hora_fin)->format('A');
                        $fail("La hora fin {$horaCompleta} no es válida; debería ser {$horaSinAMPM}:59 $amOpm.");
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(), // Retorna solo el primer error encontrado
                'code' => 500
            ]);
        }

        try {
            $bloques = Bloque::create([
                'day_id' => $request->input('jornada'),
                'time_start' => $request->input('hora_inicio'),
                'time_end' => $request->input('hora_fin'),
            ]);

            Log::info('Se creó el bloque exitosamente');
            return response()->json([
                'success' => true,
                'message' => 'Se creó el bloque exitosamente',
                'code' => 200,
                'url' => route('bloques.index'),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'code' => 500
            ]);
        }
    }


    public function show(Bloque $bloque)
    {
        //
    }

    // EDIT
    public function edit($id)
    {
        $j = [];

        try {
            $bloques = DB::table('blocks')
                ->select(
                    'blocks.id as id',
                    'days.name as jornada',
                    'blocks.day_id as jornadaId',
                    'blocks.time_start as hora_inicio',
                    'blocks.time_end as hora_fin'
                )
                ->join('days', 'days.id', '=', 'blocks.day_id')
                ->where('blocks.id', $id)
                ->get();

            $j['success'] = true;
            $j['data'] = $bloques;
            $j['message'] = 'Consulta exitosa';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    // UPDATE
    public function update(Request $request)
    {
        $request->validate([
            'jornada' => 'required',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
        ]);
        // dd($request->hora_inicio);

        // Extraer los minutos
        list($horaInicio, $minutosInicio) = explode(':', $request->hora_inicio);
        list($horaFin, $minutosFin) = explode(':', $request->hora_fin);
        // Validación personalizada para los minutos
        $validator = Validator::make($request->all(), [
            'hora_inicio' => [
                function ($attribute, $value, $fail) use ($request,$horaInicio,$minutosInicio) {
                    if ($minutosInicio!== '00') {
                        $horaCompleta = Carbon::createFromTimeString($request->hora_inicio)->format('g:i A');
                        $horaSinAMPM = Carbon::createFromTimeString($request->hora_inicio)->format('g');
                        $amOpm = Carbon::createFromTimeString($request->hora_inicio)->format('A');
                        $fail("La hora de inicio {$horaCompleta} no es válida; debería ser {$horaSinAMPM}:00 $amOpm.");

                    }
                },
            ],
            'hora_fin' => [
                function ($attribute, $value, $fail) use ($request,$horaFin, $minutosFin) { // Asegúrate de pasar las variables aquí
                    if ($minutosFin!== '59') {
                        $horaCompleta = Carbon::createFromTimeString($request->hora_fin)->format('g:i A');
                        $horaSinAMPM = Carbon::createFromTimeString($request->hora_fin)->format('g');
                        $amOpm = Carbon::createFromTimeString($request->hora_fin)->format('A');
                        $fail("La hora fin {$horaCompleta} no es válida; debería ser {$horaSinAMPM}:59 $amOpm.");
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(), // Retorna solo el primer error encontrado
                'code' => 500
            ]);
        }

        try {
            $url = route('bloques.index');

            $bloques = Bloque::findOrFail($request->input('id'));

            $bloques->update([
                'day_id' => $request->input('jornada'),
                'time_start' => $request->input('hora_inicio'),
                'time_end' => $request->input('hora_fin'),
            ]);

            Alert::toast('Se editó el bloque exitosamente', 'info');

            $j['success'] = true;
            $j['message'] = 'Bloque actualizado';
            $j['url'] = $url;
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function change($id)
    {
        $j = [];

        try {
            $lastState = DB::table('blocks')->select('state')->where('id', $id)->first();

            if (!$lastState->state) {
                DB::table('blocks')->where('id', $id)->update(['state' => true]);
            } else {
                DB::table('blocks')->where('id', $id)->update(['state' => false]);
            }

            $j['success'] = true;
            $j['title'] = "Cambio Exitoso";
            $j['message'] = "El bloque ha sido " . ($lastState->state ? "deshabilitado" : "habilitado");
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    // Delete
    public function destroy($id)
    {
        $j = [];

        try {
            $bloque = Bloque::findOrFail($id);

            $bloque->delete();
            Alert::toast('Se eliminó el bloque', 'warning');
            $j['success'] = true;
            $j['message'] = 'bloque eliminado';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }
}
