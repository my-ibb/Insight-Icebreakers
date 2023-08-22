<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('title', 'Login Page')

@section('content')

<h1 class="text-center mb-4">Login Form</h1>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- エラーメッセージの表示 -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p class="mb-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <!-- ログインフォームのHTML -->
            <form method="post" action="/login">
                @csrf
                
                <div class="form-group">
                    <label for="email">メールアドレス:</label>
                    <input type="text" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password">パスワード:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <div class="form-group text-center">
                    <input type="submit" value="ログイン" class="btn btn-primary">
                </div>
            </form>
            <p class="text-center">
                <a href="{{ url('/register') }}">新規登録</a>
            </p>
        </div>
    </div>
</div>

@endsection
