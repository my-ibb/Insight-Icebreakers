{{-- 自己紹介設定 --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">自己紹介設定画面</h1>

    <form method="POST" action="{{ route('selfIntroductionLieGame.completeQuestion') }}">
    @csrf

        <!-- 設問内容入力 -->
        <div class="form-group">
            <label for="content">設問内容</label>
            <input type="text" class="form-control" id="content" name="content">
        </div>

        <!-- 送信ボタン -->
        <button type="submit" class="btn btn-primary">次へ</button>
    </form>
</div>
@endsection
