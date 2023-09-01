<!-- ベースとなるlayouts.appテンプレートを拡張 -->
@extends('layouts.app')  

<!-- ページタイトルをセット -->
@section('title', 'ウミガメのスープ - 問題一覧')  

<!-- メインコンテンツの開始 -->
@section('content')
<!-- ページのタイトル -->  
<h1>問題一覧ページ</h1>  

<div class="container">
    <div class="row">
        <!-- 問題を一つずつ繰り返して表示 -->
        @foreach ($questions as $question)
        <!-- 各問題を縦に並べるための設定 -->
        <div class="col-12 mb-4">  
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $question->genre }} - 難易度：{{ $question->difficulty }}</h5>
                    <!-- ユーザーネーム（仮定していますが、関連付けられているならば以下のように表示できます） -->
                    <p class="card-subtitle mb-2 text-muted">作成者：{{ $question->user ? $question->user->username : '名無しの太郎' }}</p>
                    <p class="card-text">{{ $question->question_content }}</p>
                    <!-- 問題の詳細ページへのリンク -->
                    <a href="{{ route('questions.detail', ['id' => $question->id]) }}" class="btn btn-primary">この問題であそぶ</a>
                    <!-- 答えを見るボタン -->
                    <button id="showAnswerButton-{{ $question->id }}" class="btn btn-warning" onclick="showAnswer({{ $question->id }})">答えを見る</button>

                    <!-- 答え表示エリア（最初は非表示） -->
                    <div id="answerArea-{{ $question->id }}" style="display:none;">
                    <br>
                    <h2>答え:</h2>
                    <div class="alert alert-success">{{ $question->answer_content }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- 問題作成ページへのリンク -->
<a href="{{ route('questions.create') }}" class="btn btn-primary">問題を作成する</a>

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
}
</script>

@endsection  <!-- メインコンテンツの終了 -->
