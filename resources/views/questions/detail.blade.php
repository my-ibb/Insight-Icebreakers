<!-- layouts.appというBladeテンプレートを拡張しています -->
@extends('layouts.app')

<!-- ページのタイトルを設定 -->
@section('title', 'Question detail Page')

<!-- contentセクションを開始 -->
@section('content')

<!-- 与えられた質問のタイトルと内容を表示 -->
<h1>{{ $question['title'] }}</h1>
<p>{{ $question['content'] }}</p>

<!-- 回答するためのボタン。現在はログインページへ遷移するように設定 -->
<a href="{{ route('login') }}" class="btn btn-primary">回答する</a>

<!-- contentセクションの終了 -->
@endsection
