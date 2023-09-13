<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Score; // Scoreモデルを使用

class RankingController extends Controller
{
    public function index()
    {
        $rankings = Score::selectRaw('user_id, SUM(score) as total_score, SUM(question_count) as total_questions, SUM(hint_count) as total_hints')
            ->groupBy('user_id')
            ->orderBy('total_score', 'asc') // 最低点が上位になるように変更
            ->orderBy('total_questions', 'asc')
            ->orderBy('total_hints', 'asc')
            ->get();

            dd($rankings);  // デバッグ用
            
        return view('rankings.ranking', compact('rankings'));
    }

    public function askQuestion($userId)
    {
        // 質問のロジックをここに書く
        $this->updateScore($userId, 1, 'question');  // 質問により1点加算
    }

    public function viewHint($userId)
    {
        // ヒントのロジックをここに書く
        $this->updateScore($userId, 0.5, 'hint');  // ヒントにより0.5点加算
    }

    protected function updateScore($userId, $point, $type)
    {
        $score = Score::where('user_id', $userId)->first();
        
        if (!$score) {
            $score = new Score([
                'user_id' => $userId, 
                'score' => 0,
                'question_count' => 0,
                'hint_count' => 0
            ]);
        }

        $score->score += $point;

        if ($type === 'question') {
            $score->question_count += 1;
        } elseif ($type === 'hint') {
            $score->hint_count += 1;
        }

        $score->save();
    }
}
