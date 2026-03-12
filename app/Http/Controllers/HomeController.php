<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->guest('/login'); 
        }

        $role = Auth::user()->role;

        if ($role === 'admin') {
            return redirect()->intended('/monitor');
        } elseif ($role === 'officer') {
            return redirect()->intended('/officer/open_status');
        } else {
            return redirect()->intended('/sos');
        }
    }
}
