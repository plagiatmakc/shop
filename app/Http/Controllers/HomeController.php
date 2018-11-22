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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function admin(Request $request){
        if($request->ajax()) {
            if (Auth::check() && Auth::user()->authorizeRoles(['admin'])) {
                return response('true');
            }else {
                return response('This action is unauthorized.');
            }

        }

    }
}
