<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('title', 'Login Page')

@section('content')

<h1>Login Form</h1>

<!-- エラーメッセージの表示 -->
@if ($errors->any())
    <div>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<!-- ログインフォームのHTML -->
<form method="post" action="/login">
    @csrf

    <label for="email">メールアドレス:</label>
    <input type="text" id="email" name="email" required>
    <br>
    <label for="password">パスワード:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <input type="submit" value="ログイン">
</form>  
<p>
    <a href="{{ url('/register') }}">新規登録</a>
</p>

@endsection
