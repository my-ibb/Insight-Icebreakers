<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;


class QuestionController extends Controller
{
    public function index()
    {
      // 仮のデータ。実際にはデータベース等から問題のリストを取得
    $questions = [
        ['id' => 1, 'title' => 'Question 1'],
        ['id' => 2, 'title' => 'Question 2'],
        // 必要なだけ追加
    ];
    return view('questions.index', compact('questions'));
    }

    public function create()
    {
        return view('questions.create');
    }

    public function show($id)
    {
        return view('questions.show', compact('id'));
    }

    public function checkAnswer($id)
    {
        return view('questions.check', compact('id'));
    }

    public function showQuestionForm()
    {
        return view('questions.question_form');
    }

    public function showHintForm()
    {
            return view('questions.hint_form');
    }

    public function edit($id)
    {
            return view('questions.edit', compact('id'));
    }

    public function detail($id)
    {
         // ログインしていなければログインページへリダイレクト
        if (Auth::guest()) {
            return redirect()->route('login');
        }
        // 仮のデータ。実際にはデータベース等から問題の詳細を取得
        $questionDetail = [
            'id' => $id,
            'title' => 'Question ' . $id,
            'content' => 'This is a detail of question ' . $id,
            // 必要なだけ追加
        ];

            return view('questions.detail', compact('questionDetail'));
    }

    public function generateQuestion(Request $request)
    {
        $genre = $request->input('genre');
        $difficulty = $request->input('difficulty');
    
        // OpenAI APIのエンドポイントとパラメータを設定
        $endpoint = "https://api.openai.com/v1/chat/completions";
        $prompt = "#命令文
        水平思考クイズをしましょう。あなたが出題者となってください。私が回答します。
        
        以下の#手順で進めます。
        #手順
        1.あなたは#制約条件に従った問題と、それに対応する答えを考えてください。問題のみ出力し、解答は出力してはいけません。
        2.私はこれから質問をするので、その物語に基づいて「はい」または「いいえ」のみで回答してください。
        3.2.を何回か繰り返します。
        4.私が「答えを教えてください」と入力したら、解答となる物語を教えてください。
        
        #制約条件
        *問題文は100字以上で作成すること
        *起承転結がはっきりした物語を作成すること
        *一般的な視点から見て「奇妙」と思われる物語とすること
        *#例題を参考にして物語を考えること
        *登場人物や情景が思い浮かぶ内容にすること
        *日本語で出力すること
        *答えと問題をセットで表示してください
        *改行はPHPのbladeで表示できるようにbrにて行うこと
            
        #例題
        「ある男性が、海の近くのレストランでウミガメのスープを注文しました。彼はスープを一口飲んだところで、シェフに問いかけました。 『すみません、これはウミガメのスープで合っていますか？』 シェフは『はい、お客様が召し上がったのは間違いなくウミガメのスープですよ』と答えました。 男性は会計を済ませ、家に帰ると自殺してしまいました。」
        
        #例題の答え
        このクイズの答えは、男はかつて数人の仲間と海で遭難し、とある島に漂着した。食料はなく、仲間たちは生き延びるために力尽きて死んだ者の肉を食べ始めたが、男はかたくなに拒否していた。見かねた仲間の一人が、「これはウミガメのスープだから」と嘘をつき、男に人肉のスープを飲ませ、救助が来るまで生き延びさせた。男はレストランで飲んだ「本物のウミガメのスープ」とかつて自分が飲んだスープの味が違うことから真相を悟り、絶望のあまり自ら命を絶った。

        #このゲームの楽しみ方
        「ウミガメのスープ」のクイズであれば、
        質問1：男がウミガメのスープをレストランで注文するのは初めてでしたか？

        回答1：YES、初めてです。

        質問2：自殺の理由は金銭的な問題ですか？

        回答2：NO、男はお金には困っていませんでした。

        質問3：男は、ウミガメのスープを飲んだことがきっかけでなにかを知りましたか？

        回答3：YES！

        といった質問から、徐々に真相に迫っていくという楽しみ方ができます。

        #表示形式は以下でお願いします
        [問題文]
        {問題文を表示}

        {改行}

        [答え]
        {答えを表示}
        ";

        $content = "{$genre}に関する{$difficulty}の質問を作成してください。";
            
        $client = new Client();
    
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
    
        $data = json_decode($response->getBody(), true);
        $questionContent = $data['choices'][0]['message']['content'] ?? 'Failed to generate question';
    
        return redirect()->route('questions.create')->with('question', $questionContent);
    }
    public function showGenerated()
{
    $question = session('question', 'No question generated');
    return view('questions.generated', compact('question'));
}
    }
