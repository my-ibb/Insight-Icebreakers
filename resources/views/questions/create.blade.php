@extends('layouts.app')

@section('title', 'Create a Question Page')

@section('content')


<!-- resources/views/questions/create.blade.php -->
<h1>Create a Question</h1>

<form method="post" action="{{ route('generate-question') }}">
    @csrf

    <div>
        <label for="genre">ジャンル:</label>
        <select name="genre" id="genre">
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

    <div>
        <label for="difficulty">難易度:</label>
        <select name="difficulty" id="difficulty">
            <option value="簡単">簡単</option>
            <option value="普通">普通</option>
            <option value="難しい">難しい</option>
        </select>
            </div>

    <div>
        <button type="submit">Generate Question</button>
    </div>

</form>

<!-- 生成された質問を表示するための領域 -->
@if(session('question'))
    <h2>Generated Question:</h2>
    <div>{{ session('question') }}</div>
@endif

<div>
    <label for="questionText">質問を入力:</label>
    <textarea name="questionText" id="questionText" rows="4" cols="50"></textarea>
</div>

<div>
    <button type="submit">質問する</button>
</div>

</form>

<!-- 生成された答えを表示するための領域 -->
@if(session('answer'))
    <h2>答え:</h2>
    <div>{{ session('answer') }}</div>
@endif

@endsection
