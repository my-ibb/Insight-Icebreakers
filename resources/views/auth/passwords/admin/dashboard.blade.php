@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Admin Dashboard</h1>

        <!-- Users Section -->
        <section id="users" class="mb-4">
            <h2 class="h4 mb-3">
                Users
                <a href="{{ route('register') }}" class="btn btn-secondary ml-2">新規登録</a>
            </h2>
            <ul class="list-group mb-5"> <!-- Added more bottom margin -->
                @foreach($users as $user)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        User Name： {{ $user['username'] }}
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        User Email： {{ $user['email'] }}
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        User Role： {{ $user['role'] }}
                    </li>
                    <!-- 以下のspanの部分は、後々ちゃんとボタンを押せるようにする必要がある！！！ -->
                    <span class="badge bg-primary rounded-pill">Edit | Delete</span>
                @endforeach
            </ul>
        </section>

        <!-- Questions Section -->
        <section id="questions" class="mb-4">
            <h2 class="h4 mb-3">
                Questions
                <a href="{{ route('questions.create') }}" class="btn btn-primary btn-sm">Create New</a>
            </h2>
            @foreach($questions as $question)
                <div class="mb-3">
                    <p>{{ $question->title }}</p>
                    <a href="{{ route('questions.edit', ['id' => $question->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form method="POST" action="{{ route('questions.delete', ['id' => $question->id]) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete" class="btn btn-danger btn-sm">
                    </form>
                </div>
            @endforeach
        </section>

        <!-- Self Introduction Questions Section -->
        <section id="self-introductions" class="mb-4">
            <h2 class="h4 mb-3">
                Self Introduction Questions
                <a href="{{ route('self-introductions.create') }}" class="btn btn-primary btn-sm">Create New</a>
            </h2>
            @foreach($introQuestions as $question)
                <div class="mb-3">
                    <p>{{ $question->title }}</p>
                    <a href="{{ route('self-introductions.edit', ['id' => $question->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form method="POST" action="{{ route('self-introductions.delete', ['id' => $question->id]) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete" class="btn btn-danger btn-sm">
                    </form>
                </div>
            @endforeach
        </section>
    </div>
@endsection
