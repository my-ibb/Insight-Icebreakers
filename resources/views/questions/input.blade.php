<!-- ベースとなるlayouts.appテンプレートを拡張 -->
@extends('layouts.app')  
<!-- ページタイトルをセット -->
@section('title', 'Question Content Page')  
<!-- メインコンテンツの開始 -->
@section('content')  

<!-- 表示される問題のタイトルと内容 -->
<h1>{{ $question['title'] }}</h1>
<p>{{ $question['content'] }}</p>

<!-- ユーザーが質問を入力するセクション -->
<h2>Input your question:</h2>
<!-- formアクションは、特定の問題IDに関連するルートに設定されています -->
<form action="{{ route('questions.storeQuestion', $question['id']) }}" method="post">
    @csrf  <!-- CSRFトークンを含む -->
    <!-- ユーザーが質問を入力できるテキストエリア -->
    <textarea name="question_content"></textarea>
    <!-- 質問を送信するボタン -->
    <button type="submit">Submit</button>
</form>
@endsection  <!-- メインコンテンツの終了 -->
