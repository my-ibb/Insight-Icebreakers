@extends('layouts.app')

@section('title', '問題詳細ページ')

@section('content')
<div class="container-fluid">
<div class="col-12 ">  
    <div class="card border-dark m-4" style="border-radius: 20px">
        <div class="card-body">
            <h5 class="card-title">{{ $question->genre }} - 難易度：{{ $question->difficulty }}</h5>
            <p class="card-subtitle mb-2 text-muted">作成者：{{ $question->user ? $question->user->username : '名無しの太郎' }}</p>
            <p class="card-text">{{ $question->question_content }}</p>

            <button id="hintBtn" href="javascript:void(0);" onclick="showHint()" class="btn btn-info">ヒントをもらう</button>
            <div id="hint" style="display: none;"></div>
            <div id="hintContainer"></div>
            <div id="hintCountContainer">ヒント使用回数： 未使用</div>

            <br>
            <button id="showAnswerButton-{{ $question->id }}" class="btn btn-warning" onclick="showAnswer({{ $question->id }})">ギブアップ（答えを見る）</button>

            <div id="answerArea-{{ $question->id }}" style="display:none;">
            <br>
            <h2>答え:</h2>
            <div class="alert alert-success">{{ $question->answer_content }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
<div class="col-6">
    <div class="card border-dark m-4" style="border-radius: 20px"> 
        <div class="card-body"> <!-- ここが変更された部分です -->
            <textarea id="userQuestion" rows="4" name="userQuestion" class="form-control" placeholder="質問をここに入力 &#13; (例)その人物は男ですか？" style="border-radius: 15px;"></textarea>
            <br>
            <div class="row">

                <!-- エラーメッセージの表示 -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="col-6" id="questionCountContainer">質問回数：未使用</div>
                <button id="questionBtn" class="col-5 btn btn-primary me-2" style="border-radius: 10px;" onclick="sendQuestion()">質問をする</button>
            </div>
            <div id="previousQuestionsContainer"></div>
            <div id="chatResponse"></div>
        </div>
    </div>
</div>

<!-- 回答フォームエリア -->
<div class="col-6" id="answerFormArea">
    <div class="card border-dark m-4" style="border-radius: 20px; padding: 20px;">
        <form id="answerForm" action="{{ route('checkAnswer', ['id' => $question->id]) }}" method="POST">

            @csrf
            <div class="form-group">
                <textarea class="form-control" id="user_answer" name="user_answer" placeholder="回答をここに入力 &#13; (例)男は〜だったから" rows="4" style="width: 100%; border-radius: 20px;" required></textarea>
            </div>

            <!-- 正解・不正解のフィードバック表示エリア -->
            <div id="feedbackMessage" class="pt-4">
                <div id="feedbackMessageArea"></div>
            </div>

            <!-- ここで border-radius を追加してボタンの枠を丸くしています -->
            <div class="d-flex justify-content-end" style="margin-top: 10px;"> <!-- ボタンの位置を調整 -->
                <button id="answerBtn" type="submit" class="btn btn-primary" style="border-radius: 10px;">回答を送信</button>
            </div>
        </form>        
    </div>
</div>
</div>

<script>
    function showAnswer(id) {
        var answerArea = document.getElementById('answerArea-' + id);
        if (answerArea.style.display === 'block') {
            answerArea.style.display = 'none';
        } else {
            answerArea.style.display = 'block';
        }
    }

    let hintCount = 0;
    let previousHints = [];

    async function showHint() {
        const hintButton = document.getElementById('hintBtn'); // "ヒントをもらう" ボタンを特定
        hintButton.disabled = true; // ボタンを無効化

        hintCount++;
        const questionId = {{ $question->id }};
        const previousHintsString = previousHints.join(',');

         // localStorage に hintCount を保存
        localStorage.setItem(`hintCount_${questionId}`, hintCount);

        try {
            const response = await fetch(`/getHint/${questionId}?previousHints=${previousHintsString}&hintCount=${hintCount}`);
            const data = await response.json();

            previousHints.push(data.hint);

            const hintContainer = document.getElementById("hintContainer");
            const newHintElement = document.createElement("div");
            newHintElement.textContent = data.hint;
            hintContainer.appendChild(newHintElement);

            const hintCountContainer = document.getElementById("hintCountContainer");
            hintCountContainer.textContent = "ヒント使用回数： " + hintCount + "回目";
        } catch (error) {
            console.error("There was an error when showing the hint: ", error);
        } finally {
            hintButton.disabled = false; // 処理が完了したらボタンを再び有効化
    }
}

    let questionCount = 0;
    let previousQuestions = [];

document.addEventListener('DOMContentLoaded', (event) => {
    const form = document.querySelector('#answerFormArea form');
    const answerBtn = document.getElementById('answerBtn'); // "回答を送信" ボタンを特定
        
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        answerBtn.disabled = true; // ボタンを無効化

        const userAnswer = document.getElementById('user_answer').value;

        if (userAnswer.length > 500) {
            alert('回答は500文字以内で入力してください。');
            answerBtn.disabled = false;  // ボタンを再有効化
            event.preventDefault();  // フォームの送信を中止
            return; // 処理を中断
        }

        try {
            // サーバーに回答を送信
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify({ userAnswer }),
            });

            const result = await response.json();

            // フィードバック表示
            let feedbackMessage = document.createElement('div');
            feedbackMessage.innerText = result.isCorrect ? '正解です！' : '不正解です'; //ここの正解です別になくても可

            if (result.isCorrect) { //isCorrectがある場合に下記の処理が実行される
                saveScore(); // 正解だった場合にスコアをつくる処理
                redirectResult(); //正解だった場合結果とランキング画面遷移
            }

            document.getElementById('feedbackMessageArea').appendChild(feedbackMessage);
        } catch (error) {
            console.error("回答の送信中にエラーが発生しました: ", error);
            // エラーメッセージを表示するなど、エラー時のハンドリングをここで行うことができます。
        } finally {
            answerBtn.disabled = false; // 処理が完了したらボタンを再び有効化
        }
    });
});

async function sendQuestion() {

    const sendButton = document.getElementById('questionBtn'); // 質問を送信するボタンを特定
    const userQuestion = document.getElementById("userQuestion").value;
// 文字数チェック
    if (userQuestion.length > 300) {
            alert("質問文は300文字以内で入力してください。");
            sendButton.disabled = false;  // ボタンを再有効化
            return; // 処理を中断
        }

    sendButton.disabled = true;  // ボタンを無効化
    const questionId = {{ $question->id }};

    try {
        const response = await fetch('/generate-chat-response', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    questionId: questionId,
                    chatQuestionContent: userQuestion
                })
        });
        // 以降の処理（応答が正常である場合のロジックなど）
            if (response.ok) {
                const data = await response.json();
                console.log("Received data:", data);
                questionCount++;

                const newEntry = {
                    question: userQuestion,
                    answer: data.answer
                };

                previousQuestions.push(newEntry);
                localStorage.setItem(`questionCount_${questionId}`, questionCount);
                localStorage.setItem(`previousQuestions_${questionId}`, JSON.stringify(previousQuestions));
                document.getElementById('questionCountContainer').textContent = "質問回数： " + questionCount + "回目";

                const previousQuestionsContainer = document.getElementById('previousQuestionsContainer');
                const chatResponse = document.getElementById("chatResponse");
                chatResponse.innerHTML = "";

                // 以前の質問と回答をクリア
                previousQuestionsContainer.innerHTML = "";
                chatResponse.innerHTML = "";

                previousQuestions.forEach((entry, index) => {
                    //const newQuestionElement = document.createElement("div");
                    //newQuestionElement.textContent = `質問${index + 1}: ${entry.question}`;
                    //previousQuestionsContainer.appendChild(newQuestionElement);

                    const newAnswerElement = document.createElement("div");
                    newAnswerElement.textContent = `質問${index + 1}: ${entry.answer}`;
                    chatResponse.appendChild(newAnswerElement);
                });
            } else {
                console.error("Failed to send question");
            }
        } catch (error) {
            console.error("There was an error sending the question", error);
        } finally {
        sendButton.disabled = false;  // ボタンを再び有効化
    }
} 

async function saveScore() {
    const questionId = {{ $question->id }};
    const questionCount = localStorage.getItem(`questionCount_${questionId}`) || 0;
    const hintCount = localStorage.getItem(`hintCount_${questionId}`) || 0;

    // スコアの計算
    const score = questionCount * 1 + hintCount * 0.5;

    try {
        const response = await fetch(`/questions/${questionId}/store-score`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                score: score,
                questionCount: questionCount,
                hintCount: hintCount,
            }),
        });

        if (response.ok) {
            console.log("Score saved successfully");
        } else {
            console.log("Failed to save score");
        }
    } catch (error) {
        console.error("There was an error saving the score", error);
    }
}
//正解後の画面遷移
async function redirectResult() { // 下記数字で渡してる
    const questionId = {{ $question->id }};
    const questionCount = localStorage.getItem(`questionCount_${questionId}`) || 0;
    const hintCount = localStorage.getItem(`hintCount_${questionId}`) || 0;
    window.location.href = `{{ route('result', ['questionId' => $question->id, 'questionCount' => 'QUESTION_COUNT_VALUE', 'hintCount' => 'HINT_COUNT_VALUE']) }}`
        .replace('QUESTION_COUNT_VALUE', questionCount)
        .replace('HINT_COUNT_VALUE', hintCount);
}


</script>
@endsection
