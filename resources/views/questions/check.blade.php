<!-- layouts.appというBladeテンプレートを拡張しています -->
@extends('layouts.app')

<!-- ページのタイトルを設定 -->
@section('title', '解答ページ')

<!-- contentセクションを開始 -->
@section('content')

<!-- ページのタイトル -->
<h1>解答ページ</h1>

<!-- 答えを表示（$answer はコントローラーから渡された変数） -->
<p>答え：{{ $answer }}</p> 

<!-- contentセクションの終了 -->
@endsection
