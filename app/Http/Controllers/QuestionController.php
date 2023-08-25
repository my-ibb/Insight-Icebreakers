<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use App\Models\SoupGameQuestion;






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

    public function chatPage()//質問ボタン押してからのチャット形式
    {
    return view('questions.chat');
    }

    public function hintPage()//ヒント
    {
    // ここでGPTからヒントを取得
    $hint = "This is a hint"; // GPTから取得したヒント
    return view('questions.hint', compact('hint'));
    }

    public function checkAnswer($id)
    {
        $question = Question::find($id); // Questionモデルを使ってIDで問題を取得
        $answer = $question->answer; // 問題から答えを取得
        return view('check', ['answer' => $answer]); // 答えをビューに渡す
    }
    
    public function showQuestionForm()//いらんかも
    {
        return view('questions.question_form');
    }

    //public function showHintForm()
    //{
      //      return view('questions.hint_form');
    //}

    public function edit($id)//多分あとでいる
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
        $question = [
            'id' => $id,
            'title' => 'Question ' . $id,
            'content' => 'This is a detail of question ' . $id,
            // 必要なだけ追加
        ];

            return view('questions.detail', compact('question'));
    }

    // generateQuestion メソッドを削除または完成させる

    public function inputQuestion($id)
    {
        $question = [
            'id' => $id,
            'title' => 'Question ' . $id,
            'content' => 'This is a detail of question ' . $id,
        ];
    
        return view('questions.input', compact('question'));
    }

    public function storeQuestion(Request $request, $id)
    {
        // ここでデータベースに質問内容を保存する処理を追加します
        // ...
    
        return redirect()->route('questions.index');
    }

    public function generateQuestion(Request $request)
    {
        // OpenAI APIのエンドポイントとパラメータを設定
        $endpoint = "https://api.openai.com/v1/chat/completions";
        $prompt = "#命令文
        水平思考クイズをしましょう。
        
        水平思考ゲームとは、出題者が読み上げる謎の物語に対して、回答者が「はい」か「いいえ」、もしくは「関係ありません」のいずれかで答えられる質問を繰り返すことで、状況を整理して真相を推理する形式のクイズです。
        あなたには、水平思考ゲームの問題作成のプロとして、問題文を指定されたジャンル・難易度で最適に作成いただきたいです。
        

        ーーーーーー
        以下に例題を記載します。参考にしてください。

        #例題1
        ある男性が、海の近くのレストランでウミガメのスープを注文しました。彼はスープを一口飲んだところで、シェフに問いかけました。 『すみません、これはウミガメのスープで合っていますか？』 シェフは『はい、お客様が召し上がったのは間違いなくウミガメのスープですよ』と答えました。 男性は会計を済ませ、家に帰ると自◯してしまいました。なぜ男性は自○をしたのでしょうか？
        
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

        #例題5
        隣で仕事をしていた同僚の服が破れた瞬間、彼はその同僚が死ぬことを悟った。なぜだろうか？

        #例題5の答え
        ２人は宇宙飛行士。宇宙歩行中に宇宙服が破れてしまったのだった。

        #例題6
        ある女性が菜園の土を耕していると、現金や宝石がぎっしり詰まった箱が出てきた。女性は7年ものあいだ、その金に手をつけず、宝箱のことを誰にも話さなかった。しかし7年が過ぎたとき、彼女は突然、新しい車や家、毛皮のコートなどを買い始めた。きっかけは何？

        #例題6の答え
        女性は船で旅行中、遭難事故に遭い、孤島でひとり救助を待っていた。海賊が隠した財宝を見つけたはいいが、救助されるまでに7年もの歳月を要してしまったのだ。

        #例題7
        ある会社の採用面接を受けた男。にぎやかなオフィスに到着すると、受付係から、書類に記入した上で呼び出しを待つように言われた。男は記入を済ませ、先に到着していた4人の受験者と一緒に待った。数分後、男は立ち上がって奥のオフィスに入ると、採用を告げられた。男より先に来ていた受験者4人が人事担当者に詰め寄ると、担当者はなぜ男だけが選ばれたかを説明した。それはどんな理由だっただろうか？

        #例題7の答え
        これは19世紀の話。面接に来ていたのは、電信技師の採用試験を受ける人々だった。オフィスの騒音に紛れて、モールス信号でこんなメッセージが伝えられた。「もしこれを理解できたら、奥のオフィスへどうぞ」。受験者の技量と注意力を試すテストだ。この試験に合格したのは、男ひとりだけだった。 

        #例題8
        女が歩いていると突然男に羽交い絞め（はがいじめ）をされた。男は感謝され称賛された。なぜでしょうか？

        #例題8の答え
        ある女子高生はスマホに夢中で赤信号の横断歩道を渡っていた。
        それに気づいた男性は慌てて羽交い絞めをし歩道に引き戻した。
        彼女に怪我はなく人命救助に成功した男は称賛された。

        ーーー

        #このゲームの楽しみ方
        例題である「ウミガメのスープ」のクイズであれば、
        以下のような質問と回答のやりとりから、徐々に真相に迫っていくという楽しみ方ができます。
        なので、答えはあまりに非現実的だと納得感がなく、かといって直接的すぎるとゲームの楽しみがないので、そこの塩梅を上手に調整してください。

        ```
        質問1：男がウミガメのスープをレストランで注文するのは初めてでしたか？

        回答1：YES、初めてです。

        質問2：自殺の理由は金銭的な問題ですか？

        回答2：NO、男はお金には困っていませんでした。

        質問3：男は、ウミガメのスープを飲んだことがきっかけでなにかを知りましたか？

        回答3：YES！
        ```

        ーーー

        それでは、問題の作成について、以下の#手順で進めます。よろしくお願いします。
        #手順
        1.あなたは#制約条件と#禁止事項に従った問題文と、それに対応する答えを考え、#表示形式に従って出力してください。
        
        #制約条件
        * [問題文]は70文字以上、250字以内で作成すること
        * [答え]は、起承転結を踏襲した物語であること
        * 先ほど記載したゲームの例題を参考にして問題を考えること
        * 登場人物や情景が思い浮かぶ内容にすること
        * [問題文]はそれ自体では一見矛盾しているようなシチュエーションとなっていること
        * [答え]は、一般的な道徳観、倫理観に沿ったものになること
        * 日本語で出力すること
        * [問題文]の最後は必ず疑問文の形で終わること（〜なのはなぜでしょう？など）
        
        #禁止事項
        * [答え]の種類として、夢オチは使わないこと。
        * [答え]の種類として、あまりに非現実的な設定は使わないこと。

        #表示形式
        [問題文]：
        {問題文を表示}

        [答え]：
        {答えを表示}

        ";

        $genre = $request->input('genre');
        $difficulty = $request->input('difficulty');
        $content = "ジャンルとしては{$genre}系の問題で、難易度は「{$difficulty}」レベルとして作成してください。";            
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
    
        $data = json_decode($response->getBody(), true);
        $questionContent = $data['choices'][0]['message']['content'] ?? 'Failed to generate question';

        // ここで正規表現を使って $question_content と $answer_content を抜き出す
        if (preg_match('/\[問題文\]：(.*?)\[答え\]：/su', $questionContent, $matches)) {
        $question_content = trim($matches[1]);
        }

        if (preg_match('/\[答え\]：(.*)$/su', $questionContent, $matches)) {
        $answer_content = trim($matches[1]);
        }
    
        return redirect()->route('questions.create')->with('question', $questionContent);
    }

    public function showGenerated()
    {
        $question = session('question', 'No question generated');
        return view('questions.generated', compact('question'));
    }

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

    public function generateChatResponse(Request $request)
    {
        $questionID = $request->input('chatQuestionID');

        // 質問をデータベースから取得
        $question = SoupGameQuestion::find($questionID);
        if (!$question) {
            return redirect()->route('questions.chat')->with('answer', 'Question not found');
        }

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

    // 質問内容をデータベースから取得
    $question = SoupGameQuestion::find($questionID);
    if (!$question) {
        // 質問が見つからない場合の処理
        return redirect()->route('questions.hint')->with('hint', 'Question not found');
    }

    // GPT APIを使ってヒントを取得するロジック
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
            'max_tokens' => 50 // この部分は調整が必要かもしれません
        ]
    ]);
    $data = json_decode($response->getBody(), true);
    $hint = $data['choices'][0]['text'] ?? 'Failed to generate hint';

    return redirect()->route('questions.hint')->with('hint', $hint);
}
}
