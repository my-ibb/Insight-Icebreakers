<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
      // 仮のデータ。実際にはデータベース等から問題のリストを取得
    $questions = [
        ['id' => 1, 'title' => 'Question 1'],
        ['id' => 2, 'title' => 'Question 2'],
        // 必要なだけ追加
    ];
    return view('questions.index', compact('questions'));
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
    public function showQuestionForm()
{
    return view('questions.question_form');
}

    public function showHintForm()
{
        return view('questions.hint_form');
}
    public function edit($id)
{
        return view('questions.edit', compact('id'));
}
    public function detail($id)
{
    // 仮のデータ。実際にはデータベース等から問題の詳細を取得
    $questionDetail = [
        'id' => $id,
        'title' => 'Question ' . $id,
        'content' => 'This is a detail of question ' . $id,
        // 必要なだけ追加
    ];

        return view('questions.detail', compact('questionDetail'));
}
}
