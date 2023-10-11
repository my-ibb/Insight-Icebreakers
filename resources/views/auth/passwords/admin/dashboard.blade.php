@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Admin Dashboard</h1>

        <div class="row">
            <div class="col-md-4">
                <section id="users" class="mb-4">
                    <h2 class="h4 mb-3">Users</h2>
                    <!-- Users management content will go here -->
                    <ul class="list-group">
                        @foreach(range(1,5) as $user)
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
                    <h2 class="h4 mb-3">Questions</h2>
                    @foreach($questions as $question)
                        <p>{{ $question->title }}</p>
                        <a href="{{ route('questions.edit', ['id' => $question->id]) }}">Edit</a>
                        <form method="POST" action="{{ route('questions.delete', ['id' => $question->id]) }}">
                            @csrf
                            @method('DELETE')
                            <input type="submit" value="Delete">
                        </form>
                    @endforeach
                </section>
            </div>

            <div class="col-md-4">
                <section id="self-introductions" class="mb-4">
                    <h2 class="h4 mb-3">Self Introduction Questions</h2>
                    <!-- Self introduction questions management content will go here -->
                </section>
            </div>
        </div>
    </div>
@endsection
