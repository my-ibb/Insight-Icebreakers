<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\IntroGameQuestion; // IntroGameQuestionモデルをインポート

class SelfIntroductionLieGameController extends Controller
{
    // 初期設定画面を表示
    public function index()
    {
        // 参加人数のオプション（例: 2人から10人）
        $numberOfPlayersOptions = range(2, 10);

        // 設問個数のオプション（例: 1から5個）
        $numberOfQuestionsOptions = range(1, 5);

        // オプションをビューに渡す
        return view('self_introduction_lie_game', [
            'numberOfPlayersOptions' => $numberOfPlayersOptions,
            'numberOfQuestionsOptions' => $numberOfQuestionsOptions
        ]);
    }

    // 設問入力画面を表示
    public function setup()
    {
        $numberOfPlayersOptions = range(2, 10);
        $numberOfQuestionsOptions = range(1, 5);

        return view('self_introduction_lie_game_setup', [
            'numberOfPlayersOptions' => $numberOfPlayersOptions,
            'numberOfQuestionsOptions' => $numberOfQuestionsOptions
        ]);
    }

    // 現在のプレイヤー名をビューに渡す
    public function start(Request $request) 
    {
        $player_name = $request->input('player_name');
        $number_of_players = $request->input('number_of_players');
        $number_of_questions = $request->input('number_of_questions');
        
         // 仮に、$player_namesを作成する（この部分はどうやってプレイヤー名を集めるかに依存）
        $player_names = [$player_name];  // ここで$player_namesを定義
        
        // これらの情報をセッションに保存（他の保存方法もあり）
        session([
            'player_names' => $player_names,// 単一のプレイヤー名を取得
            'number_of_players' => $number_of_players,
            'number_of_questions' => $number_of_questions
        ]);
    
        // 設問入力画面にリダイレクト
        return redirect()->route('selfIntroductionLieGame.setup');
    }

    // 設問が完了したら呼び出されるメソッド
    public function completeQuestion(Request $request) {
        // 現在のプレイヤー名をセッションから取得する（仮の例）
        $current_player_name = session('current_player_name', 'Default Name');
    
        // 設問内容をリクエストから取得
        $content = $request->input('content');
    
        // 設問内容をデータベースに保存
        IntroGameQuestion::create([
            'content' => $content
            // 必要に応じて他のフィールドも追加
        ]);
    
        // 次のプレイヤーに移るロジック（セッションの更新、など）
        // ...
    
        // 設問入力画面にリダイレクト（次のプレイヤーのターン）
        return redirect()->route('selfIntroductionLieGame.setup');
    }
}
