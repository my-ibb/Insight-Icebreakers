<?php

namespace App\Http\Controllers;

// 必要なクラスをインポート
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use App\Models\SoupGameQuestion;
use Illuminate\Support\Facades\Log;
// QuestionController クラスの定義。Controller クラスを継承。
class QuestionController extends Controller
{
    // 問題一覧ページを表示
    public function index()
    {
        // SoupGameQuestion モデルを使用してデータベースから全ての問題を取得
        // データベースから新着順でデータを取得
        $questions = SoupGameQuestion::orderBy('created_at', 'desc')->get();    
        return view('questions.index', compact('questions'));
    }
    
    // 新しい問題を作成するフォームを表示
    public function create()
    {
        //未ログインだとログインフォームにリダイレクトされる
        if (Auth::guest()) {
            return redirect()->route('login');
        }
        return view('questions.create');
    }

    // 特定のIDの問題の答えをチェック
    public function checkAnswer($id)
    {
        $question = SoupGameQuestion::find($id);
        $answer = $question->answer;
        return view('check', ['answer' => $answer]);
    }

    // 問題フォームを表示（使わないかも）
    public function showQuestionForm()
    {
        return view('questions.question_form');
    }

    // 問題を編集するためのフォームを表示
    public function edit($id)
    {
        return view('questions.edit', compact('id'));
    }

    // 問題の詳細ページを表示
    public function detail($id)
    {
        // ゲストユーザーはログインページにリダイレクト
        if (Auth::guest()) 
        {
            return redirect()->route('login');
        }
    // SoupGameQuestion モデルを使用して、指定されたIDに対応する問題を取得
        $question = SoupGameQuestion::find($id);
    // もし問題が存在しなければ、404エラーを返す
        if (!$question) 
        {
            abort(404, 'The specified question does not exist.');
        }
        return view('questions.detail', compact('question'));
    }

    // 問題を入力するページを表示
    public function inputQuestion($id)
    {
        $question = [
            'id' => $id,
            'title' => 'Question ' . $id,
            'content' => 'This is a detail of question ' . $id,
        ];
        return view('questions.input', compact('question'));
    }

    // 問題をデータベースに保存
    public function storeQuestion(Request $request, $id)
    {
        // ここでDBに保存する処理を書く
        return redirect()->route('questions.index');
    }

    // 問題を生成（このメソッドは未完成または削除される）
    public function generateQuestion(Request $request)
    {
        // OpenAI APIのエンドポイントとパラメータを設定
        //OpenAI APIのエンドポイントURLを保持
        $endpoint = "https://api.openai.com/v1/chat/completions";
        //APIに送信する命令文または質問を保持
        $prompt = "#Instructions
        Let's play a lateral thinking quiz.
    
        In the lateral thinking game, participants ask questions that can only be answered with 'Yes', 'No', or 'Irrelevant' to unravel the mystery presented by the quiz master. You are a professional at creating lateral thinking game questions, and we would like you to optimize your problem statements according to the specified genre and difficulty levels.
    
        ------------
        Below are some example questions. Please refer to them.
    
        #Example 1
        A man ordered turtle soup at a restaurant near the sea. After taking a sip, he asked the chef, 'Excuse me, is this really turtle soup?' The chef responded, 'Yes, what you're having is undoubtedly turtle soup.' The man paid the bill, went home, and ended his own life. Why did the man take such a drastic step?
    
        #Answer to Example 1
        The answer to this quiz is that the man had once been stranded at sea with several companions. With no food, they began to eat the flesh of those who had died, but the man stubbornly refused to partake. One of his companions lied to him, saying it was 'turtle soup,' allowing him to survive until they were rescued. When he tasted the 'real' turtle soup at the restaurant, he realized the truth and took his own life out of despair.
    
        #Example 2
        Yuu, who loves sweet bean buns, headed for his favorite sweet bean bun shop. Upon seeing a sold-out sign, he was delighted. Why?
    
        #Answer to Example 2
        The sweet bean bun shop was Yuu's own shop. He was delighted that his handmade sweet bean buns were popular with the customers.
    
        #Example 3
        Morita, a taxi driver, was driving the wrong way down a one-way street. Although spotted by a patrolling police officer, he wasn't scolded. Why?
    
        #Answer to Example 3
        Morita was not actually in his taxi but was walking in the opposite direction on the one-way street. While cars have to abide by the one-way rules, walking does not.
    
        #Example 4
        In a hospital, a man was crying loudly. Visitors who came to see him shed tears and also smiled. A woman hugged the crying man. Who is the man?
    
        #Answer to Example 4
        The 'man' was actually a newborn baby. Those who came to visit shed tears of joy and smiled at the sight of the new life. The woman hugging him was his mother.
    
        ------------
    
        #How to enjoy this game
        You can enjoy figuring out the truth through a series of questions and answers, like in the example question about turtle soup. Hence, the answer should neither be too unrealistic nor too direct, to maintain the fun element of the game.
    
        #Procedure
        1. Following the #constraints and #prohibitions, please come up with a question and corresponding answer, formatted according to the #display_format.
    
        #Constraints
        * [Question] must be between 70 and 250 characters
        * [Answer] should be a story that follows a logical flow
        * Reference the example questions for guidance
        * Make sure the characters and scenes are vivid
        * [Question] should appear paradoxical at first glance
        * [Answer] should align with common moral and ethical perspectives
        * Output in Japanese
        * [Question] must end in a form of a question (Why did he do that?, etc.)
    
        #Prohibitions
        * Don't use a 'it was a dream' ending for the [Answer]
        * Don't use overly unrealistic settings for the [Answer]
    
        #Display format
        [Question]:
        {Display the question}
    
        [Answer]:
        {Display the answer}";

    // $prompt = "#指示
    // 水平思考クイズを遊びましょう。

    // 水平思考ゲームでは、参加者は「はい」「いいえ」「関係ない」のいずれかだけで答えられる質問を出して、クイズマスターが出す謎を解明します。あなたは水平思考ゲームの問題作成のプロであり、指定されたジャンルと難易度に合わせて、問題文を最適化してください。

    // --------------
    // 以下は一例です。参考にしてください。

    // #例1
    // ある男が海の近くのレストランで亀のスープを頼みました。一口飲んでからシェフに「これは本当に亀のスープですか？」と尋ねました。シェフは「はい、あなたが食べているのは間違いなく亀のスープです」と答えました。その男は料金を支払い、帰宅して自らの命を絶った。なぜそんな極端な行動をとったのでしょうか？

    // #例1の答え
    // このクイズの答えは、その男が以前、何人かの仲間と海で遭難していたということです。食べ物がなく、死んだ者の肉を食べ始めましたが、その男は頑としてそれを食べないでいました。仲間の一人が「これは亀のスープだ」と嘘をついて、彼が生き延びることができました。レストランで「本物の」亀のスープを味わったとき、彼は真実を悟り、絶望から自らの命を絶ったのです。

    // #例2
    // (以下、他の例も続く)
    // --------------
    // #ゲームの楽しみ方
    // 亀のスープについての例の質問のように、一連の質問と回答を通じて真実を解き明かす楽しみがあります。したがって、答えは現実離れしているわけでも、あまりにも直訳ではなく、ゲームの楽しさを保つべきです。

    // #手順
    // 1. #制約 と #禁止事項 に従い、#表示形式に従って質問と対応する答えを作成してください。

    // #制約
    // * [質問] は70文字から250文字の間でなければならない
    // * [答え] は論理的な流れに従ったストーリーでなければならない
    // * 例の質問を参考にしてください
    // * 登場人物や場面が生き生きとしていること
    // * [質問] は一見すると逆説的に見えるべき
    // * [答え] は一般的な道徳観や倫理観に合致するものでなければならない
    // * 出力は日本語で
    // * [質問] は疑問形で終わる（なぜそうしたのか？など）

    // #禁止事項
    // * [答え] の結末で「それは夢だった」としないこと
    // * [答え] で現実離れした設定を使わないこと

    // #表示形式
    // [質問]:
    // {質問を表示}

    // [答え]:
    // {答えを表示}";

        // フォームからジャンルと難易度を取得
        $genre = $request->input('genre');
        $difficulty = $request->input('difficulty');
        $content = "ジャンルとしては{$genre}系の問題で、難易度は「{$difficulty}」レベルとして作成してください。"; 
        // GuzzleHTTPクライアントのインスタンスを作成           
        $client = new Client();
    
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
        $questionContent = $data['choices'][0]['message']['content'] ?? 'Failed to generate question';

        // ここで正規表現を使って $question_content と $answer_content を抜き出す
        if (preg_match('/\[Question\]:(.*?)\[Answer\]:/su', $questionContent, $matches)) {
        $question_content = trim($matches[1]);
        }

        if (preg_match('/\[Answer\]:(.*)$/su', $questionContent, $matches)) {
        $answer_content = trim($matches[1]);
        }

        // 生成された問題を次のページで表示するためにリダイレクト
        // Controller
        return redirect()->route('questions.create')
            ->with('question_content', $question_content)
            ->with('answer_content', $answer_content)
            ->with('genre', $genre)
            ->with('difficulty', $difficulty);
    }
        // 生成された問題を表示するメソッド
    public function showGenerated()
    {
        // セッションから問題を取得してビューに渡す
        $question = session('question', 'No question generated');
        return view('questions.generated', compact('question'));
    }
    // 新規の問題をデータベースに保存するメソッド
    public function store(Request $request)
    {
        // 新規質問と回答をデータベースに保存するロジック
        $newQuestion = new SoupGameQuestion();
        $newQuestion->question_content = 'Your Question Content Here';
        $newQuestion->answer_content = 'Your Answer Content Here';
        $newQuestion->genre = 'Your Genre Here';
        $newQuestion->difficulty = 'Your Difficulty Level Here';
        $newQuestion->user_id = 1; // 例: Auth::id() などで取得する
        $newQuestion->save();

        return redirect()->route('questions.create');
    }

    public function saveQuestion(Request $request) {
        $data = [
            'generated_question' => $request->input('question_content'),
            'generated_answer' => $request->input('answer_content'),
            'genre' => $request->input('genre'),
            'difficulty' => $request->input('difficulty'),
        ];
    
        // モデルを通してデータベースに保存
        $result = SoupGameQuestion::storeNewQuestion($data);
    
        // 保存成功時の処理
        if($result) {
            return redirect()->route('questions.index')->with('success', 'Question saved successfully.');
        }
    
        // 保存失敗時の処理
        return redirect()->route('questions.index')->with('error', 'Failed to save the question.');
    }
    
    // ーーーーーーーーーーーヒント関連はここからーーーーーーーーーーー
    public function getHint(Request $request, $questionId)
    {
        // クエリパラメータからこれまでのヒントを取得
        $previousHints = $request->query('previousHints', '');
        $hintCount = $request->query('hintCount', 1);
    
        // 既存のコード：問題の取得など
        $question = SoupGameQuestion::find($questionId);

        if (!$question) {
            return response()->json(['error' => 'Question not found'], 404);
        }
    
        $endpoint = "https://api.openai.com/v1/chat/completions";

        $prompt =         
        "#Instructions
        You are an AI trained to provide subtle hints for lateral thinking puzzles. The player is stuck and needs a hint to move forward.
        
        #Procedure
        1. Provide a hint that subtly guides the player in the right direction without giving away the answer.
        2. The hint must be concise, limited to one or two sentences.
        3. The hint must be relevant to the puzzle.
        4. Output in Japanese.
        
        #Restrictions
        Do not include text that is identical to the content of the answer.
        
        #Please provide a hint";

    // #指示
    // あなたは水平思考パズルに対するヒントを提供する訓練を受けたAIです。プレイヤーは行き詰っていて、前に進むための微妙なヒントが必要です。

    // #手順
    // 1. 答えを明かさないように、プレイヤーを正しい方向に微妙に導くヒントを提供してください。
    // 2. ヒントは簡潔で、一つまたは二つの文でなければなりません。
    // 3. ヒントはパズルに関連していなければなりません。
    // 4. 出力は日本語で行ってください。

    // #禁止事項
    // 答えの内容と全く同じ文章を入れない

    // #ヒントを提供してください

        $content = "#Puzzle: {$question->question_content}
                    #Answer: {$question->answer_content}"; 

         // hintCountが1より大きい場合、前回のヒントをcontentに追加
        if ($hintCount > 1) {
        $content .= "\n#Previous Hints: {$previousHints}";
    }

        // GuzzleHTTPクライアントのインスタンスを作成           
        $client = new Client();
    
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
        
        // JSONレスポンスをデコードしてヒントを抽出
        $data = json_decode($response->getBody(), true);
        $generated_hint = $data['choices'][0]['message']['content'] ?? 'Failed to generate question';

    
        // API呼び出しを行い、ヒントを生成（この部分はOpenAI APIと通信する実際のコードに置き換えてください）
        //$generated_hint = "これは生成されたヒントです";
    
        // JSONとしてヒントを返す（フロントエンドのJavaScriptで受け取る）
        return response()->json(['hint' => $generated_hint]);
    }
    // ーーーーーーーーーーーヒント関連はここまでーーーーーーーーーーー


    // ーーーーーーーーーーー質問関連はここからーーーーーーーーーーー
    // OpenAI APIを使って問題に対する回答を生成するメソッド
// OpenAI APIを使って問題に対する回答を生成するメソッド
public function generateChatResponse(Request $request)
{
    $chatQuestionContent = $request->input('chatQuestionContent');
    
    $questionId = $request->input('questionId');  // 質問IDも取得
    // 質問回数をセッションから取得、もしくは初期化
    $questionCount = $request->session()->get('question_count', 0);
    $questionCount++;

    // 問題の取得
    $question = SoupGameQuestion::find($questionId);

    if (!$question) {
        Log::error("Question not found for ID: " . $questionId);
        return response()->json(['error' => 'Question not found'], 404);
    }
    Log::info("Received chatQuestionContent: " . $chatQuestionContent); 
    Log::info("Question Content: " . $question->question_content);
    Log::info("Answer Content: " . $question->answer_content);
    Log::info("Chat Question Content: " . $chatQuestionContent);
    Log::info("Answer Content: " . $question->answer_content);

    // デバッグ：受け取った質問やセッションデータを出力
    error_log("Received chatQuestionContent: " . $chatQuestionContent);
    error_log("Session question_count: " . $questionCount);
        
    if (!$question) {
        return response()->json(['error' => 'Question not found'], 404);
    }

    $endpoint = "https://api.openai.com/v1/chat/completions";
    
    // プロンプト
    // 1回目の質問、2回目の質問、3回目の質問...と追加情報をプロンプトに含める
    // $prompt = "1st Question: {$request->session()->get('1st_question', 'N/A')}
    //         2nd Question: {$request->session()->get('2nd_question', 'N/A')}
    //         ...";

    $prompt =
    "1.The answer to the question should be 'イエス', 'ノー', or 'どちらでもない'.
    2.If the content of the question is linked to the answer, respond with 'イエス'. 
    3.If the content of the question is not linked to the answer, respond with 'ノー'.
    4.The answer to the question should be provided in Japanese.
    5. For questions unrelated to the problem, display 'どちらでもない'.
    6. Format for responses Example >>  質問: (Content of the entered question is written here) 回答: どちらでもない
    ";

    
    // ユーザーからの質問を含めます
    $content = "#Puzzle: {$question->question_content}
                #Answer: {$question->answer_content}
                #User Question: {$chatQuestionContent}";

    // GuzzleHTTPクライアントのインスタンスを作成
    $client = new Client();

    // API連携して回答を生成
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
    
    // JSONレスポンスをデコードして回答を抽出
    $data = json_decode($response->getBody(), true);
    $generated_answer = $data['choices'][0]['message']['content'] ?? 'Failed to generate answer';

    // デバッグ：APIからのレスポンスを出力
    error_log("API Response: " . json_encode($data));

    // 最新の質問をセッションに保存
    $request->session()->put("{$questionCount}th_question", $chatQuestionContent);

    // 過去の質問をセッションに保存（配列として）
    $previousQuestions = $request->session()->get('previous_questions', []);
    $previousQuestions[] = $chatQuestionContent;
    $request->session()->put('previous_questions', $previousQuestions);


    // JSONとして回答を返す
    return response()->json(['answer' => $generated_answer]);
}

public function getAnswer(Request $request)
{
    // ユーザーからの質問を取得
    $userQuestion = $request->input('question');

    // GPT-3へのプロンプトを形成
    $prompt = "Q: {$userQuestion}\nA:";

    // GPT-3 APIへのリクエスト（以下は仮のコード、実際のAPIリクエストに置き換えてください）
    $apiResponse = 'ここにAPIからの回答';

    // APIからの回答をレスポンスとして返す
    return response()->json(['answer' => $apiResponse]);
}

    // ーーーーーーーーーーー質問関連はここまでーーーーーーーーーーー
}    