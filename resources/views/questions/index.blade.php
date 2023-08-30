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
                    <a href="{{ route('questions.detail', ['id' => $question->id]) }}" class="btn btn-primary">詳細を見る</a>
                    <!-- 答えを確認するリンク -->
                    <a href="{{ route('check-answer', $question->id) }}" class="btn btn-secondary ml-2">答えを確認する</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- 問題作成ページへのリンク -->
<a href="{{ route('questions.create') }}" class="btn btn-primary">問題を作成する</a>
@endsection  <!-- メインコンテンツの終了 -->
