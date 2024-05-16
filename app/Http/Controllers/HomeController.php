<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use App\Models\Ficha;
use App\Models\Programa;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        $users = User::count('id');
        $fichas = Ficha::count('id');
        $programas = Programa::count('id');
        $ambientes = Ambiente::count('id');

        return view('layouts.index', compact('users', 'fichas', 'programas', 'ambientes'));
    }
}
