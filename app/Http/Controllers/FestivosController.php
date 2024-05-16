<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Holiday;
use App\Models\Trimestre;
use App\Models\TrimestreAnio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FestivosController extends Controller
{
    // Index information
    public function index()
    {
        $totalHolidays = Holiday::count('id');
        return view('festivos.index', compact('totalHolidays'));
    }

    // List
    public function listar()
    {
        $j = [];
        try {
            // holidays count
            $holidays = Holiday::select('id', 'date')->get();
            $j['success'] = true;
            $j['message'] = 'Consulta exitosa';
            $j['data'] = $holidays;
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    // Create method
    public function store(Request $request)
    {
        $j = [];
        try {

            /* VALIDATIONS */

            // EXIST THIS DATE?
            $existDate = Holiday::where('date', $request->input('date'))->exists();

            /* CONDITIONALS */
            if ($existDate) {
                /* ERROR MESSAGE */
                $j['icon'] = 'error';
                $j['title'] = 'Ya Existe Fecha';
                $j['message'] = 'La fecha ' . $request->input('date') . ' ya esta registrada.';
                $j['success'] = false;
            } else {
                // DATE HOLIDAY YEAR
                $yearDateHoliday = intval(substr($request->input('date'), 0, 4));
                // IS THERE YEAR QUARTER?
                $existQuarterYear = TrimestreAnio::where('year', $yearDateHoliday)->exists();

                if ($existQuarterYear) {
                    // IS THERE ANY PROGRAMMING THAT HAS SKIPPED THIS DATE?
                    // 1. WHAT YEAR QUARTER IS THIS DATE? ->
                    $holidayDateYearQuarter = TrimestreAnio::where('start_date', '<=', $request->input('date'))
                        ->where('finish_date', '>=', $request->input('date'))
                        ->value('id');
                    if (!$holidayDateYearQuarter) { // == null
                        // STORE
                        Holiday::create(['date' => $request->input('date')]);
                        // SUCCESS MESSAGE
                        $j['icon'] = 'success';
                        $j['title'] = 'Fecha Registrada';
                        $j['message'] = 'La fecha ' . $request->input('date') . ' a sido registrada exitosamente.';
                        $j['success'] = true;
                    } else { // != null
                        // 2. YEAR QUARTER DATES
                        $yearQuarterDates = TrimestreAnio::select('start_date', 'finish_date')->where('id', $holidayDateYearQuarter)->get();

                        // 3. IS THERE YEAR QUARTER DATES IN EVENTS?
                        $startDate = $yearQuarterDates->value('start_date');
                        $endDate = $yearQuarterDates->value('finish_date');
                        $rangeDatesEvents = Evento::where(function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('start', [$startDate, $endDate])
                                ->orWhereBetween('end', [$startDate, $endDate]);
                        })
                            ->exists();

                        if ($rangeDatesEvents) {
                            $j['icon'] = 'error';
                            $j['title'] = 'Registro Interrumpido';
                            $j['message'] = 'Ya existen programaciones que se verian influidas por esta fecha festiva, si la desea registrar deberar eliminar la programación.';
                            $j['success'] = false;
                        } else {
                            // STORE
                            Holiday::create(['date' => $request->input('date')]);
                            // SUCCESS MESSAGE
                            $j['icon'] = 'success';
                            $j['title'] = 'Fecha Registrada';
                            $j['message'] = 'La fecha ' . $request->input('date') . ' a sido registrada exitosamente.';
                            $j['success'] = true;
                        }
                    }
                } else {
                    // STORE
                    Holiday::create(['date' => $request->input('date')]);
                    // SUCCESS MESSAGE
                    $j['icon'] = 'success';
                    $j['title'] = 'Fecha Registrada';
                    $j['message'] = 'La fecha ' . $request->input('date') . ' a sido registrada exitosamente.';
                    $j['success'] = true;
                }
            }
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }
        return response()->json($j);
    }

    // Delete method
    public function destroy($id)
    {
        $j = [];

        try {

            // DATA
            $holidayDate = DB::table('holidays')->where('id', $id)->value('date');
            $holiday = Holiday::find($id);
            // DATE HOLIDAY YEAR
            $yearDateHoliday = intval(substr($holiday->value('date'), 0, 4));
            // IS THERE YEAR QUARTER?
            $existQuarterYear = TrimestreAnio::where('year', $yearDateHoliday)->exists();

            if ($existQuarterYear) {
                // 1. WHAT YEAR QUARTER IS THIS DATE? ->
                $holidayDateYearQuarter = TrimestreAnio::where('start_date', '<=', $holidayDate)
                    ->where('finish_date', '>=', $holidayDate)
                    ->value('id');

                if (!$holidayDateYearQuarter) {
                    /* DELETE DATE */
                    Holiday::where('id', $id)->delete();
                    /* SUCCESS MESSAGE */
                    $j['icon'] = 'success';
                    $j['title'] = 'Fecha Eliminada Exitosamente';
                    $j['message'] = 'La fecha se ha eliminada exitosamente.';
                    $j['success'] = true;
                } else {
                    // 2. YEAR QUARTER DATES
                    $yearQuarterDates = TrimestreAnio::select('start_date', 'finish_date')->where('id', $holidayDateYearQuarter)->get();

                    // 3. IS THERE YEAR QUARTER DATES IN EVENTS?
                    $startDate = $yearQuarterDates->value('start_date');
                    $endDate = $yearQuarterDates->value('finish_date');
                    $rangeDatesEvents = Evento::where(function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('start', [$startDate, $endDate])
                            ->orWhereBetween('end', [$startDate, $endDate]);
                    })
                        ->exists();

                    if ($rangeDatesEvents) {
                        /* ERROR MESSAGE */
                        $j['icon'] = 'error';
                        $j['title'] = 'Eliminación Interrumpido';
                        $j['message'] = 'Ya existen programaciones que se verian influidas por esta fecha festiva, si la desea eliminar deberar eliminar la programación.';
                        $j['success'] = false;
                    } else {
                        /* DELETE DATE */
                        Holiday::where('id', $id)->delete();
                        /* SUCCESS MESSAGE */
                        $j['icon'] = 'success';
                        $j['title'] = 'Fecha Eliminada Exitosamente';
                        $j['message'] = 'La fecha se ha eliminada exitosamente.';
                        $j['success'] = true;
                    }
                }
            } else {
                /* DELETE DATE */
                Holiday::where('id', $id)->delete();
                /* SUCCESS MESSAGE */
                $j['icon'] = 'success';
                $j['title'] = 'Fecha Eliminada Exitosamente';
                $j['message'] = 'La fecha se ha eliminada exitosamente.';
                $j['success'] = true;
            }
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }
}
