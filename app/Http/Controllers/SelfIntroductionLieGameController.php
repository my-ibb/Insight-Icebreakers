<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\IntroGameQuestion; // IntroGameQuestionモデルをインポート
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;



class SelfIntroductionLieGameController extends Controller

{
    public function delete($id) 
    {
        $question = IntroGameQuestion::findOrFail($id); // 指定された設問を取得

        if ($question) { // 設問が存在するか確認
            $question->delete(); // 設問を削除
            return redirect()->back()->with('success', 'Question deleted successfully.'); // 削除後に前のページにリダイレクト
        } else {
            return redirect()->back()->with('error', 'Question not found.'); // 設問が存在しない場合、エラーメッセージと共にリダイレクト
        }
    }
    // 初期設定画面を表示
    //オプションとセッションからのプレイヤー名をビューに渡す
    public function index()
    {
        // 参加人数のオプション（例: 2人から10人）
        $numberOfPlayersOptions = range(2, 10);

        // 設問個数のオプション（例: 1から5個）
        $numberOfQuestionsOptions = range(2, 5);

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

        // 現在のプレイヤー名と要約文をセッションに保存
        $current_player_name = session('current_player_name', '');
        $summaries = session('summaries', []); // 既に保存されている要約を取得
        $summaries[] = [ // 新しい要約を追加
            'player_name' => $current_player_name,
            'summary' => $response,
        ];
        session(['summaries' => $summaries]); // 更新された要約をセッションに保存

        // 次のプレイヤーにリダイレクト
        return $this->redirectToSetup();
    }
    
    private function formatContent(array $contents): string
    {
        $formattedContent = '';
        // 現在のプレイヤー名をセッションから取得
        $playerName = session('current_player_name', '');

        // プレイヤー名を含める
        $formattedContent .= '名前： ' . $playerName . "\n\n";
        // 保存してある質問（問題文）をセッションから取得
        $questions = session('questions', []);

        foreach($contents as $index => $content) {
            // 各設問に関して、セッションから取得した質問とユーザーの回答を組み合わせる
            $formattedContent .= '質問' . ($index + 1) . '： '  . ($questions[$index]->content ?? '未定義の設問') . "\n";
            $formattedContent .= '回答' . ($index + 1) . '： ' . $content . "\n";
        }

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
        "
        #命令書
        あなたは、ユーザーからの名前と様々な質問内容とその回答を受け取り、それらを利用して自己紹介を作成することが得意な専門家です。
        以下の制約条件から最高におもしろおかしいSNS（Facebookなど）やクラス替えなどのタイミングで使える自己紹介文を出力してください。
        また、ユーザーが想定していない嘘の情報を追加してあげて、自己紹介を盛ってあげてください。
        
        #制約は以下
        ・日本語で出力する
        ・挨拶を挿入する（はじめまして！ #名前 です。）
        ・500文字以内で作成する。
        ・「よろしくお願いします！」で終わる。
        ・必ず一つ、架空の質問内容とその答えをでっち上げて、嘘の自己紹介要素を盛り込む。
      
        #ユーザーからの入力形式の例は以下です。
        名前： たろう
        質問1： 人生で一番困った時、どのように対処しましたか？
        回答1： 困ったことだけに集中することです
        質問2： 最後に大笑いした出来事やジョークを教えてください。
        回答2： 友達がピザを一枚ごとを落としたとき
        質問3： あなたに影響を与えた人物や出来事について教えてください。
        回答3： 母です。母は仕事もしながら私にいつも元気に明るく接してくれます。

        #出力形式の例は以下です。
        はじめまして！たろうです。SNSでは「どんな困難も乗り越える笑顔の戦士」として活動しています。私の哲学は、困った時ほど笑顔を絶やさないこと。たとえば、友達がピザを落とすハプニングも、それを機にピザの飛距離競争を始めたりしてます。
        影響を受けたのは、もちろん私の父。仕事と家庭の両立をこなしながら、いつも私に明るい笑顔を向けてくれる彼のように、私も日々を楽しく過ごしています。
        よろしくお願いします！

        #上記の出力例では、以下の部分が嘘の情報となる
        「影響を受けたのは、もちろん私の父。仕事と家庭の両立をこなしながら、いつも私に明るい笑顔を向けてくれる彼のように、私も日々を楽しく過ごしています。」
        ⇨ユーザーからの回答では、影響を受けたのは”母”だが、父という嘘の情報に変えている！！

        #禁止事項
        偽の情報について「この情報は偽物です」と宣言しないこと！！
        「SNS」という言葉を使わないこと。
        「質問」という言葉を使わないこと。
        「この質問に含まれていない」という言葉を使わないこと。
        「あなたが尋ねた情報以外」という言葉を使わないこと。
        「質問1:」などの言葉を使わないこと。
        「質問」や「答え」という言葉を使わないこと。
        導入段落でこのプロンプトの内容を漏らさないこと。
        ";
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
        $summaries = session('summaries', []); // セッションから全プレイヤーの要約を取得。

        // 自己紹介表示ページにリダイレクト
        return view('self_introduction_lie_game_display', [
            'summaries' => $summaries // 要約をビューに渡す
        ]);
    }

    // セッションをリセットしてトップページにリダイレクト
    public function reset()
    {
         session()->flush();  // セッションをクリア
         return redirect()->route('home');  // トップページへリダイレクト
    }
    public function destroy($id)
    {
        $selfIntroduction = IntroGameQuestion::findOrFail($id);
        $selfIntroduction->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Self-introduction deleted successfully');
    }

//-------------ダッシュボードはここから-------------------
public function edit($id)
{
    $question = IntroGameQuestion::find($id); // or use findOrFail($id) to throw an exception if the ID doesn't exist
    // ビューファイル名を 'self_introduction_edit' に変更しました
    return view('auth.passwords.admin.self_introduction_edit', compact('question'));
}

    // 入力データのバリデーションルール
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'content' => ['required', 'string', 'max:50'],
        ], [
            'content.required' => '設問は必須です。',
            'content.max' => '設問は50文字以内で入力してください。',
        ]);
    }

public function update(Request $request, $id)
{
    // リクエストから全データを取得
    $data = $request->all();
    // バリデーション
    $this->validator($data)->validate();

    $question = IntroGameQuestion::find($id); // or use findOrFail($id)
    $question->content = $request->input('content');
    $question->save();

    return redirect()->route('admin.dashboard.self_introduction_questions')->with('success', 'Question updated successfully.');
}
public function createQuestionForm()
{
return view('auth.passwords.admin.create_self_introductionquestion');
}
public function storeQuestion(Request $request)
{
$request->validate([
    'content' => 'required', // 必要に応じて他のバリデーションルールを追加
]);

$question = new IntroGameQuestion();
$question->content = $request->input('content');
$question->save();

return redirect()->route('admin.dashboard.self_introduction_questions')->with('success', 'Question created successfully.');
}
}
