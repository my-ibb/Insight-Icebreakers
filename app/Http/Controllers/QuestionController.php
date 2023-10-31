<?php

namespace App\Http\Controllers;

// 必要なクラスをインポート
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use App\Models\SoupGameQuestion;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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

    // 正解判定のための処理はここ！！！
    public function checkAnswer(Request $request, $id)
    {
        $question = SoupGameQuestion::find($id);
        $correctAnswer = $question->answer_content;
        $questionContent = $question->question_content;

        $endpoint = "https://api.openai.com/v1/chat/completions";
        $client = new Client();

        // ---- ここから要約のためのAPI通信の一連の処理 -------
        // 1. 元の正解文を要約するためのプロンプトを定義
        $summaryPrompt = "
        # Background
        We are developing a web service for solving lateral thinking puzzles using the ChatGPT API. 
        With the help of AI (ChatGPT), we have implemented a process that spans from automatic question creation to answering.
        The question creation part has already been implemented using a separate prompt, and the created questions are stored in a database.
        Users solve these puzzles and submit their answers to the service.
        This prompt is used to determine the correctness of the answers submitted by the users.
        Due to the nature of lateral thinking puzzles, the determination of the answer is based on the match in meaning or nuance, rather than an exact match in the text.

        # Instructions
        Given the 'Question content' and the 'Correct answer', your task is to create a concise summary that captures the main information and nuance of the correct answer. This summary will be used as a standard for determining the similarity of user-submitted answers in terms of meaning.

        Please ensure that the summary is:
        - Concise: It should be a shortened version of the correct answer, focusing on the main points.
        - Informative: It should capture the main information and nuance of the correct answer.
        - Neutral: Avoid adding any personal opinions or interpretations.

        #Constraints
        - Output in Japanese

        ## Example
        **Question content**: Why was Morita-san looking out of the window during his overtime?
        **Correct answer**: Morita-san, during his overtime, was curious about the scream he heard from outside. When he looked out of the building's window, he saw a dead body lying in the alley and a man nearby. Morita-san realized he had witnessed the man committing murder. The man noticed Morita-san, who might have seen the murder, and started counting the floors of the building by pointing and saying, '1st floor, 2nd floor, 3rd floor...' to identify which floor Morita-san was on, intending to silence him.

        **Expected Summary**: Morita-san witnessed a man committing murder in the alley from his building during his overtime. The man tried to identify Morita-san's floor by counting the floors.
        ";

        $summaryContent = "#Question content
                            ```
                            {$questionContent}
                            ```
                            #Correct answer
                            ```
                            {$correctAnswer}
                            ```";

        //# 背景
        //ChatGPTのAPIを使って、ラテラルシンキングパズルを解くWebサービスを開発しています。
        //AI(ChatGPT)の助けを借りて、問題の自動作成から解答までのプロセスを実装しています。
        //問題作成部分はすでに別のプロンプトを使って実装されており、作成された問題はデータベースに保存されます。
        //ユーザーはこれらの問題を解き、解答をサービスに提出します。
        //このプロンプトはユーザーが提出した答えの正誤を判断するために使用されます。
        //ラテラルシンキングパズルの性質上、答えの判定はテキストの完全一致ではなく、意味やニュアンスの一致に基づいて行われます。

        # 指示
           //質問内容」と「正解」が与えられた場合、あなたの仕事は、正解の主な情報とニュアンスをとらえた簡潔な要約を作成することです。この要約は、ユーザーが投稿した回答の意味の類似性を判断する基準として使用されます。

           //要約が以下のようになっていることを確認してください：
           //- 簡潔であること： 簡潔であること：要点に焦点を当て、正解を短くまとめたものであること。
           //- 有益であること： 正解の主な情報やニュアンスを捉えていること。
           //- 中立的であること： 個人的な意見や解釈を加えないこと。

        #制約事項
           //- 日本語での出力

        ## 例
        //**質問内容 なぜ森田さんは残業中に窓の外を見ていたのですか。
        //**正解 森田さんは残業中、外から聞こえた悲鳴が気になった。ビルの窓から外を見ると、路地に死体が転がっており、近くに人がいた。森田さんは、その男が殺人を犯しているところを目撃してしまったことに気づいた。男は殺人を目撃したかもしれない森田さんに気づき、森田さんを黙らせるつもりで、『1階、2階、3階......』と指差しながらビルの階を数え始めた。

        //**予想されるあらすじ 森田さんは残業中、自分のビルから路地に入ったところで男が殺人を犯しているのを目撃した。男は階数を数えて森田さんの階を特定しようとした。
        //";

        // 2. そのプロンプトを使用して、正解文を要約するためのAPI通信を行う
        $summaryResponse = $client->post($endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'model' => "gpt-3.5-turbo-0613",
                "messages" => [
                    [
                        "role" => "system",
                        "content" => $summaryPrompt
                    ],
                    [
                        "role" => "user",
                        "content" => $summaryContent
                    ]

                ]
            ]
        ]);

        // 3. APIからのレスポンスを受け取り、要約されたテキストを取得
        $summaryData = json_decode($summaryResponse->getBody(), true);
        $summarizedAnswer = $summaryData['choices'][0]['message']['content'] ?? 'Failed to generate summary';

        // （あとで消す） ログを出力
        Log::info($summarizedAnswer);

        // ---- 要約のためのAPI通信の一連の処理はここまで -------

        $checkAnswerPrompt = "
        # Background
        We are developing a web service for solving lateral thinking puzzles using the ChatGPT API. 
        With the help of AI (ChatGPT), we have implemented a process that spans from automatic question creation to answering.
        The question creation part has already been implemented using a separate prompt, and the created questions are stored in a database.
        Users solve these puzzles and submit their answers to the service.
        This prompt is used to determine the correctness of the answers submitted by the users.
        Due to the nature of lateral thinking puzzles, the determination of the answer is based on the match in meaning or nuance, rather than an exact match in the text.

        # Instructions
        Let's play lateral thinking quizzes.
        In lateral thinking games, participants pose questions that can only be answered with 'Yes', 'No', or 'Irrelevant', to unravel the mystery presented by the quiz master.
        You are the one to determine whether the answers in the lateral thinking game are correct or incorrect.

        You are asked to evaluate the similarity between the provided by participant answer and the correct answer.
        Consider factors like meaning, relevance, and context in your evaluation. Refer also to the question content.
        Grammar and Spelling: The accuracy of grammar and spelling is not important in the evaluation of similarity. As long as the keywords match or have similar expressions, the answer will be considered similar.

        In the example below, the truth of the story is correct, therefore the output score should be 0.5 or higher.
        
        ## Answer and Output Example ①
        Correct Answer Example: This school was hosting a special event, and that day was 'Thanksgiving Day.' This student, being deaf from birth, could hear the birds singing and his friends' voices for the first time. His emotion was conveyed to the other students around him, and they also cried and laughed with deep emotion.
        User Answer Example: The student, being deaf from birth, could hear the birds singing and his friends' voices for the first time.

        ## Answer and Output Example ②
        Correct Answer Example: The woman happened to see the man on the train. She fell in love at first sight with his kind smile and attractive aura and made him the protagonist of her novel. She believed that he was her ideal partner, but she had never had any real contact with him. However, she believed that she could get closer to him through this novel and chose him as the protagonist. She was trying to build a bridge to him by writing while imagining his figure.
        User Answer Example: The woman happened to see the man on the train. She fell in love at first sight with his kind smile and attractive aura and made him the protagonist of her novel.
        
        ## Answer and Output Example ③
        Correct Answer Example: The student was living a wheelchair life. The seat he sat in was a special barrier-free desk and chair, and the way to the classroom exit was difficult for him. While the other students could naturally leave the classroom when the bell rang, he continued to sit, not understanding the situation. When the teacher noticed and went to help him, the teacher carried him and followed the other students, carrying his wheelchair to the exit. He was happy to help the other students move around without trouble.
        User Answer Example: The student was living a wheelchair life. The seat he sat in was a special barrier-free desk and chair, and the way to the classroom exit was difficult for him.


        # Output Format
        Strictly a numerical score between 0 and 1 for the similarity. You should never include any additional text or explanation. 

        ## Incorrect Output Format
        ```
        Similarity score: 0.5
        Explanation: The provided answer is unrelated and does not provide any information or explanation for why the students reacted the way they did.
        ```

        ## Expected Output Examples
        ```
        Similarity score: 0.5
        ```

        ```
        Similarity score: 1
        ```
        ";


        // 水平思考クイズを遊びましょう。
        // 水平思考ゲームでは、参加者は「はい」「いいえ」「関係ない」のいずれかだけで答えられる質問を出して、クイズマスターが出す謎を解明します。
        // あなたは水平思考ゲームの正解、不正解を判定をする者です。

        // 指示：ユーザーから提供された回答（User's answer）と模範回答（Correct answer）との類似性を評価してください。
        // 評価には、意味、関連性、文脈などの要素を考慮してください。問題文（Question content）も参考にしてください。
        // 類似性に対する数値スコアを0から1の範囲で提供してください。
        // 文法と綴り：類似性の評価において、文法や綴りの正確性は全く重要ではありません。キーワードが一致しているか、類似の表現があれば、回答は類似していると考えられます。

        //下記の例の場合、そのストーリーの真相は合っているため、出力されるスコアは0.5以上で出力する。
        // 回答と出力例①
        // 答え：この学校はある特別なイベントが開催されており、その日は「感謝の日」でした。その生徒は、生まれつき聴覚が遮断されていたため、初めて鳥の鳴き声や友人の声を聞くことができました。彼の感動が周りの生徒たちにも伝わり、彼らも感極まって泣き笑いしたのです。
        // ユーザー回答例：その生徒は、生まれつき聴覚が遮断されていたため、初めて鳥の鳴き声や友人の声を聞くことができました。

        // 回答と出力例②
        // 答え：その女性は、電車の中でたまたまその男性を見かけました。彼の優しそうな笑顔と魅力的な雰囲気に一目惚れし、小説の主人公にしたのです。彼女はその男性が自分の理想のパートナーだと思い込んでいましたが、現実の彼とは一度も接点がありませんでした。しかし、この小説を通じて彼に近づけると信じて、彼を主人公に選んだのです。彼女は彼の姿を想像しながら執筆することで、彼との架け橋を築こうとしていたのです。
        // ユーザー回答例:その女性は、電車の中でたまたまその男性を見かけました。彼の優しそうな笑顔と魅力的な雰囲気に一目惚れし、小説の主人公にしたのです。

        // 回答と出力例③
        // 答え：その生徒は車椅子生活を送っていました。彼の座った席はバリアフリーの特別な机と椅子であり、教室の出口までの道のりは彼にとっては困難でした。他の生徒たちはベルが鳴ったことで自然と教室を出て行けましたが、彼はその状況を理解できずに座り続けたのです。教師が彼を気づかって助けに行くと、教師は彼を抱えて他の生徒たちの後を追い、彼の車椅子を出口まで運びました。彼は周りの生徒たちが無理なく移動できるように手助けをすることを嬉しく思っていました。
        // ユーザー回答例:その生徒は車椅子生活を送っていました。彼の座った席はバリアフリーの特別な机と椅子であり、教室の出口までの道のりは彼にとっては困難でした。


        // 具体例：
        //もし正解が「光合成」であれば、それに類似した用語である「植物の食物作成過程」といった回答も一致と考えられます。
        //もし正解が「重力」であれば、「物体をお互いに引き付ける力」といった回答も類似と考えられます。
        // 出力形式：Integer型で0 ~ 1までの数字に限る。文字列は認めない。

        // 出力スタイル
        // 類似性については0から1の間の厳密に数値のスコアのみ。追加のテキストや説明を含めてはなりません。
        // 不正確な出力例
        // 類似スコア: 0.5
        // 説明: 提供された答えは関連性がなく、学生たちがそのように反応した理由についての情報や説明が提供されていません。

        // 期待される出力例
        // 類似スコア: 0.5
        // 類似スコア: 1

        $userAnswer = $request->input('userAnswer');

        $answerCheckContent = "
                    # Question content
                    ```
                    {$questionContent}
                    ```
                    # Correct answer
                    ```
                    {$summarizedAnswer}
                    ```
                    # Provided by participant answer
                    ```
                    {$userAnswer}
                    ```
                    ";

        Log::info($answerCheckContent);

        // $responseには、 ChatGPTにAPI通信をした結果(レスポンス)が入ってくる
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
                        "content" => $checkAnswerPrompt
                    ],
                    [
                        "role" => "user",
                        "content" => $answerCheckContent
                    ]

                ]
            ]
        ]);

        // $dataには $response をjson_decode(形式を整えるような処理)をした結果が入っている
        $data = json_decode($response->getBody(), true);

        // $similarity_scoreには $dataの中から必要な部分のみを抜き出した文字列("Similarity score: 0.0"などの実際の正答結果)が入ってくる
        // ※ $dataはそのままの状態だとChatGPTのAPIから返ってきた色々な情報が詰め込まれている（例えば処理日時など・・・）ので、そこから必要な部分のみを抽出する必要がある
        $str = $data['choices'][0]['message']['content'] ?? 'Failed to generate similarity score';

        if (preg_match('/\d+(\.\d+)?/', $str, $matches)) {  //正規表現、検索対象、検索結果
            $similarity_score = (float)$matches[0];
        } else {
            // 数値が見つからない場合の処理。適切なデフォルト値を設定したり、エラーログを出力したりする。
            $similarity_score = 0.0; // 例として、0.0をデフォルト値とします。
        }

        // 類似度がある程度以上であれば正解とする（この値は調整が必要）
        Log::info($similarity_score);
        $isCorrect = boolval($similarity_score >= 0.5);
        // Log::info("Setting similarity score: " . json_encode($similarity_score));

        Log::info("isCorrect: " . $isCorrect);
        return response()->json(['isCorrect' => $isCorrect]);
    }

    // 問題フォームを表示（使わないかも）
    public function showQuestionForm()
    {
        return view('questions.question_form');
    }
    // 問題の詳細ページを表示
    public function detail($id)
    {
        // ゲストユーザーはログインページにリダイレクト
        if (Auth::guest()) {
            // 未ログインの場合、選んだ問題のIDをセッションに保存
            session(['selected_question_id' => $id]);
            return redirect()->route('login');
        }
        // SoupGameQuestion モデルを使用して、指定されたIDに対応する問題を取得
        $question = SoupGameQuestion::find($id);
        // もし問題が存在しなければ、404エラーを返す
        if (!$question) {
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
        Log::info('Decoded JSON data:', ['data' => $data]);
        $questionContent = $data['choices'][0]['message']['content'] ?? 'Failed to generate question';
        Log::info('Question Content:', ['questionContent' => $questionContent]);

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

    public function saveQuestion(Request $request)
    {
        $data = [
            'generated_question' => $request->input('question_content'),
            'generated_answer' => $request->input('answer_content'),
            'genre' => $request->input('genre'),
            'difficulty' => $request->input('difficulty'),
        ];

        // モデルを通してデータベースに保存
        $result = SoupGameQuestion::storeNewQuestion($data);

        // 保存成功時の処理
        if ($result) {
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

        $prompt =
            "1.The answer to the question should be 'イエス', 'ノー', or 'どちらでもない'.
        2.If the content of the question is linked to the answer, respond with 'イエス'. 
        3.If the content of the question is not linked to the answer, respond with 'ノー'.
        4.The answer to the question should be provided in Japanese.
        5. For questions unrelated to the problem, display 'どちらでもない'.
        6. Format for responses Example >>  質問: (Content of the entered question is written here) 回答: どちらでもない
        ";

        //プロンプト
        //"1.質問に対する答えは、'イエス'、'ノー'、'どちらでもない'のいずれかにする。
        //2.質問の内容と答えがリンクしている場合は、'イエス'と答える。
        //3.質問の内容と答えがリンクしていない場合は「ノー」で答える。
        //4.質問に対する回答は日本語で行うこと。
        //5. 問題と関係のない質問には「どちらでもない」と表示する。
        //6. 回答例 >> 質問：（入力された質問内容をここに書く） 回答： どちらでもない。
        //";


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
        Log::info("API Response: ", $apiResponse);
        // APIからの回答をレスポンスとして返す
        return response()->json(['answer' => $apiResponse]);
    }
    // ーーーーーーーーーーー質問関連はここまでーーーーーーーーーーー

    //------------------ダッシュボード------------------------
    public function deleteQuestion($id) //QuestionController.phpに移動する
    {
        $question = SoupGameQuestion::find($id);  // idで問題を取得
        $question->delete();  // 問題を削除
        return redirect()->back()->with('success', 'Question deleted successfully.');  // 削除後のリダイレクト
    }

    public function edit($id) //QuestionController.phpに移動する
    {
        $question = SoupGameQuestion::find($id);  // idで問題を取得
        return view('questions.question_edit', ['question' => $question]);  // 編集ビューにデータを渡す
    }

    public function delete($id)
    {
        $question = SoupGameQuestion::find($id); // idで問題を取得
        if ($question) { // 質問が存在するか確認
            $question->delete(); // 問題を削除
            return redirect()->back()->with('success', 'Question deleted successfully.'); // 削除後に前のページにリダイレクト
        } else {
            return redirect()->back()->with('error', 'Question not found.'); // 問題が存在しない場合、エラーメッセージと共にリダイレクト
        }
    }


    // 入力データのバリデーションルール
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'question_content' => ['required', 'string', 'max:1000'],
            'answer_content' => ['required', 'string', 'max:1000'],
        ], [
            'question_content.required' => '問題文は必須です。',
            'answer_content.required' => '解答文は必須です。',
            'question_content.max' => '問題文は1000文字以内です。',
            'answer_content.max' => '解答は1000文字以内で入力してください。',
        ]);
    }
    public function update(Request $request, $id)
{
        // リクエストから全データを取得
        $data = $request->all();
        // バリデーション
        $this->validator($data)->validate();
    
    // IDに基づいて質問を取得
    $question = SoupGameQuestion::find($id);
    if (!$question) {
        // IDがデータベースにない場合、リダイレクトしてエラーメッセージを表示
        return redirect()->route('questions.index')->with('error', 'Question not found.');
    }

    // リクエストデータから値を取得し、質問の属性を更新
    $question->question_content = $request->input('question_content');
    $question->answer_content = $request->input('answer_content');
    $question->genre = $request->input('genre');
    $question->difficulty = $request->input('difficulty');
    // 他のフィールドもここで更新...

    // 変更をデータベースに保存
    $question->save();

    // 更新が成功したことを示すメッセージとともに、質問の一覧画面にリダイレクト
    return redirect()->route('questions.index')->with('success', 'Question updated successfully.');
}
}
