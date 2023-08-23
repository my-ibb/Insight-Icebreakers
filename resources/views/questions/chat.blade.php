@extends('layouts.app')

@section('title', 'Chat Page')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Ask a Question</h1>
    <form method="post" action="{{ route('generate-chat-response') }}">
        @csrf
        <div class="form-group">
            <label for="chatQuestion">Your Question:</label>
            <input type="text" name="chatQuestion" id="chatQuestion" class="form-control">
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Ask</button>
        </div>
    </form>

    @if(session('answer'))
        <h2>Answer:</h2>
        <div class="alert alert-success">{{ session('answer') }}</div>
    @endif
</div>
@endsection
