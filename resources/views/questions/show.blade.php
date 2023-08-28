<!-- ベースとなるlayouts.appテンプレートを拡張 -->
@extends('layouts.app') 

<!-- ページタイトルをセット -->

@section('title', 'Question and Answer Page')  
<!-- メインコンテンツの開始 -->
@section('content')  

<h1>質問と回答ページ</h1>

<!-- セッションから取得した質問を表示 -->
<h1>{{ session('question') }}</h1>

<!-- 質問を入力するためのフォーム -->
<form method="post" action="{{ route('questions.storeAnswer') }}">
    @csrf  <!-- CSRFトークンを含む -->
    <div>
        <label for="questionText">質問を入力:</label>
        <textarea name="questionText" id="questionText" rows="4" cols="50"></textarea>
    </div>
    <div>
        <!-- 質問を送信するボタン -->
        <button type="submit">質問する</button>
    </div>
</form>

<!-- セッションから取得した答えを表示 -->
@if(session('answer'))
    <h2>答え:</h2>
    <div>{{ session('answer') }}</div>
@endif

<!-- データベースから取得した問題のタイトルと内容 -->
<h1>{{ $question['title'] }}</h1>
<p>{{ $question['content'] }}</p>
<!-- 必要に応じてその他の情報も表示 -->

<!-- メインコンテンツの終了 -->
@endsection  
