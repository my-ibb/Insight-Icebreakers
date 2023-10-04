<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\IntroGameQuestion; // IntroGameQuestionモデルをインポート
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SelfIntroductionLieGameController extends Controller
{
    // 初期設定画面を表示
    //オプションとセッションからのプレイヤー名をビューに渡す
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

    
    // ゲーム開始処理
    public function start(Request $request) 
    {
        // プレイヤー名をフォームの入力内容から取得
        $player_names = $request->input('player_names');
        // プレイ人数をフォームの入力内容から取得
        $number_of_players = $request->input('number_of_players');
        // 設問数をフォームの入力内容から取得
        $number_of_questions = $request->input('number_of_questions');
    
        // ランダムな質問を取得
        $questions = IntroGameQuestion::inRandomOrder()->limit($number_of_questions)->get(); 
    
        // 最初のプレイヤーをセッションに保存
        // $player_namesが空でないことをチェックしてから
        $current_player_name = $player_names[0] ?? null;
    
        // これらの情報をセッションに保存
        session([
            'player_names' => $player_names,
            'number_of_players' => $number_of_players,
            'number_of_questions' => $number_of_questions,
            'questions' => $questions,
            'current_player_name' => $current_player_name,
            'current_player_index' => 0,  // 最初のプレイヤーのインデックスも保存
        ]);
    
        // 入力データをセッションに保存し、設問入力画面にリダイレクト
        return redirect()->route('selfIntroductionLieGame.setup');
    }

    // 設問入力画面を表示
    //ランダムな質問とプレイヤー名をビューに渡す
    public function setup()
    {
        $current_player_name = session('current_player_name', []); // セッションからプレイヤー名を取得。デフォルトは空の配列。
        $questions = session('questions');
    
        return view('self_introduction_lie_game_setup', [
            'current_player_name' => $current_player_name, // プレイヤー名をビューに渡す
            'questions' => $questions // ランダムに取得した質問をビューに渡す
        ]);
    }

    public function storeTruthAndLie(Request $request) // 自己紹介設定画面で設問回答したら動く部分
    {
        // 真実と嘘の情報を処理・保存(自己紹介文要約）)
        $contents = $request->input('content');
        // 配列の要素を指定のフォーマットの文字列に変換
        $formattedContent = $this->formatContent($contents);
        // ChatGPT APIを呼び出し要約を生成
        // 下にあるcallGPTAPIを呼び出してる
        $response = $this->callGPTAPI($formattedContent);
        dd($response);

        // 次のプレイヤーにリダイレクト
        return $this->redirectToSetup();
    }
    
    private function formatContent(array $contents): string
    {
        $formattedContent = '';
        // 現在のプレイヤー名をセッションから取得
        $playerName = session('current_player_name', '');

        // プレイヤー名を含める
        $formattedContent .= 'プレイヤー名： ' . $playerName . "\n\n";

        // 保存してある質問（問題文）をセッションから取得
        $questions = session('questions', []);

        foreach($contents as $index => $content) {
            // 各設問に関して、セッションから取得した質問とユーザーの回答を組み合わせる
            $formattedContent .= '設問' . ($index + 1) . '： ' . ($questions[$index]->content ?? '未定義の設問') . "\n";
            $formattedContent .= '回答' . ($index + 1) . '： ' . $content . "\n";
        }
        dd($formattedContent);

        return $formattedContent;
    }

    // 設問入力画面にリダイレクトするメソッド
    // 次のプレイヤーの設問入力画面にリダイレクト
    private function redirectToSetup() 
    {
        $player_index = session('current_player_index');
        $player_names = session('player_names', []);
        $tmp_number_of_players = session('number_of_players');
        $number_of_players = $tmp_number_of_players - 1;

        // すべてのプレイヤーが設問に答えたかチェック
        if ($player_index == $number_of_players) {
            // すべてのプレイヤーが設問に答えたら要約画面にリダイレクト
            return redirect()->route('selfIntroductionLieGame.display');
        }

        // 次のプレイヤーに移り
        session(['current_player_index' => $player_index + 1]);

        // 現在のプレイヤー名をセッションに保存
        session(['current_player_name' => $player_names[$player_index + 1] ?? 'デフォルト名']);

        
        return redirect()->route('selfIntroductionLieGame.setup');
    }

    // コンテンツをAPIに送信し、結果を返す
    private function callGPTAPI($content)
    {
        $client = new Client();
        $endpoint = "https://api.openai.com/v1/chat/completions"; 

        $prompt =
        "#Instruction
        I leave the self-introduction to you.
        
        #Procedure
        
        Prepare some questions in advance and have them entered by one player at a time.
        Finally, each player will introduce themselves using the introduction sentences created by GPT.
        #Example
        For instance, the first one could be about their favorite character in Conan, the second one about their favorite food, the third one about what they would bring to a deserted island, etc.… GPT will compile these into nice self-introduction sentences and, on top of that, please include one piece of information that wasn’t asked in the questions (in other words, a false piece of information).
        
        #Prohibitions
        
        Do not disclose which information is false.
        Do not exaggerate stories from the content answered in the questions.
        (Example:
        Question 2: What was your major in your student days?
        Answer 2: English.
        Even if the answer to the question, 'What was your major in your student days?' is 'English', it does not necessarily mean they are working in a job related to English, so do not exaggerate the story.
        The false information can be something other than experience.";

        // GPTにAPI連携して問題文を生成したものを$responseとして受け取っている
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
            
        // JSONレスポンスをデコードして問題文を抽出
        $data = json_decode($response->getBody(), true);
        Log::info('Decoded JSON data:', ['data' => $data]);

        $summaryContent = $data['choices'][0]['message']['content'] ?? 'Failed to generate question';
        return $summaryContent;
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
