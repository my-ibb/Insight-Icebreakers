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
              <a class="nav-link active" aria-current="page" href="{{ route('admin.dashboard.questions') }}">Question</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('admin.dashboard.self_introduction_questions') }}">Self Introduction Questions</a>
            </li>
        </ul>

        <br>
        <br>

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
    </div>
@endsection
