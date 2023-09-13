@extends('layouts.app')

@section('title', '問題詳細ページ')

@section('content')
<div class="col-12 mb-4">  
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $question->genre }} - 難易度：{{ $question->difficulty }}</h5>
            <p class="card-subtitle mb-2 text-muted">作成者：{{ $question->user ? $question->user->username : '名無しの太郎' }}</p>
            <p class="card-text">{{ $question->question_content }}</p>
            <button id="showAnswerButton-{{ $question->id }}" class="btn btn-warning" onclick="showAnswer({{ $question->id }})">ギブアップ（答えを見る）</button>

            <div id="answerArea-{{ $question->id }}" style="display:none;">
            <br>
            <h2>答え:</h2>
            <div class="alert alert-success">{{ $question->answer_content }}</div>
            </div>
        </div>
    </div>
</div>

<div id="chatContainer">
    <textarea id="userQuestion" rows="4" cols="50"></textarea>
    <button onclick="sendQuestion()">質問をする</button>
    <div id="questionCountContainer">質問回数：未使用</div>
    <div id="previousQuestionsContainer"></div>
    <div id="chatResponse"></div>
</div>

<!-- フィードバック表示エリア -->
<div id="feedbackMessage"></div>

<a href="javascript:void(0);" onclick="showHint()" class="btn btn-info">ヒントをもらう</a>
<div id="hint" style="display: none;"></div>
<div id="hintContainer"></div>
<div id="hintCountContainer">ヒント使用回数： 未使用</div>

<!-- 回答フォームエリア -->
<div id="answerFormArea">
    <form action="{{ route('checkAnswer', ['id' => $question->id]) }}" method="POST">
        @csrf
        <div class="form-group col-md-6">
            <label for="user_answer">あなたの回答：</label>
            <input type="text" class="form-control" id="user_answer" name="user_answer" style="height: 70px;" required>
        </div>
        <button type="submit" class="btn btn-primary">回答を送信</button>
    </form>
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
        hintCount++;
        const questionId = {{ $question->id }};
        const previousHintsString = previousHints.join(',');

        const response = await fetch(`/getHint/${questionId}?previousHints=${previousHintsString}&hintCount=${hintCount}`);
        const data = await response.json();

        previousHints.push(data.hint);

        const hintContainer = document.getElementById("hintContainer");
        const newHintElement = document.createElement("div");
        newHintElement.textContent = data.hint;
        hintContainer.appendChild(newHintElement);

        const hintCountContainer = document.getElementById("hintCountContainer");
        hintCountContainer.textContent = "ヒント使用回数： " + hintCount + "回目";
    }

    let questionCount = 0;
    let previousQuestions = [];

    document.addEventListener('DOMContentLoaded', (event) => {
        const form = document.querySelector('#answerFormArea form');
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const userAnswer = document.getElementById('user_answer').value;

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
            feedbackMessage.innerText = result.isCorrect ? '正解です！' : '不正解です';
            document.getElementById('answerFormArea').appendChild(feedbackMessage);
        });
    });

    async function sendQuestion() {
        const sendButton = document.querySelector('button[onclick="sendQuestion()"]'); // 質問を送信するボタンを特定
        sendButton.disabled = true;  // ボタンを無効化

        const userQuestion = document.getElementById("userQuestion").value;
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

    function setFeedbackMessage(message) {
    const feedbackDiv = document.getElementById("feedbackMessage");
    feedbackDiv.innerHTML = `<h2>${message}</h2>`;
}
    
}
</script>
@endsection
