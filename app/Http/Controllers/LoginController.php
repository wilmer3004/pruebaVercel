<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function index (){
        return view('autenticacion.login');
    }

    public function login(Request $request){
        
        $credentials = [
            "name" => $request->email,
            "password" => $request->password,
            "state" => 'activo',
        ];

        $remember = ($request->has('remember') ? true : false);

        if(Auth::attempt($credentials, $remember)){
            
            $request->session()->regenerate();

            return redirect()->intended(route('index'))->with('inicio', 'ha iniciado sesion');
            
        } else {
            return view('autenticacion.login')->with('error', 'ha ocurrido un error');
        }
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('inicio'))->with('cerrar', 'has cerrado sesion');
    }
}
