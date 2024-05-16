<?php

namespace App\Http\Controllers;

use App\Models\Condicion;
use App\Models\CondicionHora;
use App\Models\Contrato;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CondicionHoraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalcondicionhoras = CondicionHora::count('id');
        $contratos = Contrato::where('state', true)->get();
        $condiciones = Condicion::where('state', true)->get();

        return view('condicioneshoras.index', compact('totalcondicionhoras', 'contratos', 'condiciones'));
    }

    public function listar()
    {
        $j = [];

        try {
            $condicionesh = CondicionHora::select(
                'conditions_hours.id as id',
                'contracts.name as contract',
                'conditions.name as condition',
                'conditions_hours.percentage as percentage',
            )
                ->join('contracts', 'conditions_hours.contract_id', '=', 'contracts.id')
                ->join('conditions', 'conditions_hours.condition_id', '=', 'conditions.id')
                ->get();

            $j['success'] = true;
            $j['data'] = $condicionesh;
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $j = [];

        $request->validate([
            'contrato' => 'required',
            'condicion' => 'required',
            'porcentaje' => 'required|min:1|max:3|regex:/^\d{1,3}$/'
        ]);

        $url = route('condicioneshoras.index');

        $j = [];


        try {

            /* VALIDATIONS */

            /* EXIST THIS HOUR CONDITION? */
            $existHourCondition = CondicionHora::where([
                ['contract_id', '=', $request->input('contrato')],
                ['condition_id', '=', $request->input('condicion')]
            ])
                ->exists();

            /* CONDITION */
            if ($existHourCondition) {

                /* MESSAGE */
                $j['icon'] = 'error';
                $j['title'] = 'Condición Hora Existente';
                $j['message'] = 'La condicion hora que se desea registrar actualmente ya existe en el sistema.';
                $j['success'] = false;
                $j['code'] = 200;
            } else {

                /* CREATE CONDITION */
                CondicionHora::create([
                    'contract_id' => $request->input('contrato'),
                    'condition_id' => $request->input('condicion'),
                    'percentage' => $request->input('porcentaje')
                ]);

                /* MESSAGE */
                Alert::toast('Se creó la condicion hora exitosamente', 'success');
                $j['icon'] = 'success';
                $j['title'] = 'Condición Creada Exitosamente';
                $j['message'] = 'Se creó la condición hora exitosamente';
                $j['code'] = 200;
                $j['success'] = true;
            }
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    /**
     * Display the specified resource.
     */
    public function show(CondicionHora $condicionHora)
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
            $condicionesh = DB::table('conditions_hours')
                ->select(
                    'conditions_hours.id as id',
                    'conditions_hours.contract_id as contractId',
                    'contracts.name as contract',
                    'conditions.name as condition',
                    'conditions_hours.condition_id as conditionId',
                    'conditions_hours.percentage as percentage'
                )
                ->join('contracts', 'contracts.id', '=', 'conditions_hours.contract_id')
                ->join('conditions', 'conditions.id', '=', 'conditions_hours.condition_id')
                ->where('conditions_hours.id', $id)
                ->get();

            $j['success'] = true;
            $j['data'] = $condicionesh;
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
            'contrato' => 'required',
            'condicion' => 'required',
            'porcentajeE' => 'required|min:1|max:3|regex:/^\d{1,3}$/'
        ]);

        try {
            $url = route('condicioneshoras.index');

            /* ALL DATA ABOUT QUARTER YEAR */
            $condicionesh = CondicionHora::select('contract_id', 'condition_id', 'percentage')
                ->where('id', $request->input('id'))
                ->first();

            /* QUERY */
            $existHourCondition = CondicionHora::where([
                ['contract_id', '=', $request->input('contrato')],
                ['condition_id', '=', $request->input('condicion')]
            ])->exists();

            /* CONDITIONALS */
            if ($existHourCondition && ($condicionesh->contract_id != $request->input('contrato') || $condicionesh->condition_id != $request->input('condicion'))) {
                $j['success'] = false;
                $j['icon'] = 'error';
                $j['title'] = 'Edición Bloqueada';
                $j['message'] = 'Actualmente ya existe la condición hora';
                $j['url'] = $url;
                $j['code'] = 200;
            } else {
                CondicionHora::where('id', $request->input('id'))
                    ->update([
                        'contract_id' => $request->input('contrato'),
                        'condition_id' => $request->input('condicion'),
                        'percentage' => $request->input('porcentajeE')
                    ]);

                Alert::toast('Se editó la condición hora exitosamente', 'info');

                $j['success'] = true;
                $j['icon'] = 'success';
                $j['title'] = 'Edición Exitosa';
                $j['message'] = 'Condición hora actualizada';
                $j['url'] = $url;
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
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $j = [];

        try {
            $condicionesh = CondicionHora::findOrFail($id);

            $condicionesh->delete();
            Alert::toast('Se eliminó la condición hora', 'warning');
            $j['success'] = true;
            $j['message'] = 'Condición hora eliminada';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }
}
