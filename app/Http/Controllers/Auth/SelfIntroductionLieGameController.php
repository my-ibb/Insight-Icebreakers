<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;

class SelfIntroductionLieGameController extends Controller
{
    public function index()
    {
        // 参加人数のオプション（例: 2人から10人）
        $numberOfPlayersOptions = range(2, 10);

        // 設問個数のオプション（例: 1から5個）
        $numberOfQuestionsOptions = range(1, 5);

    // オプションをビューに渡す
return view('resources.self_introduction_lie_game', [  // 閉じカッコの位置を修正
    'numberOfPlayersOptions' => $numberOfPlayersOptions,
    'numberOfQuestionsOptions' => $numberOfQuestionsOptions
]);
    }

    public function setup() {
        return view('self_introduction_lie_game_setup');
    }

    public function start(Request $request) {
        $player_name = $request->input('player_name');
        $number_of_players = $request->input('number_of_players');
        $number_of_questions = $request->input('number_of_questions');

        // これらの情報をセッションなどに保存してゲームを開始する
        // ...

        return redirect()->route('selfIntroductionLieGame.play');
    }
    
}
