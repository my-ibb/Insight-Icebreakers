<!-- layouts.appというBladeテンプレートを拡張しています -->
@extends('layouts.app')

<!-- ページのタイトルを設定 -->
@section('title', 'Answer Confirmation Page')

<!-- contentセクションを開始 -->
@section('content')

<!-- ページのタイトル -->
<h1>Answer Confirmation Page</h1>

<!-- 答えを表示（$answer はコントローラーから渡された変数） -->
<p>答え：{{ $answer }}</p> 

<!-- contentセクションの終了 -->
@endsection
