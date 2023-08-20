@extends('layouts.app')

@section('title', 'Question detail Page')

@section('content')

<h1>{{ $question['title'] }}</h1>
<p>{{ $question['content'] }}</p>
<!-- 回答ボタン -->
<a href="{{ route('login') }}" class="btn btn-primary">回答する</a>
@endsection
