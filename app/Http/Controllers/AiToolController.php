<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AiToolController extends Controller
{
    public function index()
    {
        $openaiApiKey = env('OPENAI_API_KEY');
        return view('admin.ai.chatgpt', compact('openaiApiKey'));
    }

    public function conversation()
    {
        $openaiApiKey = env('OPENAI_API_KEY');
        return view('admin.ai.conversation', compact('openaiApiKey'));
    }
}
