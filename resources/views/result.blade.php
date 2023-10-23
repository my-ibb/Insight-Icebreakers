<!-- ベースとなるlayouts.appテンプレートを拡張 -->
@extends('layouts.app')  

<!-- ページタイトルをセット -->
@section('title', 'ウミガメのスープ - 結果画面')  

<!-- メインコンテンツの開始 -->
@section('content')

<!-- ページのタイトル -->  
<h1 class="header-title text-center mt-5">🐢 ウミガメのスープ 🐢</h1>
<h2 class="text-center">正解です！！</h2>
<br>

<div class="container">
    <div class="row">
        <div class="col-12 mb-4">  
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $question->genre }} - 難易度：{{ $question->difficulty }}</h5>
                    <!-- ユーザーネーム（仮定していますが、関連付けられているならば以下のように表示できます） -->
                    <p class="card-subtitle mb-2 text-muted">作成者：{{ $question->user ? $question->user->username : '名無しの太郎' }}</p>

                    <br>

                    <h5>▽ 問題文 ▽</h5>
                    <p class="card-text">{{ $question->question_content }}</p>

                    <br>

                    <h5>▽ 模範回答 ▽</h5>
                    <p class="card-text">{{ $question->answer_content }}</p>

                    <br>

                    <h5>▽ 今回のスコア ▽</h5>
                    <p class="card-text">
                        スコア：{{ $latestScore->score }}
                        <br>
                        質問回数：{{ $questionCount }}
                        <br>
                        ヒント使用回数：{{ $hintCount }}
                    </p>

                    <br>
                    <p class="text-muted"><small>スコアは "質問回数×1点", "ヒント使用回数×0.5点" で算出しています。</small></p>
                </div>
            </div>
        </div>
    </div>
</div>

<br>

<div class="container">
    <h2>▽ ランキング ▽</h2>
    <table class="table">
        <thead>
            <tr>
                <th>順位</th>
                <th>ユーザー名</th>
                <th>スコア</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($scores as $index => $score)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $score->user->username }}</td>
                <td>{{ $score->score }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
// resultページに遷移したタイミングでlocalStorageの値をクリア
function clearLocalStorageValues() {
    const questionId = {{ $question->id }};
    localStorage.removeItem(`hintCount_${questionId}`);
    localStorage.removeItem(`questionCount_${questionId}`);
    localStorage.removeItem(`previousQuestions_${questionId}`);
}

// ウィンドウが読み込まれたときにclearLocalStorageValuesを呼び出す
window.addEventListener('load', clearLocalStorageValues);
</script>

@endsection  <!-- メインコンテンツの終了 -->
