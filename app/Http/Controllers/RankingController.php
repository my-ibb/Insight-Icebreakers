<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Score; // Score モデルを使用する
use App\Models\Question; // Question モデルを使用する

class RankingController extends Controller
{
    public function show($questionId)
    {
        // 該当の問題の詳細を取得（オプション）
        $question = Question::find($questionId);

        // データベースから該当の問題IDに関連するスコアデータを取得
        $scores = Score::where('question_id', $questionId)
            ->with('user') // usersテーブルと結合して、ユーザー情報も取得
            ->orderBy('score', 'desc')
            ->get();

        // ビューにデータを渡して表示
        return view('ranking.show', [
            'scores' => $scores,
            'question' => $question, // オプション
        ]);
    }
}
