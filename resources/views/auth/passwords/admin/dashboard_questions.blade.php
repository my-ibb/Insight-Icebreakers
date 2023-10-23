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
              <a class="nav-link active" aria-current="page" href="{{ route('admin.dashboard.questions') }}">問題</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.dashboard.self_introduction_questions') }}">自己紹介設問</a>
            </li>
        </ul>

        <br>
        <br>

        <!-- Questions Section -->
        <section id="questions" class="mb-4">
            <h2 class="h4 mb-3">
                問題一覧
                <a href="{{ route('questions.create') }}" class="btn btn-primary btn-sm">問題を作成する</a>
            </h2>
            
            @foreach($questions as $question)
                <div class="mb-4">
                    <h5><strong>問題:</strong>{{ $question->question_content }}</h5>
                    <p><strong>解答:</strong> {{ $question->answer_content }}</p>
                    <p><strong>ジャンル:</strong> {{ $question->genre }}</p>
                    <p><strong>難易度:</strong> {{ $question->difficulty }}</p>
                    <a href="{{ route('questions.edit', ['id' => $question->id]) }}" class="btn btn-warning btn-sm">編集</a>
                    <form method="POST" action="{{ route('question.delete', ['id' => $question->id]) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="削除" class="btn btn-danger btn-sm">
                    </form>
                </div>
            @endforeach
            {{ $questions->links() }} <!-- ページネーションリンクの追加 -->
        </section>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        document.querySelectorAll('.btn-danger').forEach((button) => {
            button.addEventListener('click', (event) => {
                if (!confirm('本当にこの問題・解答を削除しますか？')) {
                    event.preventDefault();
                }
            });
        });
    });
</script>

