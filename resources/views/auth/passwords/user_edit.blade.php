@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1>Edit User</h1>
        <form method="POST" action="{{ route('user.update', ['id' => $user->id]) }}">
            @csrf
            @method('PUT')
            // ここに各種入力フィールドを追加...
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>
@endsection
