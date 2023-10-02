{{-- 自己紹介表示画面 --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">自己紹介設定画面</h1>

    <h2 class="text-center">ユーザー名: {{ session('current_player_name', 'デフォルト名') }}</h2>
</div>
@endsection
