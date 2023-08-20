@extends('layouts.app')

@section('title', 'Question and Answer Page')

@section('content')

<!-- resources/views/questions/show.blade.php -->
<h1>Question and Answer Page</h1>

<h1>{{ session('question') }}</h1>

<form method="post" action="{{ route('questions.storeAnswer') }}">
    @csrf

    <div>
        <label for="questionText">質問を入力:</label>
        <textarea name="questionText" id="questionText" rows="4" cols="50"></textarea>
    </div>

    <div>
        <button type="submit">質問する</button>
    </div>

</form>

@if(session('answer'))
    <h2>答え:</h2>
    <div>{{ session('answer') }}</div>
@endif

<h1>{{ $question['title'] }}</h1>
<p>{{ $question['content'] }}</p>
// 必要に応じてその他の情報も表示

@endsection
