<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AiToolController extends Controller
{
    public function index()
    {
        return view('admin.ai.chatgpt');
    }

    public function conversation()
    {
        $openaiApiKey = env('OPENAI_API_KEY');
        return view('admin.ai.conversation', compact('openaiApiKey'));
    }
}
