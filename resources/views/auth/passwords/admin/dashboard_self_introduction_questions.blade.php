@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Admin Dashboard</h1>

        <!-- タブを表示しているのはここ！ -->
        <ul class="nav nav-pills">
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.dashboard.users') }}">Users</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.dashboard.questions') }}">Question</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="{{ route('admin.dashboard.self_introduction_questions') }}">Self Introduction Questions</a>
            </li>
        </ul>

        <br>
        <br>

        <!-- Self Introduction Questions Section -->
        <section id="self-introductions" class="mb-4">
            <h2 class="h4 mb-3">
                Self Introduction Questions
                <a href="{{ route('self-introductions.create') }}" class="btn btn-primary btn-sm">Create New</a>
            </h2>
            
            <ul class="list-group mb-5"> <!-- Added more bottom margin -->
                @foreach($introQuestions as $question)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Question Content： {{ $question->content }}

                        <!-- 削除・編集ボタン -->
                        <div class="badge">
                            <a href="{{ route('self-introductions.edit', ['id' => $question->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form method="POST" action="{{ route('self-introductions.delete', ['id' => $question->id]) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="Delete" class="btn btn-danger btn-sm">
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
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

