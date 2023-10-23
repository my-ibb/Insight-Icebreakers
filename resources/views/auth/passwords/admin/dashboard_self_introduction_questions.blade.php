@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">管理者ダッシュボード</h1>

        <!-- タブを表示しているのはここ！ -->
        <ul class="nav nav-pills">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.dashboard.users') }}">ユーザー</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.dashboard.questions') }}">問題</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="{{ route('admin.dashboard.self_introduction_questions') }}">自己紹介設問</a>
            </li>
        </ul>

        <br>
        <br>

        <!-- Self Introduction Questions Section -->
        <section id="self-introductions" class="mb-4">
            <h2 class="h4 mb-3">
                自己紹介設問
                <a href="{{ route('admin.questions.create') }}" class="btn btn-primary btn-sm">設問を作成する</a>
            </h2>
            
            <ul class="list-group mb-5"> <!-- Added more bottom margin -->
                @foreach($introQuestions as $question)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        設問： {{ $question->content }}

                        <!-- 削除・編集ボタン -->
                        <div class="badge">
                            <a href="{{ route('self-introduction.edit', ['id' => $question->id]) }}" class="btn btn-warning btn-sm">編集</a>
                            <form method="POST" action="{{ route('self-introduction.delete', ['id' => $question->id]) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="削除" class="btn btn-danger btn-sm">
                            </form>
                        </div>
                    </li>
                @endforeach
                {{ $introQuestions->links() }} <!-- ページネーションリンクの追加 -->
            </ul>
        </section>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        document.querySelectorAll('.btn-danger').forEach((button) => {
            button.addEventListener('click', (event) => {
                if (!confirm('本当にこの設問を削除しますか？')) {
                    event.preventDefault();
                }
            });
        });
    });
</script>

