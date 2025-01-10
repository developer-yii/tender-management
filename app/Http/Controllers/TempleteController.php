<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TempleteController extends Controller
{
    public function index(){
        return view('admin.templetes.index');
    }
}
