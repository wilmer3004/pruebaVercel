<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Mail\RegistroMailable;
use App\Models\Condicion;
use App\Models\Contrato;
use App\Models\Coordinacion;
use App\Models\DocumentsType;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\FuncCall;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use ZipStream\Bigint;

class PersonaController extends Controller
{

    // Método para mostrar los registros en la tabla del index
    public function index()
    {
        $idRol = Auth::user()->roles->pluck('id')->toArray();
        sort($idRol);
        $lowestRolId = (int) $idRol[0];  // El rol más bajo que debe ser excluido


        $usersWithLowestRole = User::select('user_id')
        ->join('users_roles', 'users_roles.user_id', '=', 'users.id')
        ->where('rol_id', $lowestRolId)
        ->pluck('user_id');  // Obtener solo los IDs de los usuarios que tienen el rol más bajo

        $userWithHigherRank= User::select('user_id')
        ->join('users_roles', 'users_roles.user_id', '=', 'users.id')
        ->where('rol_id','<', $lowestRolId)
        ->pluck('user_id');

        $usersWithLowestRole = User::select('user_id')
            ->join('users_roles', 'users_roles.user_id', '=', 'users.id')
            ->where('rol_id', $lowestRolId)
            ->pluck('user_id');  // Obtener solo los IDs de los usuarios que tienen el rol más bajo

        $userWithHigherRank= User::select('user_id')
        ->join('users_roles', 'users_roles.user_id', '=', 'users.id')
        ->where('rol_id','<', $lowestRolId)
        ->pluck('user_id');

        $totalpersonas = User::select(
            'users.id as id',
            'people.name as name',
            'people.lastname as lastname',
            'people.document as document',
            'people.email as email',
            'people.phone as phone',
            'users.state as state',
            'dt.nicknames'
        )
            ->join('people', 'people.user_id', '=', 'users.id')
            ->join('users_roles as ur', 'ur.user_id','=','users.id')
            ->join('documents_type as dt','dt.id','=','people.document_type_id')
            ->where('rol_id','!=',4)
            ->where('rol_id', '>', $lowestRolId)
            ->whereNotIn('users.id', $usersWithLowestRole)  // Excluir usuarios que tienen el rol más bajo
            ->whereNotIn('users.id', $userWithHigherRank)  // Excluir usuarios que tienen el rol más alto
            ->groupBy('users.id', 'people.name', 'people.lastname', 'people.document', 'people.email', 'people.phone', 'users.state','dt.nicknames')
            ->get();
        $totalpersonas=count($totalpersonas);


        $totalpersonasH = User::select(
            'users.id as id',
            'people.name as name',
            'people.lastname as lastname',
            'people.document as document',
            'people.email as email',
            'people.phone as phone',
            'users.state as state',
            'dt.nicknames'
        )
            ->join('people', 'people.user_id', '=', 'users.id')
            ->join('users_roles as ur', 'ur.user_id','=','users.id')
            ->join('documents_type as dt','dt.id','=','people.document_type_id')
            ->where('rol_id','!=',4)
            ->where('rol_id', '>', $lowestRolId)
            ->where("users.state","activo")
            ->whereNotIn('users.id', $usersWithLowestRole)  // Excluir usuarios que tienen el rol más bajo
            ->whereNotIn('users.id', $userWithHigherRank)  // Excluir usuarios que tienen el rol más alto
            ->groupBy('users.id', 'people.name', 'people.lastname', 'people.document', 'people.email', 'people.phone', 'users.state','dt.nicknames')
            ->get();
        $totalpersonasH=count($totalpersonasH);

        $totalpersonasD = User::select(
            'users.id as id',
            'people.name as name',
            'people.lastname as lastname',
            'people.document as document',
            'people.email as email',
            'people.phone as phone',
            'users.state as state',
            'dt.nicknames'
        )
            ->join('people', 'people.user_id', '=', 'users.id')
            ->join('users_roles as ur', 'ur.user_id','=','users.id')
            ->join('documents_type as dt','dt.id','=','people.document_type_id')
            ->where('rol_id','!=',4)
            ->where('rol_id', '>', $lowestRolId)
            ->where("users.state","inactivo")
            ->whereNotIn('users.id', $usersWithLowestRole)  // Excluir usuarios que tienen el rol más bajo
            ->whereNotIn('users.id', $userWithHigherRank)  // Excluir usuarios que tienen el rol más alto
            ->groupBy('users.id', 'people.name', 'people.lastname', 'people.document', 'people.email', 'people.phone', 'users.state','dt.nicknames')
            ->get();
        $totalpersonasD=count($totalpersonasD);

        // función para retornar a la vista con las variables predefinidas
        return view('personas.index', compact('totalpersonas','totalpersonasH','totalpersonasD'));
    }

    public function listar(Request $request)
    {
        $request->validate([
            "idRol"=>'required|array'
        ]);

        // Assuming idRol is an array of integers
    $idRol = $request->input('idRol');
    sort($idRol);
    $lowestRolId = (int) $idRol[0];  // El rol más bajo que debe ser excluido

    $j = [];

    try {
        $usersWithLowestRole = User::select('user_id')
            ->join('users_roles', 'users_roles.user_id', '=', 'users.id')
            ->where('rol_id', $lowestRolId)
            ->pluck('user_id');  // Obtener solo los IDs de los usuarios que tienen el rol más bajo

        $userWithHigherRank= User::select('user_id')
        ->join('users_roles', 'users_roles.user_id', '=', 'users.id')
        ->where('rol_id','<', $lowestRolId)
        ->pluck('user_id');

        $persona = User::select(
            'users.id as id',
            'people.name as name',
            'people.lastname as lastname',
            'people.document as document',
            'people.email as email',
            'people.phone as phone',
            'users.state as state',
            'dt.nicknames'
        )
            ->join('people', 'people.user_id', '=', 'users.id')
            ->join('users_roles as ur', 'ur.user_id','=','users.id')
            ->join('documents_type as dt','dt.id','=','people.document_type_id')
            ->where('rol_id','!=',4)
            ->where('rol_id', '>', $lowestRolId)
            ->whereNotIn('users.id', $usersWithLowestRole)  // Excluir usuarios que tienen el rol más bajo
            ->whereNotIn('users.id', $userWithHigherRank)  // Excluir usuarios que tienen el rol más alto
            ->groupBy('users.id', 'people.name', 'people.lastname', 'people.document', 'people.email', 'people.phone', 'users.state','dt.nicknames')
            ->get();

            $j['success'] = true;
            $j['data'] = $persona;
            $j['message'] = 'Consulta exitosa';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    //Método para redirigir al formulario de crear
    public function create()
    {
        try {
            $roles = Rol::select('id', 'name')->where('id','!=',4)->where('state','=','activo')->get();
            $documentsType = DocumentsType::select('id','name','nicknames')->where('state',true)->get();
            return view('personas.createIndividual', compact('roles', 'documentsType'));
        } catch (\Throwable $th) {
            $totalpersonas = User::count('id');

            return view('personas.index', compact('totalpersonas', 'documentsType'))->with('error', $th->getMessage());
        }
    }

    //Método para guardar los datos en la base de datos
    public function store(Request $request)
    {
        // Validación para los datos a nivel de servidor
        $request->validate([
            'nombre' => 'required|min:3|max:20|regex:/^[a-zA-ZÀ-ÿ\s]{3,20}$/',
            'apellido' => 'required|min:1|max:20|regex:/^[a-zA-ZÀ-ÿ\s]{1,20}$/',
            'documentsType' => 'required',
            'documento' => 'required|min:3|max:14|regex:/^\d{3,14}$/',
            'email' => 'required|email|regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/',
            'estado' => 'required',
            'roles' => 'required',
            'telefono' => 'required|min:3|max:14|regex:/^\d{3,14}$/',
        ]);
        // dd($request->input('documentsType'));

        $j = [];

        try {
            $user = new User;

            $email = DB::table('users')->where('name', $request->input('email'))->exists();

            $url = route('personas.index');

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


                    $persona = Persona::create([
                        'name' => $request->input('nombre'),
                        'lastname' => $request->input('apellido'),
                        'document' => $request->input('documento'),
                        'email' => $request->input('email'),
                        'phone' => $request->input('telefono'),
                        'user_id' => $user->id,
                        'document_type_id' => $request->input('documentsType'), // Asegúrate de que este valor no sea nulo
                    ]);





                    Alert::toast('Se creó el usuario ' . $request->nombre . ' correctamente', 'success');
                    $j['success'] = true;
                    $j['message'] = 'Datos guardados exitosamente';
                    $j['code'] = 200;
                    $j['url'] = $url;
                }
            }
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function card()
    {
        return view('personas.show');
    }

    // Método para mostrar la información del usuairo en una vista predeterminada
    public function show($id)
    {
        $j = [];

        try {
            return redirect()->route('personas.card', ['id' => $id]);
        } catch (\Throwable $e) {
            $totalpersonas = User::count('id');

            return view('personas.index', compact('totalpersonas'))->with('error', $e->getMessage());
        }

        return response()->json($j);
    }

    public function consulta($id)
    {
        $j = [];

        try {
            $persona = User::select(
                'users.id as id',
                'people.name as name',
                'people.lastname as lastname',
                'people.document as document',
                'people.email as email',
                'people.phone as phone',
                'users.state as state',
                'dt.nicknames',

                DB::raw('(SELECT string_agg(roles.name, \', \') FROM users_roles ur JOIN roles ON ur.rol_id = roles.id WHERE ur.user_id = users.id) as roles')
            )
                ->join('people', 'people.user_id', '=', 'users.id')
                ->join('documents_type as dt','dt.id','=','people.document_type_id')

                ->where('users.id', $id)
                ->get();

            $j['success'] = true;
            $j['data'] = $persona;
            $j['message'] = 'Consulta exitosa';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    // Método para redirigir al formulario de editar
    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);

            $roles = Rol::select('name', 'id')->where('name', '!=', 'instructor')->get();

            $documentsType = DocumentsType::select('id','name','nicknames')->where('state',true)->get();

            return view('personas.edit', compact('roles', 'user','documentsType'));
        } catch (\Throwable $th) {
            $totalpersonas = Persona::count('id');

            return view('personas.index', compact('totalpersonas'))->with('error', $th->getMessage());
        }
    }

    // Método para actualizar el registro en la base de datos
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
        ]);

        $j = [];

        try {


            $url = route('personas.index');
            $user = User::findOrFail($request->input('id'));

            $user->update([
                'name' => $request->input('email'),
                'state' => $request->input('estado'),
                'password' => Hash::make($request->input('documento')),
            ]);

            $user->roles()->sync($request->input('roles'));

            $user->persona->update([
                'name' => $request->input('nombre'),
                'lastname' => $request->input('apellido'),
                'document_type_id' => $request->input('tipoDoc'),
                'phone' => $request->input('telefono'),
                'document' => $request->input('documento'),
                'email' => $request->input('email'),
            ]);

            Alert::toast('Se actualizó la persona ' . $request->input('name') . ' correctamente', 'success');
            $j['success'] = true;
            $j['message'] = 'Datos guardados exitosamente';
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
            $personas = DB::table('users')
                ->where('users.id', $id)
                ->update(['state' => 'inactivo']);
            Alert::toast('Se deshabilitó el usuario', 'warning');
            $j['success'] = true;
            $j['message'] = 'Usuario deshabilitado';
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
            $personas = DB::table('users')
                ->where('users.id', $id)
                ->update(['state' => 'activo']);
            Alert::toast('Se habilitó el usuario', 'warning');
            $j['success'] = true;
            $j['message'] = 'Usuario habilitado';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function export(){
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
