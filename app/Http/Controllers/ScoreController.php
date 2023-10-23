<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use App\Models\Score;
use App\Models\SoupGameQuestion;

class ScoreController extends Controller
{
    public function store(Request $request, $questionId)
    {        
        $user = Auth::user();
        $question = SoupGameQuestion::find($questionId);
        
        if (!$user || !$question) {
            return response()->json(['message' => 'User or Question not found'], 404);
        }

        $score = new Score();
        $score->user_id = $user->id;
        $score->question_id = $question->id;
        $score->score = $request->input('score');
        $score->question_count = $request->input('questionCount') ?? 0;
        $score->hint_count = $request->input('hintCount') ?? 0;
        $score->save();
        
        return response()->json(['message' => 'Score saved successfully']);
    }

    public function result($questionId, $questionCount, $hintCount)
    {
        $user = Auth::user(); //ログイン中のユーザーの情報を取り出す
        $question = SoupGameQuestion::find($questionId); //該当の問題の情報を取り出す
    
        $latestScore = Score::where('user_id', $user->id) //同じ該当ユーザーで同じ問題文で一番直近のスコア情報（ブラウザでは「今回のスコア」の部分
            ->where('question_id', $question->id)
            ->latest('created_at')
            ->first(); //該当した1つだけ指定(バグ回避用)
    
        $scores = Score::where('question_id', $questionId) //登録されているスコアを全部取り出す
            ->orderBy('score', 'asc') //スコアを得点順で取り出す
            ->with('user') //ユーザーもセットで取り出す
            ->get();
    
        return view('result', compact('question', 'latestScore', 'scores', 'questionCount', 'hintCount'));
    }
}
