<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoupGameQuestion;

class SoupGameQuestionController extends Controller
{
    public function showDashboard()
    {
        $questions = SoupGameQuestion::all();
        return view('auth.passwords.admin.dashboard', ['questions' => $questions]);
    }    
    public function deleteQuestion($id)
{
    $question = SoupGameQuestion::find($id);  // idで問題を取得
    $question->delete();  // 問題を削除
    return redirect()->back()->with('success', 'Question deleted successfully.');  // 削除後のリダイレクト
}

public function editQuestion($id)
{
    $question = SoupGameQuestion::find($id);  // idで問題を取得
    return view('your_edit_view', ['question' => $question]);  // 編集ビューにデータを渡す
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