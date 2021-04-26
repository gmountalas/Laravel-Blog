<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomesController extends Controller
{
    public function home() 
    {
        // dd(Auth::id());
        // dd(Auth::user());
        // dd(Auth::check());
        return view('home.index');
    }

    public function contact() 
    {
        return view('home.contact');
    }
}
