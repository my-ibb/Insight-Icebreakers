<!-- layouts.appというBladeテンプレートを拡張しています -->
@extends('layouts.app')

<!-- ページのタイトルを設定 -->
@section('title', '問題詳細ページ')

<!-- contentセクションを開始 -->
@section('content')

<!-- 与えられた質問のタイトルと内容を表示 -->
<div class="col-12 mb-4">  
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $question->genre }} - 難易度：{{ $question->difficulty }}</h5>
            <!-- ユーザーネーム（仮定していますが、関連付けられているならば以下のように表示できます） -->
            <p class="card-subtitle mb-2 text-muted">作成者：{{ $question->user ? $question->user->username : '名無しの太郎' }}</p>
            <p class="card-text">{{ $question->question_content }}</p>            <!-- 問題の詳細ページへのリンク -->
            <!-- 答えを見るボタン -->
            <button id="showAnswerButton-{{ $question->id }}" class="btn btn-warning" onclick="showAnswer({{ $question->id }})">ギブアップ（答えを見る）</button>

            <!-- 答え表示エリア（最初は非表示） -->
            <div id="answerArea-{{ $question->id }}" style="display:none;">
            <br>
            <h2>答え:</h2>
            <div class="alert alert-success">{{ $question->answer_content }}</div>
            </div>
        </div>
    </div>
</div>

<!-- 質問関連はここから -->
<!-- GPTへの共有＞＞ここの部分をテキストフォームで質問を入力し、送信できるように変えたい！！送信した後は画面遷移せず、JavaScriptで画面上で表示を変えていきたい〜〜〜！！！ -->
<!-- テキストエリアと送信ボタン -->
<div id="chatContainer">
    <textarea id="userQuestion" rows="4" cols="50"></textarea>
    <button onclick="sendQuestion()">質問をする</button>
    
    <!-- 質問回数と過去の質問を表示するエリア -->
<div id="questionCountContainer">質問回数：未使用</div>
<div id="previousQuestionsContainer"></div>
    <div id="chatResponse"></div> <!-- ここに回答を表示 -->
</div>
<!-- 質問関連はここまで -->


<!-- ヒント関連はここから -->
<a href="javascript:void(0);" onclick="showHint()" class="btn btn-info">ヒントをもらう</a>
<div id="hint" style="display: none;"></div>
<div id="hintContainer"></div>

<!-- hintCountの値（回数）を表示する -->
<div id="hintCountContainer">ヒント使用回数： 未使用</div>

<!-- ヒント関連はここまで -->


<!-- ここからJavaScript -->
<!-- ボタンをクリックしたときに答えを表示するスクリプト -->
<script>
    function showAnswer(id) {
        // 各問題に対応する答えエリアを見つける
        var answerArea = document.getElementById('answerArea-' + id);
            // 答えエリアが現在表示されているかどうかをチェック
            //もし答えが表示されていたら非表示にし、非表示だったら表示にする
            if (answerArea.style.display === 'block') {
            // 答えエリアを非表示にする
            answerArea.style.display = 'none';
        } else {
    
        // 答えエリアを表示
        answerArea.style.display = 'block';
        }
    };

    //let hintCount = 0;  // ヒントを出した回数を保持する変数

    // ... (showAnswer関数はそのまま)

    let hintCount = 0;  // ヒントの回数を保存する変数
    let previousHints = [];  // これまでのヒントを保存する配列

    async function showHint() {
        hintCount++;  // ヒントボタンが押されたらインクリメント
        const questionId = {{ $question->id }};  // Bladeテンプレートから問題IDを取得

        // これまでのヒントを文字列で送信可能な形に変換（例えばカンマ区切りなど）
        const previousHintsString = previousHints.join(',');

        // API呼び出し
        const response = await fetch(`/getHint/${questionId}?previousHints=${previousHintsString}&hintCount=${hintCount}`);
        const data = await response.json();

        // 新しいヒントをpreviousHintsに追加
        previousHints.push(data.hint);

        // ヒントを表示するエリアを取得
        const hintContainer = document.getElementById("hintContainer");

        // 新しいヒントエレメントを作成
        const newHintElement = document.createElement("div");
        newHintElement.textContent = data.hint;

        // ヒントエレメントをコンテナに追加
        hintContainer.appendChild(newHintElement);

        // hintCountの値をHTMLに表示
        const hintCountContainer = document.getElementById("hintCountContainer");
        hintCountContainer.textContent = "ヒント使用回数： " + hintCount + "回目";
    };

    // スクリプトの最上位で変数を定義
    let questionCount = 0;
    let previousQuestions = [];

    // ページロード時の処理
    document.addEventListener('DOMContentLoaded', (event) => {
        // この部分でletを使って変数を宣言することで、sendQuestion関数でもその変数が使えるようになります。
        const questionId = {{ $question->id }}; // Bladeテンプレートから問題IDを取得
        // 質問回数をローカルストレージから取得
        let storedQuestionCount = localStorage.getItem(`questionCount_${questionId}`);
        if (storedQuestionCount) {
            questionCount = parseInt(storedQuestionCount);
            document.getElementById('questionCountContainer').textContent = "質問回数： " + questionCount + "回目";
        }    
            // 過去の質問をローカルストレージから取得
        let storedPreviousQuestions = localStorage.getItem(`previousQuestions_${questionId}`);
        if (storedPreviousQuestions) {
            previousQuestions = JSON.parse(storedPreviousQuestions);
            const previousQuestionsContainer = document.getElementById('previousQuestionsContainer');
            previousQuestions.forEach((question, index) => {
                const newQuestionElement = document.createElement("div");
                newQuestionElement.textContent = `質問${index + 1}: ${question}`;
                previousQuestionsContainer.appendChild(newQuestionElement);
            });
        }
    });

    async function sendQuestion() {
        // ユーザーが入力した質問を取得
        const userQuestion = document.getElementById("userQuestion").value;
        const questionId = {{ $question->id }};  // Bladeテンプレートから問題IDを取得
        
        // APIに送信
        try {
            const response = await fetch('/generate-chat-response', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',  // Laravel CSRFトークン
                },
                body: JSON.stringify({
                    chatQuestionContent: userQuestion,
                    questionId: questionId // もし必要なら問題IDも送信
                })
            });

            // レスポンスが正常であるかチェック
            if (response.ok) {
                    const data = await response.json();
                    const chatResponse = document.getElementById("chatResponse");
                    const newAnswerElement = document.createElement("div");
                    newAnswerElement.textContent = `質問${questionCount}: ${userQuestion} / 回答: ${data.answer}`; //これきく
                    chatResponse.appendChild(newAnswerElement);

                    // この回の質問を保存する
                    previousQuestions.push(userQuestion);

                    // 質問回数を更新
                    questionCount++;

                    document.getElementById('questionCountContainer').textContent = "質問回数： " + questionCount + "回目";

                    // 質問回数と過去の質問をローカルストレージに保存
                    localStorage.setItem(`questionCount_${questionId}`, questionCount);
                    localStorage.setItem(`previousQuestions_${questionId}`, JSON.stringify(previousQuestions));

                    // 過去の質問を更新
                    const previousQuestionsContainer = document.getElementById('previousQuestionsContainer');
                    const newQuestionElement = document.createElement("div");
                    newQuestionElement.textContent = `質問${questionCount}: ${userQuestion} / 回答: ${data.answer}`; //これきく
                    previousQuestionsContainer.appendChild(newQuestionElement);
                }
        } catch (error) {
            console.error("エラーが発生しました:", error);
        }
    }
</script>
@endsection
