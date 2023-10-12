<!-- /Applications/MAMP/htdocs/InsightIcebreakers/resources/views/introduction_edit.blade.php -->

@extends('layouts.app')

@section('content')
    <h2>Self Introduction Question Edit</h2>

    <form action="{{ route('introQuestions.update', $introQuestions->id) }}" method="post">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="content">Content</label>
            <textarea name="content" id="content" class="form-control">{{ old('content', $introQuestions->content) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
