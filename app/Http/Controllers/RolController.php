<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;


class RolController extends Controller
{


    public function index()
    {

        // Variable para contar el total de roles
        $totalroles = Rol::count('id');
        $totalrolesHabilitados = Rol::where('state', 'activo')->count();
        $totalrolesDeshabilitados = Rol::where('state', 'inactivo')->count();


        // Metodo para listar los roles
        // $roles = Rol::orderBy('id', 'asc')->paginate(5);

        // Redireccionar a la vista de la lista
        return view('roles.index', compact('totalroles','totalrolesDeshabilitados','totalrolesHabilitados'));
    }

    public function listar()
    {
        $j = [];

        try {
            $roles = DB::table('roles')->select('id', 'name', 'description', 'state')->get();
            $j['success'] = true;
            $j['message'] = 'Consulta exitosa';
            $j['data'] = $roles;
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
            'nombre' => 'required|min:4|max:20|regex:/^[a-zA-ZÀ-ÿ\s]{4,20}$/',
            'descripcion' => 'required|min:8|max:200|regex:/^[a-zA-ZÀ-ÿ\s]{8,200}$/',
        ]);
        try {
            $url = route('roles.index');
            $rol = DB::table('roles')->where('name', $request->input('nombre'))->exists();

            if ($rol) {
                $j['success'] = false;
                $j['message'] = 'El rol ya existe';
                $j['code'] = 505;
            } else {
                $rol = Rol::create([
                    'name' => $request->input('nombre'),
                    'description' => $request->input('descripcion'),
                ]);

                Alert::toast('Se creó el rol ' . $request->nombre . ' exitosamente', 'success');
                $j['success'] = true;
                $j['message'] = 'Se creó el rol exitosamente';
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

    public function edit($id)
    {
        $j = [];
        try {
            $roles = DB::table('roles')
                ->select('id', 'name', 'description')
                ->where('id', $id)
                ->get();

            $j['success'] = true;
            $j['data'] = $roles;
            $j['message'] = 'Consulta exitosa';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required|min:4|max:30|regex:/^[a-zA-ZÀ-ÿ\s]{4,30}$/',
            'descripcion' => 'required|min:8|max:200|regex:/^[a-zA-ZÀ-ÿ\s]{8,200}$/',
        ]);

        try {
            $url = route('roles.index');

            $rol = Rol::findOrFail($request->input('id'));

            if (Rol::where('name', $request->input('nombre'))
                ->where('id', '!=', $rol->id)
                ->exists()
            ) {
                $j = [
                    'success' => false,
                    'message' => 'El rol ya existe',
                    'code' => 400,
                ];
            } else {
                $rol->update([
                    'name' => $request->nombre,
                    'description' => $request->input('descripcion')
                ]);
                Alert::toast('Se editó el rol ' . $request->nombre . ' exitosamente', 'info');
                $j = [
                    'success' => true,
                    'message' => 'Rol actualizado',
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

    public function destroy($id)
    {
        $j = [];

        try {
            $rol = Rol::findOrFail($id);

            if(trim($rol->state)==='activo'){
                $rol->update([
                    'state'=> 'inactivo'
                ]);
                Alert::toast('Se deshabilito el rol', 'warning');
                $j['title'] = 'Rol deshabilitado';
                $j['success'] = true;
                $j['message'] = 'Rol deshabilitado para su uso';
                $j['code'] = 200;
            } else {
                $rol->update([
                    'state'=> 'activo'
                ]);
                Alert::toast('Se habilito el rol ', 'warning');
                $j['title'] = 'Rol habilitado';
                $j['success'] = true;
                $j['message'] = 'Rol habilitado para su uso';
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
