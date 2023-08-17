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
        *読者がまず疑問に思い、質問をしたくなるような物語にすること
        *#例題を参考にして物語を考えること
        *登場人物や情景が思い浮かぶ内容にすること
        *日本語で出力すること
        *答えと問題をセットで表示すること
        *問題文中に一見すると関係ない余談や情報を複数散りばめることで、読者がどの情報が重要であるのかを見極めるのが難しくなるようにする。
        *問題文と答えは、一般的な日本人が聞いて納得できるような内容であること。
        *答えを聞いた際に納得感が何よりも大事です。文章にない突拍子もない答えはNGです。
        *問題文の最後に疑問文を挿入すること。
        
        #禁止事項
        *答えの種類として、夢オチは使わないこと。
        *答えの種類として、あまりに非現実的な設定は使わないこと。

        #例題1
        ある男性が、海の近くのレストランでウミガメのスープを注文しました。彼はスープを一口飲んだところで、シェフに問いかけました。 『すみません、これはウミガメのスープで合っていますか？』 シェフは『はい、お客様が召し上がったのは間違いなくウミガメのスープですよ』と答えました。 男性は会計を済ませ、家に帰ると自殺してしまいました。なぜ男性は自殺をしたのでしょうか？
        
        #例題1の答え
        このクイズの答えは、男はかつて数人の仲間と海で遭難し、とある島に漂着した。食料はなく、仲間たちは生き延びるために力尽きて死んだ者の肉を食べ始めたが、男はかたくなに拒否していた。見かねた仲間の一人が、「これはウミガメのスープだから」と嘘をつき、男に人肉のスープを飲ませ、救助が来るまで生き延びさせた。男はレストランで飲んだ「本物のウミガメのスープ」とかつて自分が飲んだスープの味が違うことから真相を悟り、絶望のあまり自ら命を絶った。

        #例題2
        「あんぱんが大好きなユウくん。一番おいしいと思うあんぱんのお店へ向かったユウくんは、売り切れの札を見て喜んでいます。」なぜでしょうか？

        #例題2の答え
        そのあんぱんのお店は、ユウくんのお店だったのです。ユウくんは自分の作ったあんぱんがお客さんに大人気なのを見て、喜んだ。

        #例題3
        「タクシー運転手をしている森田さん。ある時森田さんは、一方通行の道を逆方向に走っていました。パトロール中の警察官に見られてしまいましたが、怒られませんでした。」なぜでしょうか？ 
        
        #例題3の答え
        タクシー運転手さんは車に乗っておらず、一方通行の道を徒歩で逆方向に進んでいただけだったのです。車が一方通行でも、歩きならば関係ありませんよね。「タクシー運転手」というと車に乗っているとイメージしてしまいますが、歩くときだってあるはずです。

        #例題4
        「ある病院に、とても大きな声で泣いている男がいた。その男に会いに来た人は、涙を流したり、ニコニコ笑ったりした。泣いている男を抱く女もいた。」男の正体は？
        
        #例題4の答え
        その「男」は、産まれたばかりの赤ちゃんだったのです。男の子が大きな声で泣く様子を見て、お見舞いに来た人は嬉し涙を流したり笑顔になったりしました。お母さんはそんなわが子を、大切そうに抱きかかえたのです。


        #このゲームの楽しみ方
        例題である「ウミガメのスープ」のクイズであれば、
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

        [答え]
        {答えを表示}

        [想定される質問とその答え]
        {質問1：}
        {解答1：YES, xxxx}
        {質問2：}
        {解答2：NO, xxxx}

        ";

        $content = "ジャンルとしては{$genre}系の問題で、難易度は{$difficulty}くらいの質問を作成してください。";            
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
