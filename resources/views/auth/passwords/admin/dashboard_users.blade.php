@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">管理者ダッシュボード</h1>

        <!-- タブを表示しているのはここ！ -->
        <ul class="nav nav-pills">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="{{ route('admin.dashboard.users') }}">ユーザー</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.dashboard.questions') }}">問題</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.dashboard.self_introduction_questions') }}">自己紹介設問</a>
            </li>
        </ul>

        <br>
        <br>

        <!-- Users Section -->
        <section id="users" class="mb-4">
            <h2 class="h4 mb-3">
                ウミガメのスープユーザー
            </h2>
            @foreach($users as $user)            
                <ul class="list-group mb-5"> <!-- Added more bottom margin -->
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        ユーザーネーム： {{ $user['username'] }}
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        メールアドレス： {{ $user['email'] }}
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        役割： {{ $user['role'] }}
                    </li>
                    <!-- 削除・編集ボタン -->
                    <li class="list-group-item">
                        <div class="badge pull-left">
                            <a href="{{ route('user.edit', ['id' => $user->id]) }}" class="btn btn-warning btn-sm">編集</a>
                            <form method="POST" action="{{ route('user.delete', ['id' => $user->id]) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="削除" class="btn btn-danger btn-sm">
                            </form>
                        </div>
                    </li>
                </ul>
            @endforeach
            {{ $users->links() }} <!-- ページネーションリンクの追加 -->
        </section>
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelectorAll('.btn-danger').forEach((button) => {
        button.addEventListener('click', (event) => {
            if (!confirm('本当にこのユーザーを削除しますか？')) {
                event.preventDefault();
            }
        });
    });
});
</script>