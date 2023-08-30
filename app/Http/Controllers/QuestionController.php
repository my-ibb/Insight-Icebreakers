<?php

namespace App\Http\Controllers;

// 必要なクラスをインポート
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use App\Models\SoupGameQuestion;

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

    // チャット形式の質問ページを表示
    public function chatPage()
    {
        return view('questions.chat');
    }

    // ヒントページを表示
    public function hintPage()
    {
        $hint = "This is a hint";
        return view('questions.hint', compact('hint'));
    }

    // 特定のIDの問題の答えをチェック
    public function checkAnswer($id)
    {
        $question = Question::find($id);
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
        if (Auth::guest()) {
            return redirect()->route('login');
        }
        $question = [
            'id' => $id,
            'title' => 'Question ' . $id,
            'content' => 'This is a detail of question ' . $id,
        ];
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
        {Display the answer}
    
        ";
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
    // OpenAI APIを使って問題に対する回答を生成するメソッド
    public function generateChatResponse(Request $request)
    {
        $questionID = $request->input('chatQuestionID');
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
        // コメントアウトされている部分
    /*
    public function fetchQuestion()
    {
        $question = SoupGameQuestion::find($questionID);
        if (!$question) {
            return redirect()->route('questions.chat')->with('answer', 'Question not found');
        }
    }
    */
    
    public function generateAnswer()
    {
        $client = new Client();
        $response = $client->post("https://api.openai.com/v1/chat/completions", [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'model' => "gpt-3.5-turbo-0613",
                'prompt' => $question->question_content,
                'max_tokens' => 5
            ]
        ]);
        $data = json_decode($response->getBody(), true);
        $answer = $data['choices'][0]['text'] ?? 'Failed to generate answer';
        return redirect()->route('questions.chat')->with('answer', $answer);
    }
    
    public function storeGeneratedQuestion(Request $request)
    {
        $data = $request->all();
        SoupGameQuestion::storeNewQuestion($data);
        return redirect()->route('questions.index');
    }
    
    public function generateHint(Request $request)
    {
        $questionID = $request->input('questionID');
        $question = SoupGameQuestion::find($questionID);
        if (!$question) {
            return redirect()->route('questions.hint')->with('hint', 'Question not found');
        }
        $client = new Client();
        $endpoint = "https://api.openai.com/v1/chat/completions";
        $response = $client->post($endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'model' => "gpt-3.5-turbo-0613",
                'prompt' => "Provide a hint for the following question: " . $question->question_content,
                'max_tokens' => 50
            ]
        ]);
        $data = json_decode($response->getBody(), true);
        $hint = $data['choices'][0]['text'] ?? 'Failed to generate hint';
        return redirect()->route('questions.hint')->with('hint', $hint);
    }
}    