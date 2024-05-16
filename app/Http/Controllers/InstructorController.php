<?php

namespace App\Http\Controllers;

use App\Models\Componente;
use App\Models\Condicion;
use App\Models\Contrato;
use App\Models\Coordinacion;
use App\Models\InstructorCoordinacion;
use App\Models\Instructor;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;
use App\Mail\RegistroMailable;
use App\Models\DocumentsType;
use App\Models\TipoComponente;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class InstructorController extends Controller
{

    // Funcion para retornar al index
    public function index()
    {
        $totalinstructores = Instructor::count('id');
        $totalinstructoresHabilitados = Instructor::select('users.state')
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->where('users.state', '=', 'activo')
            ->count();

        $totalinstructoresDeshabilitados = Instructor::select('users.state')
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->where('users.state', '=', 'inactivo')
            ->count();

        return view('instructores.index', compact('totalinstructores','totalinstructoresHabilitados','totalinstructoresDeshabilitados'));
    }

    public function listar()
    {
        $j = [];

        try {
            $instructores = User::select(
                'teachers.id as id',
                'people.name as name',
                'people.lastname as lastname',
                'people.document as document',
                'people.email as email',
                'people.phone as phone',
                'contracts.name as contract',
                'dt.nicknames',
                'users.state as state',
                DB::raw('(SELECT string_agg(components_type.name, \', \') FROM teachers_components_type ct JOIN components_type ON ct.components_type_id = components_type.id WHERE ct.teachers_id = teachers.id) as components'),
                DB::raw('(SELECT string_agg(conditions.name, \', \') FROM conditions_teacher cdt JOIN conditions ON cdt.condition_id = conditions.id WHERE cdt.teacher_id = teachers.id) as conditions'),
                DB::raw('(SELECT string_agg(roles.name, \', \') FROM users_roles ur JOIN roles ON ur.rol_id = roles.id WHERE ur.user_id = users.id) as roles')
            )
                ->join('teachers', 'users.id', '=', 'teachers.user_id')
                ->join('people', 'people.user_id', '=', 'users.id')
                ->leftJoin('documents_type as dt','dt.id','=','people.document_type_id')
                ->join('contracts', 'teachers.contract_id', '=', 'contracts.id')
                ->get();


            $j['success'] = true;
            $j['data'] = $instructores;
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

        try {
            $roles = Rol::select('id', 'name')->where('name', '=', 'instructor')->where('state','=','activo')->get();
            $coordinaciones = Coordinacion::select('id', 'name')->where('state',true)->get();
            $condiciones = Condicion::select('id', 'name')->where('state',true)->get();
            $contratos = Contrato::select('id', 'name')->where('state',true)->get();
            $documentsType = DocumentsType::select('id','name','nicknames')->where('state',true)->get();

            $tipoComponentes = TipoComponente::select('id', 'name')->get();

            return view('instructores.createIndividual', compact('roles', 'coordinaciones', 'condiciones', 'contratos', 'tipoComponentes','documentsType'));
        } catch (\Throwable $th) {
            $totalinstructores = Instructor::count('id');

            return view('instructores.index', compact('totalinstructores'))->with('error', $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|min:3|max:20|regex:/^[a-zA-ZÀ-ÿ\s]{3,20}$/',
            'apellido' => 'required|min:1|max:20|regex:/^[a-zA-ZÀ-ÿ\s]{1,20}$/',
            'tipoDoc' => 'required',
            'documento' => 'required|min:3|max:14|regex:/^\d{3,14}$/',
            'email' => 'required|email|regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/',
            'estado' => 'required',
            'roles' => 'required',
            'telefono' => 'required|min:3|max:14|regex:/^\d{3,14}$/',
            'coordinacion' => 'required',
        ]);

        $j = [];

        try {
            $user = new User;

            $email = DB::table('users')->where('name', $request->input('email'))->exists();

            $coordinaciones = $request->input('coordinacion');

            $nombre = $request->input('nombre') . ' ' . $request->input('apellido');

            $url = route('instructores.index');

            /*             $componentes = DB::table('components')
                ->select('components.id', 'components.name')
                ->join('programs', 'programs.id', '=', 'components.program_id')
                ->whereIn('programs.coordination_id', $coordinaciones)
                ->get();  */

            if ($email) {
                $j['success'] = false;
                $j['message'] = 'El email ya está registrado';
                $j['code'] = 500;
            } else {
                $doc = DB::table('people')->where('document', $request->input('documento'))->exists();
                if ($doc) {
                    $j['success'] = false;
                    $j['message'] = 'El documento ya está registrado';
                    $j['code'] = 500;
                } else {
                    $user = User::create([
                        'name' => $request->input('email'),
                        'password' => Hash::make($request->input('documento')),
                        'state' => $request->input('estado')
                    ]);

                    $user->roles()->attach($request->input('roles'));

                    $id = $user->id;

                    $persona = Persona::create([
                        'name' => $request->input('nombre'),
                        'lastname' => $request->input('apellido'),
                        'document_type_id' => $request->input('tipoDoc'),
                        'phone' => $request->input('telefono'),
                        'document' => $request->input('documento'),
                        'email' => $request->input('email'),
                        'user_id' => $user->id,
                    ]);

                    // Envio de correo después de ingresar un usuario
                    // $correo = new RegistroMailable(
                    //     $request->all()
                    // );
                    // Mail::to($request->email)->send($correo);

                    $j['success'] = true;
                    $j['message'] = 'Datos guardados exitosamente';
                    $j['code'] = 200;
                    $j['data'] = [$id, $coordinaciones, $nombre];
                    $j['url '] = $url;
                }
            }
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function storeDetails(Request $request)
    {
        $j = [];
        $url = route('instructores.index');
        $condicion = 0;
        $hourObject = DB::table('contracts')->select('total_hours')->where('id', $request->input('contrato'))->first();
        $hour = $hourObject->total_hours;
        $coordinaciones = $request->input('coord');

        $request->validate([
            'contrato' => 'required',
            // 'componentes' => 'required',
            'tipo' => 'required'
        ]);

        try {

            if ($request->has('condiciones')) {
                if (in_array(1, $request->input('condiciones'))) {
                    $condicion = 1;
                    $percentageObject = DB::table('conditions_hours')->select('percentage')->where('contract_id', $request->input('contrato'))->where('condition_id', $condicion)->first();
                    $percentage = $percentageObject->percentage;
                    $hour = $hour - ($hour * $percentage / 100);
                }

                if (in_array(2, $request->input('condiciones'))) {
                    $condicion = 2;
                    $percentageObject = DB::table('conditions_hours')->select('percentage')->where('contract_id', $request->input('contrato'))->where('condition_id', $condicion)->first();
                    $percentage = $percentageObject->percentage;
                    $hour = $hour - ($hour * $percentage / 100);
                }

                if (in_array(3, $request->input('condiciones'))) {
                    $condicion = 3;
                    $percentageObject = DB::table('conditions_hours')->select('percentage')->where('contract_id', $request->input('contrato'))->where('condition_id', $condicion)->first();
                    $percentage = $percentageObject->percentage;
                    $hour = $hour - ($hour * $percentage / 100);
                }
            }

            $instructor = Instructor::create([
                'user_id' => $request->input('id'),
                'contract_id' => $request->input('contrato'),
                'total_hours' => $hour,
            ]);

            $coordinacionesArray = explode(',', $coordinaciones);
            foreach ($coordinacionesArray as &$valor) {
                $valor = (int)$valor;
            }
            $instructor->coordinaciones()->attach($coordinacionesArray);

            $instructor->tipoComponente()->attach($request->input('tipo'));

            $instructor->condiciones()->attach($request->input('condiciones'));
            // $instructor->componentes()->attach($request->input('componentes'));

            Alert::toast('Se creó el instructor ' . $request->name . ' correctamente', '    ccess');
            $j['success'] = true;
            $j['message'] = 'Instructor agreagado exitosamente';
            $j['code'] = 200;
            $j['url'] = $url;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function card()
    {
        return view('instructores.show');
    }

    public function show($id)
    {
        $j = [];

        try {
            return redirect()->route('instructores.card', ['id' => $id]);
        } catch (\Throwable $e) {
            $totalinstructores = Instructor::count('id');

            return view('instructores.index', compact('totalinstructores'))->with('error', $e->getMessage());
        }

        return response()->json($j);
    }

    public function consulta($id)
    {
        $j = [];

        try {
            $instructores = User::select(
                'teachers.id as id',
                'people.name as name',
                'people.lastname as lastname',
                'people.document as document',
                'people.email as email',
                'people.phone as phone',
                'contracts.name as contract',
                'users.state as state',
                'teachers.total_hours as hours',
                'dt.nicknames',
                DB::raw('(SELECT string_agg(components_type.name, \', \') FROM teachers_components_type ct JOIN components_type ON ct.components_type_id = components_type.id WHERE ct.teachers_id = teachers.id) as components'),
                DB::raw('(SELECT string_agg(conditions.name, \', \') FROM conditions_teacher cdt JOIN conditions ON cdt.condition_id = conditions.id WHERE cdt.teacher_id = teachers.id) as conditions'),
                DB::raw('(SELECT string_agg(roles.name, \', \') FROM users_roles ur JOIN roles ON ur.rol_id = roles.id WHERE ur.user_id = users.id) as roles'),
                DB::raw('(SELECT string_agg(coordinations.name, \', \') FROM teachers_coordinations tc JOIN coordinations ON tc.coordination_id = coordinations.id WHERE tc.teacher_id = teachers.id) as coordinations')
            )
                ->join('teachers', 'users.id', '=', 'teachers.user_id')
                ->join('people', 'people.user_id', '=', 'users.id')
                ->leftJoin('documents_type as dt','dt.id','=','people.document_type_id')
                ->join('contracts', 'teachers.contract_id', '=', 'contracts.id')
                ->where('teachers.id', $id)
                ->get();

            $j['success'] = true;
            $j['data'] = $instructores;
            $j['message'] = 'Consulta exitosa';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function edit($id)
    {
        try {

            $nombres_coordinaciones = []; // Inicializa el array de nombres de coordinaciones

            $instructor = Instructor::findOrFail($id);
            $coordinaciones = Coordinacion::select('id', 'name')->where('state',true)->get();
            $contratos = Contrato::select('id', 'name')->get();
            $roles = Rol::select('id', 'name')->where('name', '=', 'instructor')->get();
            $condiciones = Condicion::pluck('name', 'id');
            $componentes = Componente::pluck('name', 'id');
            $tipoComponentes = TipoComponente::select('name', 'id', 'state')->get();
            $documentsType = DocumentsType::select('id','name','nicknames')->where('state',true)->get();

            return view('instructores.edit', compact(
                'coordinaciones',
                'contratos',
                'roles',
                'condiciones',
                'componentes',
                'instructor',
                'tipoComponentes',
                'documentsType'
            ));
        } catch (\Throwable $th) {
            $totalinstructores = Instructor::count('id');
            return view('instructores.index', compact('totalinstructores'))->with('error', $th->getMessage());
        }
    }


    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required|min:3|max:20|regex:/^[a-zA-ZÀ-ÿ\s]{3,20}$/',
            'apellido' => 'required|min:1|max:20|regex:/^[a-zA-ZÀ-ÿ\s]{1,20}$/',
            'tipoDoc' => 'required',
            'documento' => 'required|min:3|max:14|regex:/^\d{3,14}$/',
            'email' => 'required|email|regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/',
            'estado' => 'required',
            'roles' => 'required',
            'telefono' => 'required|min:3|max:14|regex:/^\d{3,14}$/',
            'coordinaciones' => 'required',
        ]);

        $j = [];

        try {

            // Información instructor
            $instructor = Instructor::findOrFail($request->input('id'));

            // Informacion coordinaciones
            $coordinacionesArray = json_decode($request->input('coordinacionesArray')); // Array coordinaciones a modificar
            $coordinacionesArray = array_map('intval', $coordinacionesArray); // Transformar valores a int

            $coordinacionesActual = DB::table('teachers_coordinations') // Array coordincaiones actuales relacionadas a Instructor
                ->select('coordination_id')
                ->where('teacher_id', $instructor->id)
                ->orderBy('coordination_id', 'asc')
                ->get();

            // Update de mail
            $instructor->user->update([
                'name' => $request->input('email'),
                'state' => $request->input('estado'),
                'password' => Hash::make($request->input('documento')),
            ]);

            // Update de rol
            $instructor->user->roles()->sync($request->input('roles'));

            // Update de datos basicos
            $instructor->user->persona->update([
                'name' => $request->input('nombre'),
                'lastname' => $request->input('apellido'),
                'document_type_id' => $request->input('tipoDoc'),
                'phone' => $request->input('telefono'),
                'document' => $request->input('documento'),
                'email' => $request->input('email'),
            ]);


            // Update coordinaciones
            // Eliminar relaciones que no están en $coordinacionesArray
            foreach ($coordinacionesActual as $coordinacionActual) {
                if (!in_array($coordinacionActual->coordination_id, $coordinacionesArray)) {
                    // Eliminar la relación de coordinación
                    DB::table('teachers_coordinations')
                        ->where('teacher_id', $instructor->id)
                        ->where('coordination_id', $coordinacionActual->coordination_id)
                        ->delete();
                }
            }

            // Agregar nuevas relaciones que están en $coordinacionesArray pero no en $coordinacionesActual
            foreach ($coordinacionesArray as $coordinacion) {
                if (!in_array($coordinacion, $coordinacionesActual->pluck('coordination_id')->toArray())) {
                    // Agregar la nueva relación de coordinación
                    DB::table('teachers_coordinations')->insert([
                        'teacher_id' => $instructor->id,
                        'coordination_id' => $coordinacion
                    ]);
                }
            }

            $j['success'] = true;
            $j['message'] = 'Datos guardados exitosamente';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;;
        }

        return response()->json($j);
    }

    public function updateDetails(Request $request)
    {
        $j = [];
        $url = route('instructores.index');
        $condicion = 0;
        $hourObject = DB::table('contracts')->select('total_hours')->where('id', $request->input('contrato'))->first();
        $hour = $hourObject->total_hours;

        $request->validate([
            'contrato' => 'required',
            'tipo' => 'required'
        ]);

        try {
            $instructor = Instructor::findOrFail($request->input('id'));

            if ($request->has('condiciones')) {
                if (in_array(1, $request->input('condiciones'))) {
                    $condicion = 1;
                    $percentageObject = DB::table('conditions_hours')->select('percentage')->where('contract_id', $request->input('contrato'))->where('condition_id', $condicion)->first();
                    $percentage = $percentageObject->percentage;
                    $hour = $hour - ($hour * $percentage / 100);
                }

                if (in_array(2, $request->input('condiciones'))) {
                    $condicion = 2;
                    $percentageObject = DB::table('conditions_hours')->select('percentage')->where('contract_id', $request->input('contrato'))->where('condition_id', $condicion)->first();
                    $percentage = $percentageObject->percentage;
                    $hour = $hour - ($hour * $percentage / 100);
                }

                if (in_array(3, $request->input('condiciones'))) {
                    $condicion = 3;
                    $percentageObject = DB::table('conditions_hours')->select('percentage')->where('contract_id', $request->input('contrato'))->where('condition_id', $condicion)->first();
                    $percentage = $percentageObject->percentage;
                    $hour = $hour - ($hour * $percentage / 100);
                }
            }

            $instructor->update([
                'contract_id' => $request->input('contrato'),
                'total_hours' => $hour,
            ]);

            $instructor->condiciones()->sync($request->input('condiciones'));
            $instructor->tipoComponente()->sync($request->input('tipo'));

            Alert::toast('Se actualizó el instructor correctamente', 'success');
            $j['success'] = true;
            $j['message'] = 'Instructor actualizado exitosamente';
            $j['code'] = 200;
            $j['url'] = $url;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function disable($id)
    {
        $j = [];

        try {
            $instructor = DB::table('users')
                ->join('teachers', 'teachers.user_id', '=', 'users.id')
                ->where('teachers.id', $id)
                ->update(['state' => 'inactivo']);

            Alert::toast('Se deshabilitó el instructor', 'warning');
            $j['success'] = true;
            $j['message'] = 'Instructor deshabilitado';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function enable($id)
    {
        $j = [];

        try {
            $instructor = DB::table('users')
                ->join('teachers', 'teachers.user_id', '=', 'users.id')
                ->where('teachers.id', $id)
                ->update(['state' => 'activo']);

            Alert::toast('Se habilitó el instructor', 'success');
            $j['success'] = true;
            $j['message'] = 'Instructor habilitado';
            $j['code'] = 500;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }


        return response()->json($j);
    }

    public function delete($id)
    {
        $j = [];
        try {
            /* DATA */
            $teacher = Instructor::findOrFail($id);
            /*** VALIDATIONS ***/

            /* TEACHER IN EVENTS */
            $existTeacherEvents = DB::table('events')
                ->where('teacher_id', $id)
                ->exists();

            if ($existTeacherEvents) {
                $j['icon'] = "error";
                $j['title'] = "Eliminiación Cancelada";
                $j['message'] = "El instructor se encuentra registrado en el historial de programaciones, no se puede eliminar, se recomienda deshabilitarlo para no perder el historial o borrar las programaciones asociadas al mismo.";
                $j['success'] = false;
                $j['code'] = 200;
            } else {
                DB::table('teachers_components_type')->where('teachers_id', $id)->delete();
                DB::table('conditions_teacher')->where('teacher_id', $id)->delete();
                DB::table('teachers_coordinations')->where('teacher_id', $id)->delete();
                $teacher->delete();
                Alert::toast('Se eliminó el instructor', 'warning');
                $j['icon'] = "success";
                $j['title'] = "Eliminación Exitosa";
                $j['message'] = "El instructor a sido eliminado exitosamente.";
                $j['success'] = true;
                $j['code'] = 200;
            }
        } catch (\Throwable $th) {
            $j['icon'] = "error";
            $j['title'] = "Hubo un error";
            $j['message'] = $th->getMessage();
            Log::error($th->getMessage());
            // $j['message'] = "Por favor contactarse con soporte, hubo un error en la eliminación del instructor.";
            $j['success'] = false;
            $j['code'] = 500;
        }

        return response()->json($j);
    }
}
