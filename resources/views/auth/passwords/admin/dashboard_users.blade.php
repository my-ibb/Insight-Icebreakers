@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Admin Dashboard</h1>

        <!-- タブを表示しているのはここ！ -->
        <ul class="nav nav-pills">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="{{ route('admin.dashboard.users') }}">Users</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.dashboard.questions') }}">Questions</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.dashboard.self_introduction_questions') }}">Self Introduction Questions</a>
            </li>
        </ul>

        <br>
        <br>

        <!-- Users Section -->
        <section id="users" class="mb-4">
            <h2 class="h4 mb-3">
                Users
            </h2>
            @foreach($users as $user)            
                <ul class="list-group mb-5"> <!-- Added more bottom margin -->
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        User Name： {{ $user['username'] }}
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        User Email： {{ $user['email'] }}
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        User Role： {{ $user['role'] }}
                    </li>
                    <!-- 削除・編集ボタン -->
                    <li class="list-group-item">
                        <div class="badge pull-left">
                            <a href="{{ route('user.edit', ['id' => $user->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form method="POST" action="{{ route('user.delete', ['id' => $user->id]) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="Delete" class="btn btn-danger btn-sm">
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