<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        return view('questions.index');
    }

    public function create()
    {
        return view('questions.create');
    }

    public function show($id)
    {
        return view('questions.show', compact('id'));
    }

    public function checkAnswer($id)
    {
        return view('questions.check', compact('id'));
    }
}
