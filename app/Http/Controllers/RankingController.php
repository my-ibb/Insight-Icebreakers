<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Score; // Ranking モデルを使用する場合
use App\Models\Question; // Question モデルを使用する場合

class RankingController extends Controller
{
    public function show($questionId)
    {
        // データベースから該当の問題IDに関連するランキングデータを取得
        $rankings = Score::where('question_id', $questionId)->orderBy('score', 'desc')->get();

        // 該当の問題の詳細も取得（オプション）
        $question = Question::find($questionId);

        // ビューにデータを渡して表示
        return view('ranking.show', [
            'rankings' => $rankings,
            'question' => $question, // オプション
        ]);
    }
}
