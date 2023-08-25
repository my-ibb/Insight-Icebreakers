<!-- ベースとなるlayouts.appテンプレートを拡張 -->
@extends('layouts.app')
<!-- ページタイトルをセット -->
@section('title', 'Home Page')
<!-- メインコンテンツの開始 -->
@section('content')

<!-- カスタムCSSスタイル -->
<style>
    /* 既存のスタイル */
    body {
        background-color: lightblue;
    }
    .header-title {
        color: blue;
        font-weight: bold;
    }
    .turtle-btn {
        background-color: lightgreen;
    }
    .lie-btn {
        background-color: lightyellow;
    }
    .custom-btn {
        width: 200px;
        height: 200px;
    }
</style>

<!-- ヘッダータイトル -->
<h1 class="header-title text-center mt-5">Insight Icebreakers</h1>
<h2 class="text-center">Welcome to the Games!</h2>
<div class="container text-center mt-4">

    <!-- ゲーム選択ボタン -->
    <h2>Select a Game</h2>
    <div class="row justify-content-center mt-5">
        <div class="col-md-4">
            <!-- ウミガメのスープゲームへのリンク -->
            <a href="{{ route('questions.index') }}" class="btn turtle-btn custom-btn">
                <!-- ウミガメのスープのイラスト追加 -->
                <img src="{{ asset('images/23048191.jpg') }}" alt="ウミガメのスープ" class="img-fluid" width="170">
                <span class="adjust-text">ウミガメのスープ</span>
            </a>
                        </a>
        </div>
        <div class="col-md-4">
            <!-- 自己紹介嘘当てゲーム（近日公開） -->
            <a href="#" class="btn deep-cyan-btn custom-btn">                <!-- 自己紹介嘘当てゲームのイラスト追加 -->
                <img src="{{ asset('images/24220784.jpg') }}" alt="自己紹介嘘当てゲーム" class="img-fluid">
                <span class="adjust-text">自己紹介嘘当てゲーム (Coming Soon)</span>
            </a>
        </div>
    </div>
    <!-- ログアウトボタン（ログインしている場合のみ表示） -->
    @if(isset($isLoggedIn) && $isLoggedIn)
    <div class="mt-4">
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-success">Logout</button>
        </form>
    </div>
    @endif
</div>
<!-- メインコンテンツの終了 -->
@endsection
