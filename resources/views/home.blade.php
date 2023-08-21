@extends('layouts.app')

@section('title', 'Home Page')

@section('content')

<!-- カスタムCSSスタイル -->
<style>
    /* 背景色とヘッダーのスタイル */
    body {
        background-color: lightblue;
    }
    .header-title {
        color: blue;
        font-weight: bold;
    }
    /* ボタンのスタイル */
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

<h1 class="header-title text-center mt-5">Insight Icebreakers</h1>
<h2 class="text-center">Welcome to the Games!</h2>
<div class="container text-center mt-4">
    <!-- ゲーム選択ボタン -->
    <h2>Select a Game</h2>
    <div class="row justify-content-center mt-5">
        <div class="col-md-4">
            <a href="{{ route('questions.index') }}" class="btn turtle-btn custom-btn">ウミガメのスープ</a>
        </div>
        <div class="col-md-4">
            <a href="#" class="btn lie-btn custom-btn">自己紹介嘘当てゲーム (Coming Soon)</a>
        </div>
    </div>

    <!-- ログアウトボタン -->
    @if($isLoggedIn)
    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
    @endif
</div>
@endsection
