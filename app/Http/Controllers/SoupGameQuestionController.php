<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoupGameQuestion;

class SoupGameQuestionController extends Controller
{

    public function showQuestions()
    {
        $questions = SoupGameQuestion::all();
        return view('questions.index', ['questions' => $questions]);
    }



public function updateQuestion(Request $request, $id)
{
    $question = SoupGameQuestion::find($id);  // idで問題を取得
    $question->title = $request->input('title');  // 新しいタイトルで更新
    $question->content = $request->input('content');  // 新しいコンテンツで更新
    $question->save();  // 変更を保存
    return redirect()->back()->with('success', 'Question updated successfully.');  // 更新後のリダイレクト
}
}