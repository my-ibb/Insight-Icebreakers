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
              <a class="nav-link" href="{{ route('admin.dashboard.questions') }}">Question</a>
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
                <a href="{{ route('register') }}" class="btn btn-secondary ml-2">Create New User</a>
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
    </div>
@endsection
