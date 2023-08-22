@extends('layouts.app')

@section('title', 'Register Page')

@section('content')

<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h1 class="text-center">新規登録</h1>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">ユーザー名:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">メールアドレス:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">パスワード:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">パスワードの確認:</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="text-center">
                        <input type="submit" class="btn btn-primary" value="登録">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
