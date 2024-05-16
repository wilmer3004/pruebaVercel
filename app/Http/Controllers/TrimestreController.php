<?php

namespace App\Http\Controllers;

use App\Models\Trimestre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class TrimestreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Variable para contar el total de trimestres
        $totaltrimestres = Trimestre::count('id');

        // Redireccionar a la vista preferida
        return view('trimestres.index', compact('totaltrimestres'));
    }

    public function listar()
    {
        $j = [];

        try {
            $trimestre = DB::table('quarters')->select('id', 'name')->get();
            $j['success'] = true;
            $j['message'] = 'Consulta exitosa';
            $j['data'] = $trimestre;
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $j = [];
        $request->validate([
            'nombre' => 'required|min:1|max:20|regex:/^[a-zA-ZÀ-ÿ\s]{1,20}$/',
        ]);
        try {
            $url = route('trimestres.index');
            $trimestre = DB::table('quarters')->where('name', $request->input('nombre'))->exists();

            if ($trimestre) {
                $j['success'] = false;
                $j['message'] = 'El trimestre ya existe';
                $j['code'] = 505;
            } else {
                $trimestre = Trimestre::create(['name' => $request->input('nombre'),
                ]);

                Alert::toast('Se creó el trimestre ' . $request->nombre . ' exitosamente', 'success');
                $j['success'] = true;
                $j['message'] = 'Se creó el trimestre exitosamente';
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

    /**
     * Display the specified resource.
     */
    public function show(Trimestre $trimestre)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
/*     public function edit($id)
    {
        $J = [];
        try {
            $trimestre = DB::table('quarters')
            ->select('id', 'name')
            ->where('id', $id)
                ->get();

            $j['success'] = true;
            $j['data'] = $trimestre;
            $j['message'] = 'Consulta exitosa';
            $j['code'] = 200;
        } catch (\Throwable $th) {
            $j['success'] = false;
            $j['message'] = $th->getMessage();
            $j['code'] = 500;
        }

        return response()->json($j);
    } */

    /**
     * Update the specified resource in storage.
     */
/*     public function update(Request $request, Trimestre $trimestre)
    {
        $request->validate([
            'nombre' => 'required|min:1|max:20|regex:/^[a-zA-ZÀ-ÿ\s]{1,20}$/',
        ]);

        try {
            $url = route('trimestres.index');

            $trimestre = Trimestre::findOrFail($request->input('id'));

            if (Trimestre::where('name', $request->input('nombre'))
                ->where('id', '!=', $trimestre->id)
                ->exists()
            ) {
                $j = [
                    'success' => false,
                    'message' => 'El trimestre ya existe',
                    'code' => 400,
                ];
            } else {
                $trimestre->update([
                    'name' => $request->input('nombre')
                ]);
                Alert::toast('Se editó el trimestre ' . $request->nombre . ' exitosamente', 'info');
                $j = [
                    'success' => true,
                    'message' => 'Trimestre actualizado',
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
    } */

}
