
<!-- resources/views/questions/index.blade.php -->

@extends('layouts.app')

@section('title', 'ウミガメのスープ - 問題一覧')

@section('content')
<h1>Question List</h1>

<ul>
    @foreach ($questions as $question)
    <li>
        <a href="{{ route('questions.detail', ['id' => $question['id']]) }}">{{ $question['title'] }}</a>
    </li>
    @endforeach
</ul>
<a href="{{ route('questions.create') }}">Question Create</a>
@endsection
