@extends('layouts.app')  

@section('title', '管理者ログイン')  

@section('content')
<div class="container">
    <h1 class="mb-4">管理者ログイン</h1>
    <form method="POST" action="{{ route('admin.login') }}">
        @csrf  <!-- CSRFトークンを生成 -->

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">ログイン</button>
    </form>
</div>
@endsection