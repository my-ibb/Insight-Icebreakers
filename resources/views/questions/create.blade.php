@extends('layouts.app')

@section('title', 'Create a Question Page')

@section('content')

<div class="container">
    <h1 class="text-center mb-4">Create a Question</h1>

    <!-- ジャンルと難易度の選択 -->
    <form method="post" action="{{ route('generate-question') }}" class="mb-4">
        @csrf

<!-- ...前略... -->
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

        <div class="form-group">
            <label for="difficulty">難易度:</label>
            <select name="difficulty" id="difficulty" class="form-control">
                <option value="簡単">簡単</option>
                <option value="普通">普通</option>
                <option value="難しい">難しい</option>
            </select>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Generate Question</button>
        </div>
    </form>

    <!-- 生成された質問を表示 -->
    @if(session('question'))
        <h2>Generated Question:</h2>
        <div class="alert alert-info">{{ session('question') }}</div>
    @endif

    <!-- 質問入力エリア -->
    <form method="post" action="ここに適切なルートを指定">
        @csrf

        <div class="form-group">
            <label for="questionText">質問を入力:</label>
            <textarea name="questionText" id="questionText" rows="4" class="form-control"></textarea>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">質問する</button>
        </div>
    </form>

    <!-- 生成された答えを表示 -->
    @if(session('answer'))
        <h2>答え:</h2>
        <div class="alert alert-success">{{ session('answer') }}</div>
    @endif
</div>

@endsection
