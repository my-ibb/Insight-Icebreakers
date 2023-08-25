<!-- resources/views/questions/index.blade.php -->

@extends('layouts.app')

@section('title', 'ウミガメのスープ - 問題一覧')

@section('content')
<h1>Question List</h1>

<div class="container">
    <div class="row">
        @foreach ($questions as $question)
        <div class="col-12 mb-4"> <!-- ここで縦に並べるための設定 -->
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('questions.detail', ['id' => $question['id']]) }}">{{ $question['title'] }}</a>
                    <!-- 答えを確認するリンクを追加 -->
                    <a href="{{ route('check-answer', $question['id']) }}" class="btn btn-secondary ml-2">答えを確認する</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<a href="{{ route('questions.create') }}" class="btn btn-primary">Question Create</a>
@endsection
