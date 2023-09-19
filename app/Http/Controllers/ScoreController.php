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
}
