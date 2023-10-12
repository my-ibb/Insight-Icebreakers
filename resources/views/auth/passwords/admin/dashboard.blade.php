@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Admin Dashboard</h1>

        <div class="row">
            <div class="col-md-4">
                <section id="users" class="mb-4">
                    <h2 class="h4 mb-3">
                        Users
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Create New</a>
                    </h2>
                    <ul class="list-group">
                        @foreach($users as $user)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                User {{ $user }}
                                <span class="badge bg-primary rounded-pill">Edit | Delete</span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            </div>

            <div class="col-md-4">
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

            <div class="col-md-4">
                <section id="self-introductions" class="mb-4">
                    <h2 class="h4 mb-3">
                        Self Introduction Questions
                        <a href="{{ route('self-introductions.create') }}" class="btn btn-primary btn-sm">Create New</a>
                    </h2>
                    <!-- Self introduction questions management content will go here -->
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
        </div>
    </div>
@endsection
