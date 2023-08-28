<!-- layouts.appというBladeテンプレートを拡張しています -->
@extends('layouts.app')

<!-- ページタイトルを設定 -->
@section('title', '質問ページ')

<!-- contentセクションを開始 -->
@section('content')

<!-- メインコンテンツのコンテナ -->
<div class="container">

    <!-- ページのタイトル -->
    <h1 class="text-center mb-4">質問するページ</h1>
    
    <!-- チャットの質問を送信するためのフォーム -->
    <form method="post" action="{{ route('generate-chat-response') }}">
        
        <!-- CSRFトークンを生成 -->
        @csrf
        
        <!-- 質問入力欄 -->
        <div class="form-group">
            <label for="chatQuestion">質問:</label>
            <input type="text" name="chatQuestion" id="chatQuestion" class="form-control">
        </div>

        <!-- 質問を送信するボタン -->
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Ask</button>
        </div>
    </form>

    <!-- セッションに'answer'がある場合、その内容を表示 -->
    @if(session('answer'))
        <h2>Answer:</h2>
        <div class="alert alert-success">{{ session('answer') }}</div>
    @endif
</div>

<!-- contentセクションの終了 -->
@endsection
