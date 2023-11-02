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
    .turtle-color {
        background-color: lightgreen;
    }
    .lie-color {
        background-color: lightgoldenrodyellow;
    }
    .custom-btn {
        width: 100%; /* or 100% for full width */
        height: 80%; /* Adds space between the buttons when stacked */

    }
    /* Optional: Adjust text alignment */
    .adjust-text {
        display: block;
        text-align: center;
    }
</style>

<!-- ヘッダータイトル -->
<h1 class="header-title text-center mt-5">Insight Icebreakers</h1>
<h2 class="text-center">Welcome to the Games!</h2>

<div class="container text-center mt-4">
    <!-- ゲーム選択ボタン -->
    <div class="row justify-content-center mt-5">
        <div class="col-5"> <!-- Change col-md-4 to col-md-12 to stack buttons vertically -->
            <!-- ウミガメのスープゲームへのリンク -->
            <div class="card turtle-color">
                <h3 class="adjust-text">ウミガメのスープ</h3>
                <a href="{{ route('questions.index') }}" class="btn custom-btn align-self-center">
                    <!-- ウミガメのスープのイラスト追加 -->
                    <img src="{{ asset('images/toppage_turtle.png') }}" alt="ウミガメのスープ" class="img-fluid" width="175">
                    <span class="adjust-text">このゲームであそぶ</span>
                </a>
            </div>
        </div>

        <!-- ウミガメのスープの説明文 -->
        <div class="col-7 align-self-center"> <!-- 上部からのマージンを追加 -->
            <h3>ウミガメのスープとは</h3>
            <p>
                出題者が回答者に対し一見不可解な問題を出し、<br>
                回答者たちは質問を投げ、<br>
                それに対する“はい”、“いいえ”、“どちらでもない”<br>
                という返答から問題の答えを導き出すという<br>
                推理ゲームです。<br>
            </p>
            <p class="text-muted"><small>※ あそぶにはログインか新規登録が必要です。</small></p>

        </div>
    </div>

    <br>

    <div class="row justify-content-center mt-2">
        <div class="col-5">
            <div class="card align-self-center lie-color">
                <!-- 自己紹介嘘当てゲーム -->
                <h3 class="adjust-text">自己紹介嘘当てゲーム</h3>
                <a href="{{ route('selfIntroductionLieGame.index') }}" class="btn custom-btn align-self-center"> <!-- Change deep-cyan-btn to lie-btn for consistency -->
                    <!-- 自己紹介嘘当てゲームのイラスト追加 -->
                    <img src="{{ asset('images/toppage_human.png') }}" alt="自己紹介嘘当てゲーム" class="img-fluid" width="175">
                <span class="adjust-text">このゲームであそぶ</span>
                </a>
            </div>
        </div>

        <div class="col-7 align-self-center">
            <!-- 自己紹介嘘当てゲームの説明文 -->
            <div class="mt-3"> <!-- 上部からのマージンを追加 -->
                <h3>自己紹介嘘当てゲームとは</h3>
                <p>
                    設問に対して参加者が答え、<br>
                    その回答を元にAIで自己紹介文を生成します。<br>
                    自己紹介文は参加者の個人的な情報を述べており、<br>
                    その中には必ず一つだけ嘘の内容が含まれています。<br>
                    生成された自己紹介文を他の参加者に公開し、どの情報が嘘かを推測してもらい、<br>
                    参加者同士で話し合いながら嘘の内容を探し出します。
                </p>
            </div>
        </div>
    </div>
</div>

<!-- メインコンテンツの終了 -->
@endsection
