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
    public function index(Request $request)
    {
        $user = Auth::user();
        if($user->role == 1){
            return redirect()->route('tender.index');
            // return view('admin.tenders.index', compact('user'));
        }else{
            return redirect()->route('employee.tenders');
            // return view('employee.tenders', compact('user'));
        }
    }
}
