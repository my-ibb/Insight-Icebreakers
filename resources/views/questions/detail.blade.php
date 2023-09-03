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
<!-- 質問、ヒントをもらうボタン -->
<a href="{{ route('questions.question_form', ['id' => $question['id']]) }}" class="btn btn-secondary">質問をする</a>
<a href="javascript:void(0);" onclick="showHint()" class="btn btn-info">ヒントをもらう</a>
<div id="hint" style="display: none;"></div>

<!-- この部分がHTMLの最後に読み込まれます -->
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
    async function showHint() {
            const questionId = {{ $question->id }};  // Bladeテンプレートから問題IDを取得
    
            // API呼び出し
            const response = await fetch(`/getHint/${questionId}`);
            const data = await response.json();
    
            // ヒントを表示
            const hintElement = document.getElementById("hint");
            hintElement.textContent = data.hint;
            hintElement.style.display = "block";
        }

    </script>
@endsection
