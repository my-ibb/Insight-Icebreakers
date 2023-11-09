{{-- resources/views/self-introductions/self_introduction_edit.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">質問編集</h1>

        <!-- エラーメッセージ -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('self-introduction.update', ['id' => $question->id]) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="content">質問</label>
                <input type="text" class="form-control" id="content" name="content" value="{{ $question->content }}">
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
