<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\IntroGameQuestion; // IntroGameQuestionモデルをインポート
use GuzzleHttp\Client;

class SelfIntroductionLieGameController extends Controller
{
    // 初期設定画面を表示
    public function index()
    {
        // 参加人数のオプション（例: 2人から10人）
        $numberOfPlayersOptions = range(2, 10);

        // 設問個数のオプション（例: 1から5個）
        $numberOfQuestionsOptions = range(1, 5);

        $player_names = session('player_names', []); // セッションからプレイヤー名を取得

        // オプションをビューに渡す
        return view('self_introduction_lie_game', [
            'numberOfPlayersOptions' => $numberOfPlayersOptions,
            'numberOfQuestionsOptions' => $numberOfQuestionsOptions,
            'player_names' => $player_names // プレイヤー名をビューに渡す
        ]);
    }

    // 設問入力画面を表示
    public function setup()
    {
        $numberOfPlayersOptions = range(2, 10);
        $numberOfQuestionsOptions = range(1, 5);
        $player_names = session('player_names', []); // セッションからプレイヤー名を取得。デフォルトは空の配列。
    
        $number_of_questions = session('number_of_questions', 1); // セッションから設問数を取得。デフォルトは1。
    
        $questions = IntroGameQuestion::inRandomOrder()->limit($number_of_questions)->get(); // ランダムな質問を取得
    
        return view('self_introduction_lie_game_setup', [
            'numberOfPlayersOptions' => $numberOfPlayersOptions,
            'numberOfQuestionsOptions' => $numberOfQuestionsOptions,
            'player_names' => $player_names, // プレイヤー名をビューに渡す
            'questions' => $questions // ランダムに取得した質問をビューに渡す
        ]);
    }
    
    // 現在のプレイヤー名をビューに渡す
    public function start(Request $request) 
    {
        $player_names = $request->input('player_names');
        $number_of_players = $request->input('number_of_players');
        $number_of_questions = $request->input('number_of_questions');
        
        // 例えば、IntroGameQuestionモデルから設問をランダムに取得
        $questions = IntroGameQuestion::inRandomOrder()->take($number_of_questions)->pluck('id')->toArray();
    
        // これらの情報をセッションに保存
        session([
            'player_names' => $player_names,
            'number_of_players' => $number_of_players,
            'number_of_questions' => $number_of_questions,
            'questions' => $questions, // 同じ設問をセッションに保存
        ]);
    
        // 設問入力画面にリダイレクト
        return redirect()->route('selfIntroductionLieGame.setup');
    }

    // 設問入力画面にリダイレクトするメソッド
    private function redirectToSetup() 
    {
        $player_index = session('current_player_index', 0);
        $player_names = session('player_names', []);

        // 現在のプレイヤー名をセッションに保存
        session(['current_player_name' => $player_names[$player_index] ?? 'デフォルト名']);

        // 次のプレイヤーに移る準備
        session(['current_player_index' => $player_index + 1]);

        return redirect()->route('selfIntroductionLieGame.setup');
    }

    public function storeTruthAndLie(Request $request) 
{
    // 真実と嘘の情報を処理・保存
    
    // GPT APIを呼び出し要約を生成
    $response = $this->callGPTAPI($request->input('content'));
    
    // 要約結果のハンドリングと保存
    if($response['success']){
        session(['summary' => $response['data']]);
    }else{
        return redirect()->back()->withErrors(['api_error' => '要約の生成に失敗しました。']);
    }
    // 次のプレイヤーにリダイレクト
    return $this->redirectToSetup();
}

private function callGPTAPI($content)
{
    $client = new Client();
    $endpoint = "https://api.openai.com/v1/chat/completions"; 
    
    try {
        // API呼び出し
        $response = $client->post($endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json'
            ],
            
            'json' => [
                'model' => "gpt-3.5-turbo-0613",
                "messages" => [
                    [
                        "role" => "system",
                        "content" => $prompt
                    ],
                    [
                        "role" => "user",
                        "content" => $content
                    ]
                ]
            ]
        ]);
        
        if($response->successful()){
            return [
                'success' => true,
                'data' => $response->body() // 必要に応じて変更してください
            ];
        }else{
            return [
                'success' => false,
                'error' => $response->body() // 必要に応じて変更してください
            ];
        }
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}   
    //  自己紹介表示画面
    public function display() 
{
    $player_names = session('player_names', []); // セッションからプレイヤー名を取得。
    
    // 自己紹介表示ページにリダイレクト
    return view('self_introduction_lie_game_display', [
        'player_names' => $player_names // プレイヤー名をビューに渡す
    ]);
}
    }
