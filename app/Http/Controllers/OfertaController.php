<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class OfertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalofertas = Oferta::count('id');

        return view('ofertas.index', compact('totalofertas'));
    }

    public function listar()
    {
        $j = [];

        try {
            $ofertas = DB::table('offers')->select('id', 'name', 'state')->get();
            $j['success'] = true;
            $j['message'] = 'Consulta exitosa';
            $j['data'] = $ofertas;
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
            'nombre' => 'required|min:4|max:20|regex:/^[a-zA-ZÀ-ÿ\s]{4,20}$/',
        ]);
        try {
            $url = route('ofertas.index');
            $oferta = DB::table('offers')->where('name', $request->input('nombre'))->exists();

            if ($oferta) {
                $j['success'] = false;
                $j['message'] = 'La oferta ya existe';
                $j['code'] = 505;
            } else {
                $oferta = Oferta::create([
                    'name' => $request->nombre,
                ]);

                Alert::toast('Se creó la oferta ' . $request->nombre . ' exitosamente', 'success');
                $j['success'] = true;
                $j['message'] = 'Se creó la oferta exitosamente';
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
    public function show(Oferta $oferta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $J = [];
        try {
            $ofertas = DB::table('offers')
                ->select('id', 'name')
                ->where('id', $id)
                ->get();

            $j['success'] = true;
            $j['data'] = $ofertas;
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
            'nombre' => 'required|min:4|max:20|regex:/^[a-zA-ZÀ-ÿ\s]{4,20}$/',
        ]);

        try {
            $url = route('ofertas.index');

            $oferta = Oferta::findOrFail($request->input('id'));

            if (Oferta::where('name', $request->input('nombre'))
                ->where('id', '!=', $oferta->id)
                ->exists()
            ) {
                $j = [
                    'success' => false,
                    'message' => 'La oferta ya existe',
                    'code' => 400,
                ];
            } else {
                $oferta->update([
                    'name' => $request->input('nombre')
                ]);
                Alert::toast('Se editó la oferta ' . $request->nombre . ' exitosamente', 'info');
                $j = [
                    'success' => true,
                    'message' => 'Oferta actualizada',
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
            $ofertas = Oferta::findOrFail($id);

            if(trim($ofertas->state)==='activo'){
                $ofertas->update([
                    'state'=> 'inactivo'
                ]);
                Alert::toast('Se deshabilito la oferta', 'warning');
                $j['title'] = 'Oferta deshabilitada';
                $j['success'] = true;
                $j['message'] = 'Oferta deshabilitada para su uso';
                $j['code'] = 200;
            } else {
                $ofertas->update([
                    'state'=> 'activo'
                ]);
                Alert::toast('Se habilito la oferta', 'warning');
                $j['title'] = 'Oferta habilitada';
                $j['success'] = true;
                $j['message'] = 'Oferta habilitada para su uso';
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
