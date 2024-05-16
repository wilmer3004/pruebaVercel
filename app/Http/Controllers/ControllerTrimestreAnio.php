<?php

namespace App\Http\Controllers;

use App\Models\TrimestreAnio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class ControllerTrimestreAnio extends Controller
{
    //

    public function index()
    {
        // Variable para contar el total de trimestres del año
        $totalfechast = TrimestreAnio::count('id');

        // Redireccionar a la vista preferida
        return view('trimestresanio.index', compact('totalfechast'));
    }

    public function listar()
    {
        $j = [];

        try {
            $trimestreanio = DB::table('year_quarters')
                ->select('id', 'quarter', 'start_date', 'finish_date', 'state')
                ->orderBy('start_date', 'DESC')
                ->get();

            $trimestreanioArray = $trimestreanio->toArray();

            $trimestreanioAgrupado = [];

            for ($i = 0; $i < count($trimestreanio); $i += 4) {
                $trimestreanioAgrupado[] = array_slice($trimestreanioArray, $i, 4);
            }

            $responseTrimestre = [];

            foreach ($trimestreanioAgrupado as $trimestreanio2) {
                foreach ($trimestreanio2 as $trimestre) {
                    $responseTrimestre[] = $trimestre;
                }
            }

            $j['success'] = true;
            $j['message'] = 'Consulta exitosa';
            $j['data'] = $responseTrimestre;
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

    public function store(Request $request)
    {
        $j = [];

        $request->validate([
            'year' => 'required',
            'quarter' => 'required',
            'start' => 'required',
            'end' => 'required'
        ]);

        try {
            $url = route('fechasanio.index');
            $trimestreanio = DB::table('year_quarters')->where('quarter', $request->input('quarter'))->where('year', $request->input('year'))->exists();
            // Verificar el numero en que va el trimestre en ese año
            $numTrimestre = DB::table('year_quarters')
                ->where('year', $request->input('year'))
                ->orderBy('quarter', 'desc')
                ->limit(1)
                ->value('quarter') ?? 0;

            // validacion de la fechas, que no esten registradas en otro trimestre
            $fechaS =  $request->input('start');
            $fechaF =  $request->input('end');

            $fechaSTrimestreAnio = DB::table('year_quarters')
                ->where(function ($query) use ($fechaS) {
                    $query->whereDate('start_date', '<=', $fechaS)
                        ->whereDate('finish_date', '>=', $fechaS);
                })
                ->exists();

            $fechaFTrimestreAnio = DB::table('year_quarters')
                ->where(function ($query) use ($fechaF) {
                    $query->whereDate('start_date', '<=', $fechaF)
                        ->whereDate('finish_date', '>=', $fechaF);
                })
                ->exists();

            $hayTrimestre = DB::table('year_quarters')
                ->where('year', $request->input('year'))
                ->orderBy('quarter', 'desc') // Ordenar por trimestre en orden descendente
                ->take(1) // Tomar solo el primer resultado (es decir, el trimestre más reciente)
                ->where('finish_date', '>=', $fechaS)
                ->exists();

            if ($trimestreanio) {
                $j['success'] = false;
                $j['message'] = 'El trimestre en ese año ya existe';
                $j['code'] = 505;
            } else if ($fechaSTrimestreAnio || $fechaFTrimestreAnio) {
                $j['success'] = false;
                $j['message'] = 'Las fechas del rango ya se encuentran registradas en otro trimestre.';
                $j['code'] = 505;
            } else if (($numTrimestre + 1) != $request->input('quarter')) {
                $j['success'] = false;
                if ($numTrimestre == 0) {
                    $j['message'] = 'No hay ningun trimestre registrado para este año, debe comenzar en 1';
                } else {
                    $j['message'] = "El numero del trimestre debe ir de forma consecutiva, actualmente va en $numTrimestre";
                }
                $j['code'] = 505;
            } else if ($hayTrimestre) {
                $j['success'] = false;
                $j['message'] = 'La fecha inicial no puede ser menor o igual a la fecha final del trimestre pasado';
                $j['code'] = 505;
            } else {

                /* Modificación de la fecha para que se almacene la hora sin formato y quede 00:00:00 */
                $startDate = Carbon::parse($request->start);
                $finishDate = Carbon::parse($request->end);
                $startDate->startOfDay();
                $finishDate->startOfDay();

                $trimestreanio = TrimestreAnio::create([
                    'year' => $request->year,
                    'quarter' => $request->quarter,
                    'start_date' => $request->start,
                    'finish_date' => $request->end
                ]);

                Alert::toast('Se creó el trimestre en ese año exitosamente', 'success');
                $j['success'] = true;
                $j['message'] = 'Se creó el trimestre en ese año exitosamente';
                $j['code'] = 200;
                $j['url'] = $url;
            }
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }
        return response()->json($j);
    }

    public function show(TrimestreAnio $trimestreanio)
    {
        //
    }

    public function edit($id)
    {
        $j = [];

        try {
            $fechasanio = DB::table('year_quarters')
                ->where('id', $id)
                ->get();

            $j['success'] = true;
            $j['data'] = $fechasanio;
            $j['message'] = 'Consulta exitosa';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function update(Request $request, TrimestreAnio $trimestreanio)
    {

        /* Requerimos los datos */
        $request->validate([
            'year' => 'required',
            'quarter' => 'required',
            'start' => 'required',
            'end' => 'required'
        ]);

        $j = [];

        try {

            /* Ruta para redireccionar en caso de que salga todo bien */
            $url = route('fechasanio.index');

            /* Traer todos los datos por medio del id */
            $trimestreanio = TrimestreAnio::findOrFail($request->input('id'));

            $startDate = Carbon::parse($request->input('start'))->startOfDay();
            $endDate = Carbon::parse($request->input('end'))->endOfDay();
            $startDate1 = Carbon::parse($trimestreanio->start_date)->startOfDay();
            $endDate1 = Carbon::parse($request->finish_date)->endOfDay();
            /* VALIDACIONES */

            // Validacion de quarter

            // Cuantos quarter tiene year asociadas, si tiene un total de 4 significa que no se puede agregar mas
            $numQuartersinYearA = DB::table('year_quarters')
                ->where('year', $request->input('year'))
                ->count();

            // Ya existe quarter en el año al cual se edita? Devolver un true en caso de que ese quarter ya exista dentro de year
            $existQuarter = DB::table('year_quarters')
                ->where('year', $request->input('year'))
                ->where('quarter', $request->input('quarter'))
                ->exists();

            // El quarter de year inicia en cero? Devolvera null en caso de no haber nada
            $startQuarterInCero = DB::table('year_quarters')
                ->select('quarter')
                ->orderBy('quarter', 'ASC')
                ->where('year', $request->input('year'))
                ->first();

            // Ultimo trimestre del año
            $lastQuarter = DB::table('year_quarters')
                ->select('quarter')
                ->orderBy('quarter', 'DESC')
                ->where('year', $request->input('year'))
                ->first();

            // Validacion de year las fechas no se encuentren (choquen), devuelve true si se encuentra del rango de una fecha y false si es lo contrario
            $rangeDates = DB::table('year_quarters')
                ->where('year', $request->input('year'))
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('finish_date', [$startDate, $endDate]);
                })
                ->exists();

            // Validación de fecha inicial, la fecha inicial no puede ser menor a la fecha final del trimestre que le sigue
            $startDateMainFinalLastQuarter = DB::table('year_quarters')
                ->where('year', $request->input('year')) // 2024
                ->where('quarter', '<', $request->input('quarter')) // 1 < 2
                ->where('finish_date', '>=', $startDate) // 04/01 >= 04/02
                ->exists(); // true, si es verdadero saltara la validación

            // Validacion uso del trimestre año dentro de events
            $rangeDatesEvents = DB::table('events')
                ->where(function ($query) use ($startDate1, $endDate1) {
                    $query->whereBetween('start', [$startDate1, $endDate1])
                        ->orWhereBetween('end', [$startDate1, $endDate1]);
                })
                ->exists();

            $quarterNum = intval($request->input('quarter')); // Transformar el dato a int

            /* Reutilizacion */
            function cruzeFechas($startDate, $endDate, $request, $trimestreanio, $url)
            {
                $idCFechasChoque = DB::table('year_quarters')
                    ->select('id')
                    ->where(function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('start_date', [$startDate, $endDate])
                            ->orWhereBetween('finish_date', [$startDate, $endDate]);
                    })
                    ->get();

                // Contar el numero de elementos con los cuales choca
                $numElementosIdQuarterYear = $idCFechasChoque->count();

                if ($numElementosIdQuarterYear >= 2) { // Si choca con mas de un trimestre significa que es una correción de fecha que se cruza con otras fechas del mismo año
                    return [
                        'success' => false,
                        'message' => "El trimestre editado se cruza con fechas del año " . $request->input('year'),
                        'code' => 505
                    ];
                } else {
                    // Buscar el id dentro de la consulta de $idFechasChoque, retornara un booleano
                    $idEdicionFecha = $idCFechasChoque->contains('id', $request->input('id'));

                    if (!$idEdicionFecha) { // Si se resive un false entrara al bloque y enviara el mensaje de error
                        return [
                            'success' => false,
                            'message' => "El trimestre editado se cruza con fechas del año " . $request->input('year'),
                            'code' => 505
                        ];
                    } else {
                        // Actualización del trimestre
                        $trimestreanio->update([
                            'year' => $request->input('year'),
                            'quarter' => $request->input('quarter'),
                            'start_date' => $request->input('start'),
                            'finish_date' => $request->input('end')
                        ]);

                        // json para el SwalFire
                        return [
                            'success' => true,
                            'message' => 'Se edito el trimestre exitosamente',
                            'code' => 200,
                            'url' => $url
                        ];
                    }
                }
            }
            /* Condicionales de validaciones */
            if ($rangeDatesEvents) {
                $j['success'] = false;
                $j['message'] = "Este trimestre actualmente esta siendo usado en la programación, no se puede editar o borrar. Borre las programaciones relacionadas al mismo para su edición";
                $j['code'] = 505;
            } else if ($numQuartersinYearA === 4) { // Validacion de un año alcanzo el numero de trimestres maximos que puede tener
                $j['success'] = false;
                $j['message'] = "El año " . $request->input('year') . " ya cuenta con cuentro trimestres registrados";
                $j['code'] = 505;
            } else if ($existQuarter && (intval(($request->input('quarter'))) != $trimestreanio->quarter )) { // Validación de trimestre existente
                $j['success'] = false;
                $j['message'] = "En el año " . $request->input('year') . " ya existe un trimestre #" . $request->input('quarter') . " registrado";
                $j['code'] = 505;
            } else if (!$startQuarterInCero && $quarterNum !== 1) { // Validación año sin trimestres asociados
                $j['success'] = false;
                $j['message'] = "En el año " . $request->input('year') . " no existe un trimestre registrado aun, por lo que debera empezar el trimestre con 1";
                $j['code'] = 505;
            } else if ($startDateMainFinalLastQuarter) {
                $j['success'] = false;
                $j['message'] = "La fecha inicial no puede ser menor a la fecha final del anterior trimestre";
                $j['code'] = 505;
            } else if ($lastQuarter) { // Si la edición del quarter lleva a un year el cual no tiene ningun quarter no se valida la consectividad de los trimestres
                if ($lastQuarter->quarter + 1 != $request->input('quarter') && $request->input('year') != $trimestreanio->year) { // Consecutividad de los trimestres, ejem. si el trimestre va en uno
                    $j['success'] = false;
                    $j['message'] = "El trimestre del año " . $request->input('year') . " va en " . $lastQuarter->quarter . " no se puede editar el trimestre si su valor siguiente no es igual a " . $lastQuarter->quarter + 1;
                    $j['code'] = 505;
                } else if ($rangeDates) { // Validar si las fechas se cruzan con otras
                    $j = cruzeFechas($startDate, $endDate, $request, $trimestreanio, $url);
                } else if ($rangeDates) { // Validar si las fechas se cruzan con otras
                    $j = cruzeFechas($startDate, $endDate, $request, $trimestreanio, $url);
                } else {
                    // Actualizacion
                    $trimestreanio->update([
                        'year' => $request->input('year'),
                        'quarter' => $request->input('quarter'),
                        'start_date' => $request->input('start'),
                        'finish_date' => $request->input('end')
                    ]);

                    // json para el SwalFire
                    $j = [
                        'success' => true,
                        'message' => 'Se edito el trimestre exitosamente',
                        'code' => 200,
                        'url' => $url
                    ];
                }
            } else if ($rangeDates) { // Validar si las fechas se cruzan con otras
                $j = cruzeFechas($startDate, $endDate, $request, $trimestreanio, $url);
            } else {
                // Actualizacion
                $trimestreanio->update([
                    'year' => $request->input('year'),
                    'quarter' => $request->input('quarter'),
                    'start_date' => $request->input('start'),
                    'finish_date' => $request->input('end')
                ]);

                // json para el SwalFire
                $j = [
                    'success' => true,
                    'message' => 'Se edito el trimestre exitosamente',
                    'code' => 200,
                    'url' => $url
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

    public function destroy($id)
    {
        $j = [];

        try {
            /*  DATA QUARTER YEARS */
            $trimestreanio = TrimestreAnio::findOrFail($id);
            /* DATE TO CARBON */
            $startDate = Carbon::parse($trimestreanio->start_date)->startOfDay();
            $endDate = Carbon::parse($trimestreanio->finish_date)->endOfDay();
            /* VALIDATIONS */

            /* USE QUARTER YEAR IN EVENTS */
            $useQuarterYearinEvents = DB::table('events')
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start', [$startDate, $endDate])
                        ->orWhereBetween('end', [$startDate, $endDate]);
                })
                ->exists();

            /* COUNT QUARTER IN A YEAR */
            $countYearsQuarter = DB::table('year_quarters')
                ->where('year', $trimestreanio->year)
                ->count();

            /* ORDER OF ELIMINATION */
            $orderDeleteQuarterYear = DB::table('year_quarters')
                ->where('year', $trimestreanio->year)
                ->where('quarter', '>', $trimestreanio->quarter)
                ->exists();

            /* CONDITIONALS */
            if ($useQuarterYearinEvents) {
                $j['icon'] = 'error';
                $j['title'] = 'Operación Cancelada';
                $j['message'] = 'Existen sesiones de clase dentro del trimestre del año, no se puede eliminar.';
                $j['success'] = false;
                $j['code'] = 200;
            } else if ($orderDeleteQuarterYear && $countYearsQuarter != 1) {
                $j['icon'] = 'error';
                $j['title'] = 'Operacion Cancelada';
                $j['message'] = 'No se puede eliminar un trimestre del año que va antes de otro.';
                $j['success'] = false;
                $j['code'] = 200;
            } else {
                $trimestreanio->delete();
                Alert::toast('Se eliminó el trimestre', 'warning');
                $j['icon'] = 'success';
                $j['title'] = 'Operacion Exitosa';
                $j['message'] = 'El trimestre ha sido eliminado.';
                $j['success'] = true;
                $j['code'] = 200;
            }
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function changeState($id)
    {
        $j = [];

        try {
            $trimestreanio = TrimestreAnio::findOrFail($id);

            if (trim($trimestreanio->state) === 'activo') {
                $trimestreanio->update([
                    'state' => 'inactivo'
                ]);
                Alert::toast('Se deshabilito el trimestre del año', 'warning');
                $j['title'] = 'Trimestre deshabilita';
                $j['success'] = true;
                $j['message'] = 'Trimestre deshabilita para su uso';
                $j['code'] = 200;
            } else {
                $trimestreanio->update([
                    'state' => 'activo'
                ]);
                Alert::toast('Se habilito el trimestre del año', 'warning');
                $j['title'] = 'Trimestre habilitada';
                $j['success'] = true;
                $j['message'] = 'Trimestre habilitada para su uso';
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
