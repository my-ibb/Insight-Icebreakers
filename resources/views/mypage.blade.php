<!-- resources/views/mypage.blade.php -->
@extends('layouts.app')

@section('title', 'マイページ')

@section('content')

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h1 class="display-4">My page</h1>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-3">
            <div class="card">
                <div class="card-header">
                    ユーザー情報
                </div>
                <div class="card-body">
                    <p class="card-text">名前: （ユーザー名）</p>
                    <p class="card-text">メール: （メールアドレス）</p>
                </div>
            </div>
        </div>

        <div class="col-9">
            <div class="card">
                <div class="card-header">
                    アクティビティ
                </div>
                <div class="card-body">
                    <p class="card-text">こちらには最近解いた問題や作成した問題のリストが表示されます。</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
