{{-- resources/views/auth/login.blade.php --}}

@extends('layouts.app')

@section('title', 'Login Page')

@section('content')

<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h1 class="text-center">ログイン</h1>
            </div>
            <div class="card-body">
                <!-- エラーメッセージの表示 -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <form method="post" action="/login">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">メールアドレス:</label>
                        <input type="text" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">パスワード:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                <!-- パスワードリセットリンクの代わりにボタンを追加 -->
                    <div class="mt-3 text-center">
                        <button type="button" class="btn btn-link">
                        パスワードをお忘れですか？
                        </button>
                    </div>               
                </form>
                    <div class="row">
                        <div class="text-center">
                            <input type="submit" class="btn btn-primary" value="ログイン">
                        </div>
                    </div>

                <div class="text-center mt-2">
                    <a href="{{ route('register') }}" class="btn btn-secondary">新規登録</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
