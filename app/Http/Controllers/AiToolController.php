<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AiToolController extends Controller
{
    public function index()
    {
        return view('admin.ai.chatgpt');
    }
}
