<!-- /Applications/MAMP/htdocs/InsightIcebreakers/resources/views/questions/question_edit.blade.php -->

@extends('layouts.app')

@section('content')
    <h2>Question Edit</h2>

    <form action="{{ route('questions.update', $question->id) }}" method="post">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="content">Content</label>
            <textarea name="content" id="content" class="form-control">{{ old('content', $question->content) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
