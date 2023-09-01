<!-- layouts.appというBladeテンプレートを拡張しています -->
@extends('layouts.app')

<!-- ページのタイトルを設定 -->
@section('title', '問題詳細ページ')

<!-- contentセクションを開始 -->
@section('content')

<!-- 与えられた質問のタイトルと内容を表示 -->
<h1>{{ $question['title'] }}</h1>
<p>{{ $question['content'] }}</p>

<!-- 質問、ヒントをもらうボタン -->
<a href="{{ route('questions.question_form', ['id' => $question['id']]) }}" class="btn btn-secondary">質問をする</a>
<a href="javascript:void(0);" onclick="showHint()" class="btn btn-info">ヒントをもらう</a>

<script>
    function showHint() {
        const hint = "これはヒントです。";
        const hintElement = document.getElementById("hint");
        hintElement.textContent = hint;
        hintElement.style.display = "block";
    }
</script>
<!-- これはヒントを表示するための要素です。デフォルトでは非表示（display: none） -->
<div id="hint" style="display: none;"></div>

<!-- contentセクションの終了 -->
@endsection

@section('scripts')
<!-- この部分がHTMLの最後に読み込まれます -->
<script>
    function showHint() {
        // ここでAjax通信を行ってサーバーからヒントを取得するか、
        // JavaScriptで直接ヒントを生成します。

        // 以下はサンプルヒントとしての文字列です。
        const hint = "これはヒントです。";

        // ヒントを表示するHTML要素を指定
        const hintElement = document.getElementById("hint");
        // ヒントを表示
        hintElement.textContent = hint;
        hintElement.style.display = "block";
    }
</script>
@endsection
