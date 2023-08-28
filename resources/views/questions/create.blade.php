<!-- layouts.appというBladeテンプレートを拡張しています -->
@extends('layouts.app')

<!-- ページのタイトルを設定 -->
@section('title', 'Create a Question Page')


<!-- contentセクションを開始 -->
@section('content')

<div class="container">
    <h1 class="text-center mb-4">問題作成</h1>

    <!-- ジャンルと難易度を選択するためのフォーム -->
    <form method="post" action="{{ route('generate-question') }}" class="mb-4">
        @csrf<!-- CSRF対策用のトークン -->

<!-- ...前略... -->
<!-- ジャンル選択のドロップダウン -->
<div class="form-group">
    <label for="genre">ジャンル:</label>
    <select name="genre" id="genre" class="form-control">
        <option value="ビジネス">ビジネス</option>
        <option value="学校">学校</option>
        <option value="怖い">怖い</option>
        <option value="恋愛">恋愛</option>
        <option value="日常">日常</option>
        <option value="非日常">非日常</option>
        <option value="スポーツ">スポーツ</option>
        <option value="サスペンス">サスペンス</option>
    </select>
</div>
<!-- ...後略... -->
<!-- 難易度選択のドロップダウン -->
        <div class="form-group">
            <label for="difficulty">難易度:</label>
            <select name="difficulty" id="difficulty" class="form-control">
                <option value="簡単">簡単</option>
                <option value="普通">普通</option>
                <option value="難しい">難しい</option>
            </select>
        </div>
        <!-- 送信ボタン -->
        <div class="text-center">
            <button type="submit" class="btn btn-primary">ChatGPTで問題を作る</button>
        </div>
    </form>

<!-- 既存の生成された質問を表示 -->
@if(session('question_content'))
    <h2>作られた問題:</h2>
    <div class="alert alert-info">{{ session('question_content') }}</div>
    
    <!-- 質問ボタン -->
    <a href="{{ route('chat-page') }}" class="btn btn-primary">質問する</a>
    
    <!-- ヒントボタン -->
    <a href="{{ route('hint-page') }}" class="btn btn-secondary">ヒントをもらう</a>

    <!-- 答えを見るボタン -->
    <button id="showAnswerButton" class="btn btn-warning">答えを見る</button>

    <!-- 答え表示エリア（最初は非表示） -->
    <div id="answerArea" style="display:none;">
        <h2>答え:</h2>
        <div class="alert alert-success">{{ session('answer_content') }}</div>
    </div>
@endif
</div>
<!-- contentセクションの終了 -->
@endsection

@section('scripts')
    <script src="{{ asset('js/toggleAnswer.js') }}"></script>
@endsection
