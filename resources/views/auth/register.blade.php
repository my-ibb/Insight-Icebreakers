@extends('layouts.app')

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
                <!-- エラーメッセージの表示 -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- フォーム -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">ユーザーネーム</label>
                        <input type="text" name="username" class="form-control" id="username" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">メールアドレス</label>
                        <input type="email" name="email" class="form-control" id="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">パスワード</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">パスワード（確認用）</label>
                        <input type="password" name="password_confirmation" class="form-control" id="password-confirm" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">登録</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
