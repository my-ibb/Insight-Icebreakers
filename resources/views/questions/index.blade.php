<!-- resources/views/questions/index.blade.php -->

@extends('layouts.app')

@section('title', 'ウミガメのスープ - 問題一覧')

@section('content')
<h1>Question List</h1>

<!-- 追加 -->
<div class="container">
    <div class="row">
        @foreach ($questions as $question)
        <div class="col-12 mb-4"> <!-- ここで縦に並べるための設定 -->
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('questions.detail', ['id' => $question['id']]) }}">{{ $question['title'] }}</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<!-- 追加終わり -->

<a href="{{ route('questions.create') }}" class="btn btn-primary">Question Create</a>
@endsection
